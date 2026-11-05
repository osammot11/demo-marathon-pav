<?php

namespace App\Http\Controllers;

use App\Models\DemoDiscountCode;
use App\Models\Order;
use App\Services\DemoRegistry;
use App\Services\DemoStripeGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class DemoGroupCheckoutController extends Controller
{
    private const FIELDS = [
        'first_name' => 'string|max:255', 'last_name' => 'string|max:255',
        'email' => 'email|max:255', 'dob' => 'date|before:today',
        'city' => 'string|max:255', 'phone' => 'string|max:30',
    ];

    public function __construct(private DemoRegistry $registry, private DemoStripeGateway $stripe) {}

    public function store(Request $request, string $demo, string $race)
    {
        $site = $request->attributes->get('demo');
        abort_unless(data_get($site, 'group_registration.enabled'), 404);
        $this->registry->race($site, $race);
        abort_unless($this->registry->isOpen($site, $race), 403);
        $maximum = min(10, max(1, (int) data_get($site, 'group_registration.max_participants', 10)));

        $top = $request->validate([
            'locale' => ['required', Rule::in($site['locales'])],
            'privacy_consent' => 'accepted',
            'payment_method' => ['required', Rule::in(['stripe'])],
            'discount_code' => 'nullable|string|max:100',
            'participants' => 'required|array|min:1|max:'.$maximum,
            'participants.*.race' => ['required', Rule::in(array_keys($site['races']))],
        ]);
        app()->setLocale($top['locale']);
        $rows = $request->all()['participants'];
        if (! array_is_list($rows)) {
            throw ValidationException::withMessages(['participants' => 'Participants must be in sequence.']);
        }

        $people = [];
        $errors = [];
        foreach ($rows as $index => $row) {
            $selectedRace = $row['race'];
            abort_unless($this->registry->isOpen($site, $selectedRace), 403);
            $registration = $this->registry->registrationForRace($site, $selectedRace);
            $rules = ['race' => ['required', Rule::in(array_keys($site['races']))]];
            foreach (array_unique(array_merge(['first_name', 'last_name', 'email'], $registration['fields'] ?? [])) as $field) {
                abort_unless(isset(self::FIELDS[$field]), 500);
                $rules[$field] = (in_array($field, $registration['optional_fields'] ?? [], true) ? 'nullable' : 'required').'|'.self::FIELDS[$field];
            }
            $type = $row['membership_type'] ?? null;
            $minimumAge = max(
                (int) ($registration['minimum_age'] ?? 0),
                is_string($type) ? (int) ($registration['minimum_age_by_membership'][$type] ?? 0) : 0,
            );
            if (isset($rules['dob']) && $minimumAge > 0) {
                $rules['dob'] .= '|before_or_equal:'.now()->subYears($minimumAge)->toDateString();
            }
            if (data_get($registration, 'membership.enabled')) {
                $rules['membership_type'] = ['required', Rule::in(data_get($registration, 'membership.types', []))];
                $rules['membership_number'] = 'required|string|max:100';
            }
            if (data_get($registration, 'medical_certificate')) {
                $isRequired = ! in_array($type, data_get($registration, 'medical_certificate_optional_for', []), true);
                $rules['medical_certificate_expires_on'] = ($isRequired ? 'required' : 'nullable').'|date|after:today';
                $rules['medical_certificate'] = ($isRequired ? 'required' : 'nullable').'|file|mimes:pdf,jpg,jpeg,png|max:8192';
                $rules['medical_certificate_attestation'] = $request->hasFile("participants.$index.medical_certificate") ? 'accepted' : 'nullable';
            }
            $validator = Validator::make($row, $rules);
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $messages) {
                    $errors["participants.$index.$field"] = $messages;
                }
                continue;
            }
            $people[] = [
                'race' => $selectedRace,
                'registration' => $registration,
                'fields' => $validator->validated(),
                'file' => data_get($registration, 'medical_certificate') ? $request->file("participants.$index.medical_certificate") : null,
            ];
        }
        if ($errors) throw ValidationException::withMessages($errors);

        $discount = null;
        if (! empty($top['discount_code'])) {
            $discount = DemoDiscountCode::where('demo_slug', $site['slug'])
                ->where('code', strtoupper(trim($top['discount_code'])))->first();
            if (! $discount) throw ValidationException::withMessages(['discount_code' => 'Invalid discount code.']);
        }
        $totalCents = 0;
        foreach ($people as &$person) {
            $surcharge = $person['fields']['membership_type'] ?? null;
            $surcharge = $surcharge === 'none' ? (float) data_get($person['registration'], 'membership.non_member_surcharge', 0) : 0;
            $cents = (int) round(($this->registry->price($site, $person['race']) + $surcharge - (float) ($discount?->amount ?? 0)) * 100);
            if ($cents < 50) throw ValidationException::withMessages(['discount_code' => 'Each test registration must cost at least €0.50.']);
            $person['price'] = $cents / 100;
            $person['surcharge'] = $surcharge;
            $totalCents += $cents;
        }
        unset($person);

        $paths = [];
        try {
            if (collect($people)->contains(fn ($person) => $person['file'] !== null)) {
                $disk = Storage::disk('local');
                $directory = 'demo-documents/'.$site['slug'];
                $disk->makeDirectory('demo-documents');
                $disk->setVisibility('demo-documents', 'private');
                $disk->makeDirectory($directory);
                $disk->setVisibility($directory, 'private');
                foreach ($people as $index => $person) {
                    if (! $person['file']) continue;
                    $path = $person['file']->store($directory, 'local');
                    if (! $path) throw ValidationException::withMessages(["participants.$index.medical_certificate" => 'The file could not be saved.']);
                    $paths[$index] = $path;
                    $disk->setVisibility($path, 'private');
                }
            }
            $order = DB::transaction(function () use ($site, $top, $people, $paths, $discount, $totalCents) {
                $races = array_unique(array_column($people, 'race'));
                $order = Order::create([
                    'race_slug' => count($races) === 1 ? array_values($races)[0] : null,
                    'group_code' => 'DEMO_'.strtoupper(Str::random(18)),
                    'total_amount' => $totalCents / 100,
                    'payment_method' => 'stripe', 'discount_code' => $discount?->code,
                    'status' => 'pending', 'locale' => $top['locale'], 'privacy_consent_at' => now(),
                ]);
                foreach ($people as $index => $person) {
                    $data = $person['fields'];
                    $registration = $person['registration'];
                    $order->tickets()->create([
                        'race_slug' => $person['race'],
                        'route_choice' => $this->registry->race($site, $person['race'])['name'],
                        'first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'],
                        'dob' => $data['dob'] ?? '1990-01-01', 'birth_place' => 'N/A',
                        'residence_address' => 'N/A', 'city' => $data['city'] ?? 'N/A',
                        'zip_code' => 'N/A', 'province' => 'N/A', 'region' => 'N/A',
                        'country' => 'N/A', 'nationality' => 'IT', 'phone' => $data['phone'] ?? 'N/A',
                        'tshirt_size' => 'N/A', 'shuttle_needed' => false, 'celiac' => false,
                        'price_paid' => $person['price'], 'payment_tag' => 'Stripe test', 'status' => 'pending',
                        'membership_type' => $data['membership_type'] ?? null,
                        'membership_number' => $data['membership_number'] ?? null,
                        'non_member_surcharge' => $person['surcharge'],
                        'medical_certificate_expires_on' => $data['medical_certificate_expires_on'] ?? null,
                        'medical_certificate_path' => $paths[$index] ?? null,
                        'eligibility_status' => data_get($registration, 'membership.enabled') || data_get($registration, 'medical_certificate') ? 'pending_review' : 'not_required',
                    ]);
                }
                return $order;
            });
        } catch (Throwable $exception) {
            if ($paths) Storage::disk('local')->delete(array_values($paths));
            throw $exception;
        }

        try {
            $this->stripe->paymentIntent($order);
            return redirect()->route('demo.checkout.payment', ['demo' => $site['slug'], 'order_code' => $order->group_code]);
        } catch (Throwable $exception) {
            $order->update(['status' => 'payment_error']);
            Log::error('Could not start demo group payment', ['order_id' => $order->id, 'error' => $exception->getMessage()]);
            return back()->withInput()->withErrors(['payment_method' => 'The test payment could not be started.']);
        }
    }
}
