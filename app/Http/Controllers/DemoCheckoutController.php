<?php

namespace App\Http\Controllers;

use App\Models\DemoDiscountCode;
use App\Models\Order;
use App\Models\Ticket;
use App\Services\DemoPaymentFinalizer;
use App\Services\DemoRegistry;
use App\Services\DemoStripeGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class DemoCheckoutController extends Controller
{
    private const FIELDS = [
        'first_name' => 'string|max:255', 'last_name' => 'string|max:255',
        'dob' => 'date|before:today', 'birth_place' => 'string|max:255',
        'codice_fiscale' => 'string|max:16', 'residence_address' => 'string|max:255',
        'city' => 'string|max:255', 'zip_code' => 'string|max:20',
        'province' => 'string|max:255', 'region' => 'string|max:255',
        'country' => 'string|max:255', 'nationality' => 'string|size:2',
        'email' => 'email|max:255', 'phone' => 'string|max:30',
    ];

    public function __construct(private DemoRegistry $registry, private DemoPaymentFinalizer $finalizer, private DemoStripeGateway $stripe) {}

    public function store(Request $request, string $demo, string $race)
    {
        $site = $request->attributes->get('demo');
        $this->registry->race($site, $race);
        abort_unless($this->registry->isOpen($site, $race), 403);
        $registration = $site['registration'];
        $fields = array_values(array_unique(array_merge(['first_name', 'last_name', 'email'], $registration['fields'] ?? [])));
        $rules = [
            'locale' => ['required', Rule::in($site['locales'])],
            'privacy_consent' => ['accepted'],
            'payment_method' => ['required', Rule::in(['stripe'])],
            'discount_code' => ['nullable', 'string', 'max:100'],
        ];
        foreach ($fields as $field) {
            abort_unless(isset(self::FIELDS[$field]), 500);
            $required = in_array($field, $registration['optional_fields'] ?? [], true) ? 'nullable' : 'required';
            $rules[$field] = $required.'|'.self::FIELDS[$field];
        }
        if (isset($rules['dob']) && (int) ($registration['minimum_age'] ?? 0) > 0) {
            $rules['dob'] .= '|before_or_equal:'.now()->subYears((int) $registration['minimum_age'])->toDateString();
        }
        if (data_get($registration, 'shuttle.enabled')) {
            $rules['shuttle_needed'] = 'required|boolean';
        }
        if (data_get($registration, 'membership.enabled')) {
            $rules['membership_type'] = ['required', Rule::in(data_get($registration, 'membership.types', []))];
            $rules['membership_number'] = 'required_unless:membership_type,none|nullable|string|max:100';
        }
        if ($registration['medical_certificate'] ?? false) {
            $rules['medical_certificate_expires_on'] = 'required|date|after:today';
            $rules['medical_certificate'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:8192';
            $rules['medical_certificate_attestation'] = 'accepted';
        }
        $validated = $request->validate($rules);
        app()->setLocale($validated['locale']);

        $discount = null;
        if (! empty($validated['discount_code'])) {
            $discount = DemoDiscountCode::query()
                ->where('demo_slug', $site['slug'])
                ->where('code', strtoupper(trim($validated['discount_code'])))
                ->first();
            if (! $discount) return back()->withInput()->withErrors(['discount_code' => 'Invalid discount code.']);
        }

        $base = $this->registry->price($site, $race);
        $surcharge = ($validated['membership_type'] ?? null) === 'none'
            ? (float) data_get($registration, 'membership.non_member_surcharge', 0) : 0;
        $price = max(0, round($base + $surcharge - (float) ($discount?->amount ?? 0), 2));
        abort_if($price < 0.50, 422, 'Test payments must be at least EUR 0.50.');
        $certificatePath = null;
        if ($request->hasFile('medical_certificate')) {
            $directory = 'demo-documents/'.$site['slug'];
            $disk = Storage::disk('local');
            $disk->makeDirectory('demo-documents');
            $disk->setVisibility('demo-documents', 'private');
            $disk->makeDirectory($directory);
            $disk->setVisibility($directory, 'private');
            $certificatePath = $request->file('medical_certificate')->store('demo-documents/'.$site['slug'], 'local');
            if (! $certificatePath) return back()->withInput()->withErrors(['medical_certificate' => 'The file could not be saved.']);
            $disk->setVisibility($certificatePath, 'private');
        }

        try {
            $order = DB::transaction(function () use ($site, $race, $validated, $price, $surcharge, $discount, $certificatePath) {
                $order = Order::create([
                    'demo_slug' => $site['slug'], 'race_slug' => $race,
                    'group_code' => 'DEMO_'.strtoupper(Str::random(18)),
                    'total_amount' => $price, 'payment_method' => 'stripe',
                    'discount_code' => $discount?->code,
                    'status' => 'pending', 'locale' => $validated['locale'],
                    'privacy_consent_at' => now(),
                ]);
                $name = $this->registry->race($site, $race)['name'];
                $order->tickets()->create([
                    'first_name' => $validated['first_name'], 'last_name' => $validated['last_name'],
                    'email' => $validated['email'], 'route_choice' => $name,
                    'dob' => $validated['dob'] ?? '1990-01-01',
                    'birth_place' => $validated['birth_place'] ?? 'N/A',
                    'codice_fiscale' => $validated['codice_fiscale'] ?? null,
                    'residence_address' => $validated['residence_address'] ?? 'N/A',
                    'city' => $validated['city'] ?? 'N/A',
                    'zip_code' => $validated['zip_code'] ?? 'N/A',
                    'province' => $validated['province'] ?? 'N/A',
                    'region' => $validated['region'] ?? 'N/A',
                    'country' => $validated['country'] ?? 'N/A',
                    'nationality' => strtoupper($validated['nationality'] ?? 'IT'),
                    'phone' => $validated['phone'] ?? 'N/A',
                    'tshirt_size' => 'N/A', 'shuttle_needed' => (bool) ($validated['shuttle_needed'] ?? false), 'celiac' => false,
                    'price_paid' => $price, 'payment_tag' => 'Stripe test', 'status' => 'pending',
                    'membership_type' => $validated['membership_type'] ?? null,
                    'membership_number' => ($validated['membership_type'] ?? null) === 'none' ? null : ($validated['membership_number'] ?? null),
                    'non_member_surcharge' => $surcharge,
                    'medical_certificate_expires_on' => $validated['medical_certificate_expires_on'] ?? null,
                    'medical_certificate_path' => $certificatePath,
                    'eligibility_status' => data_get($site, 'registration.membership.enabled') || data_get($site, 'registration.medical_certificate') ? 'pending_review' : 'not_required',
                ]);
                return $order;
            });
        } catch (Throwable $exception) {
            if ($certificatePath) Storage::disk('local')->delete($certificatePath);
            throw $exception;
        }

        try {
            $this->stripe->paymentIntent($order);
            return redirect()->route('demo.checkout.payment', ['demo' => $site['slug'], 'order_code' => $order->group_code]);
        } catch (Throwable $exception) {
            $order->update(['status' => 'payment_error']);
            Log::error('Could not start demo payment', ['order_id' => $order->id, 'error' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['payment_method' => 'The test payment could not be started.']);
        }
    }

    public function payment(Request $request, string $demo, string $order_code)
    {
        $order = $this->order($demo, $order_code);
        app()->setLocale($order->locale ?? 'it');
        if ($order->status === 'paid') return redirect()->route('demo.checkout.success', ['demo' => $demo, 'order_code' => $order_code]);
        $intent = $this->stripe->paymentIntent($order);
        if ($this->paid($order, $intent)) {
            $this->finalizer->finalize($order);
            return redirect()->route('demo.checkout.success', ['demo' => $demo, 'order_code' => $order_code]);
        }

        return view('demo.checkout.payment', $this->viewData($request, $order) + [
            'clientSecret' => $intent->client_secret,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    public function success(Request $request, string $demo, string $order_code)
    {
        $order = $this->order($demo, $order_code);
        app()->setLocale($order->locale ?? 'it');
        if ($order->status !== 'paid' && $order->stripe_payment_intent_id) {
            $intent = $this->stripe->paymentIntent($order);
            if ($this->paid($order, $intent)) $this->finalizer->finalize($order);
        }
        $order->refresh()->load('tickets');
        if ($order->status !== 'paid') return redirect()->route('demo.checkout.cancel', ['demo' => $demo, 'order_code' => $order_code]);

        return view('demo.checkout.success', $this->viewData($request, $order));
    }

    public function cancel(Request $request, string $demo, string $order_code)
    {
        $order = $this->order($demo, $order_code);
        app()->setLocale($order->locale ?? 'it');
        return view('demo.checkout.cancel', $this->viewData($request, $order));
    }

    public function ticket(Request $request, string $demo, string $unique_code)
    {
        $ticket = Ticket::query()->with('order')
            ->where('unique_ticket_code', $unique_code)
            ->whereHas('order', fn ($query) => $query->where('demo_slug', $demo)->where('status', 'paid'))
            ->where('status', 'active')->firstOrFail();
        app()->setLocale($ticket->order->locale ?? 'it');
        return view('demo.checkout.ticket', [
            'ticket' => $ticket, 'demo' => $request->attributes->get('demo'),
        ]);
    }

    private function order(string $demo, string $code): Order
    {
        return Order::query()->with('tickets')->where('demo_slug', $demo)->where('group_code', $code)->firstOrFail();
    }

    private function viewData(Request $request, Order $order): array
    {
        return ['order' => $order, 'demo' => $request->attributes->get('demo')];
    }

    private function paid(Order $order, $intent): bool
    {
        return $intent->id === $order->stripe_payment_intent_id
            && $intent->status === 'succeeded'
            && $intent->amount_received === (int) round($order->total_amount * 100)
            && $intent->currency === 'eur'
            && ($intent->metadata->demo_slug ?? null) === $order->demo_slug
            && ($intent->metadata->group_code ?? null) === $order->group_code;
    }
}
