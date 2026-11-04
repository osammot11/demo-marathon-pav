<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use App\Services\OrderPaymentFinalizer;
use App\Services\CroTrailRegistration;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class CheckoutController extends Controller
{
    public function store(Request $request, string $distance, CroTrailRegistration $registration)
    {
        abort_unless($registration->isOpen($distance), 403);
        app()->setLocale($request->input('locale') === 'en' ? 'en' : 'it');

        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,paypal',
            'locale' => 'required|in:it,en',
            'privacy_consent' => 'accepted',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'birth_place' => 'required|string|max:255',
            'codice_fiscale' => 'nullable|string|max:16',
            'residence_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'nationality' => 'required|string|size:2',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'membership_type' => 'required|in:uisp,eps,fidal,runcard,none',
            'membership_number' => 'required_unless:membership_type,none|nullable|string|max:100',
            'medical_certificate_expires_on' => 'required|date|after:today',
            'medical_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:8192',
            'medical_certificate_attestation' => 'accepted',
        ]);

        if (! in_array($validated['payment_method'], $registration->methods(), true)) {
            return back()->withInput()->withErrors(['payment_method' => 'Metodo di pagamento non disponibile.']);
        }

        $price = $registration->totalPrice($distance, $validated['membership_type']);
        $surcharge = $validated['membership_type'] === 'none' ? $registration->nonMemberSurcharge() : 0;
        $certificatePath = $request->file('medical_certificate')->store('crotrail/medical-certificates', 'local');
        if ($certificatePath === false) {
            return back()->withInput()->withErrors(['medical_certificate' => __('crotrail.reg_certificate_storage_error')]);
        }

        try {
            $order = DB::transaction(function () use ($validated, $distance, $price, $surcharge, $certificatePath) {
                $order = Order::create([
                    'group_code' => 'CRO_' . strtoupper(\Illuminate\Support\Str::random(16)),
                    'total_amount' => $price,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'pending',
                    'locale' => $validated['locale'],
                    'privacy_consent_at' => now(),
                ]);

                $order->tickets()->create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'route_choice' => 'CroTrail ' . $distance,
                    'dob' => $validated['dob'],
                    'birth_place' => $validated['birth_place'],
                    'codice_fiscale' => $validated['codice_fiscale'] ?? null,
                    'residence_address' => $validated['residence_address'],
                    'city' => $validated['city'],
                    'zip_code' => $validated['zip_code'],
                    'province' => $validated['province'],
                    'region' => $validated['region'],
                    'country' => $validated['country'],
                    'nationality' => strtoupper($validated['nationality']),
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'tshirt_size' => 'N/A',
                    'shuttle_needed' => false,
                    'celiac' => false,
                    'price_paid' => $price,
                    'payment_tag' => ucfirst($validated['payment_method']),
                    'status' => 'pending',
                    'membership_type' => $validated['membership_type'],
                    'membership_number' => $validated['membership_type'] === 'none' ? null : $validated['membership_number'],
                    'non_member_surcharge' => $surcharge,
                    'medical_certificate_expires_on' => $validated['medical_certificate_expires_on'],
                    'medical_certificate_path' => $certificatePath,
                    'eligibility_status' => 'pending_review',
                ]);

            return $order;
            });
        } catch (Throwable $e) {
            Storage::disk('local')->delete($certificatePath);
            throw $e;
        }

        try {
            return $validated['payment_method'] === 'stripe'
                ? $this->processStripePayment($order)
                : $this->processPaypalPayment($order);
        } catch (Throwable $e) {
            $order->update(['status' => 'payment_error']);
            Log::error('Errore avvio pagamento CroTrail', [
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'message' => $e->getMessage(),
            ]);

            return back()->withInput()->withErrors(['payment_method' => 'Non siamo riusciti ad avviare il pagamento. Riprova più tardi.']);
        }
    }

    private function processStripePayment(Order $order)
    {
        $this->createOrRetrieveStripePaymentIntent($order);

        return redirect()->route('checkout.payment', ['order_code' => $order->group_code]);
    }

    public function payment($order_code)
    {
        $order = Order::with('tickets')->where('group_code', $order_code)->firstOrFail();
        abort_unless(str_starts_with($order->group_code, 'CRO_'), 404);
        app()->setLocale($order->locale ?? 'it');

        if ($order->payment_method !== 'stripe') {
            return redirect()->route('checkout.cancel', ['order_code' => $order->group_code])->with('error', 'Metodo di pagamento non valido per questo ordine.');
        }

        if ($order->status === 'paid') {
            return redirect()->route('checkout.success', ['order_code' => $order->group_code]);
        }

        try {
            $paymentIntent = $this->createOrRetrieveStripePaymentIntent($order);

            if ($paymentIntent->status === 'succeeded' && $paymentIntent->amount_received === (int) round($order->total_amount * 100) && $paymentIntent->currency === 'eur') {
                app(OrderPaymentFinalizer::class)->finalize($order);

                return redirect()->route('checkout.success', ['order_code' => $order->group_code]);
            }
        } catch (Throwable $e) {
            $order->update(['status' => 'payment_error']);
            Log::error('Errore creazione Payment Intent Stripe', [
                'order_id' => $order->id,
                'group_code' => $order->group_code,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('registration', ['distance' => str_replace('CroTrail ', '', $order->tickets->first()->route_choice)])
                ->withErrors(['payment_method' => 'Non siamo riusciti ad avviare il pagamento con carta. Riprova tra poco.']);
        }

        return view('checkout.payment', [
            'order' => $order,
            'clientSecret' => $paymentIntent->client_secret,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    private function createOrRetrieveStripePaymentIntent(Order $order)
    {
        $stripeSecret = config('services.stripe.secret');

        if (blank($stripeSecret) || blank(config('services.stripe.key'))) {
            throw new RuntimeException('Stripe keys non configurate.');
        }

        \Stripe\Stripe::setApiKey($stripeSecret);

        if ($order->stripe_payment_intent_id) {
            try {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($order->stripe_payment_intent_id);

                if ($paymentIntent->status !== 'canceled') {
                    return $paymentIntent;
                }
            } catch (Throwable $e) {
                Log::warning('Payment Intent Stripe non recuperabile, ne creo uno nuovo', [
                    'order_id' => $order->id,
                    'group_code' => $order->group_code,
                    'stripe_payment_intent_id' => $order->stripe_payment_intent_id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $order->loadMissing('tickets');
        $firstTicket = $order->tickets->first();

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => (int) round($order->total_amount * 100),
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'description' => 'Iscrizione CroTrail 2027 - Ordine ' . $order->group_code,
            'receipt_email' => $firstTicket?->email,
            'metadata' => [
                'order_id' => (string) $order->id,
                'group_code' => $order->group_code,
            ],
        ]);

        $order->update(['stripe_payment_intent_id' => $paymentIntent->id]);

        return $paymentIntent;
    }

    private function processPaypalPayment(Order $order)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('checkout.success', ['order_code' => $order->group_code]),
                "cancel_url" => route('checkout.cancel', ['order_code' => $order->group_code]),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => number_format((float) $order->total_amount, 2, ".", "")
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    $order->update(['paypal_order_id' => $response['id']]); 
                    return redirect()->away($links['href']);
                }
            }
        }

        return redirect()->route('checkout.cancel', ['order_code' => $order->group_code])->with('error', 'Qualcosa è andato storto con PayPal.');
    }

    public function success(Request $request, $order_code)
    {
        $order = Order::with('tickets')->where('group_code', $order_code)->firstOrFail();
        abort_unless(str_starts_with($order->group_code, 'CRO_'), 404);
        app()->setLocale($order->locale ?? 'it');

        // 1. Se l'ordine risulta già pagato, mostriamo solo la vista.
        if ($order->status === 'paid') {
            app(OrderPaymentFinalizer::class)->finalize($order);
            $order->refresh()->load('tickets');
            return view('checkout.success', compact('order'));
        }

        // --- 2. VERIFICA E CATTURA PAGAMENTO PAYPAL ---
        if ($order->payment_method === 'paypal' && $request->query('token') === $order->paypal_order_id && $order->paypal_order_id) {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            
            $response = $provider->capturePaymentOrder($request->query('token'));
            $capture = data_get($response, 'purchase_units.0.payments.captures.0');

            if (isset($response['status'], $response['id'])
                && $response['status'] === 'COMPLETED'
                && $response['id'] === $order->paypal_order_id
                && data_get($capture, 'status') === 'COMPLETED'
                && data_get($capture, 'amount.currency_code') === 'EUR'
                && (float) data_get($capture, 'amount.value') === (float) $order->total_amount) {
                app(OrderPaymentFinalizer::class)->finalize($order);
            } else {
                return redirect()->route('checkout.cancel', ['order_code' => $order->group_code])->with('error', 'Il pagamento PayPal non è stato autorizzato o è stato annullato.');
            }
        }
        
        // --- 3. VERIFICA PAGAMENTO STRIPE ---
        elseif ($order->payment_method === 'stripe' && $order->stripe_payment_intent_id) {
            $stripeSecret = config('services.stripe.secret');

            if (blank($stripeSecret)) {
                return redirect()->route('checkout.cancel', ['order_code' => $order->group_code])->with('error', 'Stripe non è configurato correttamente.');
            }

            \Stripe\Stripe::setApiKey($stripeSecret);
            $paymentIntent = \Stripe\PaymentIntent::retrieve($order->stripe_payment_intent_id);
            
            if ($paymentIntent->status === 'succeeded' && $paymentIntent->amount_received === (int) round($order->total_amount * 100) && $paymentIntent->currency === 'eur') {
                app(OrderPaymentFinalizer::class)->finalize($order);
            } else {
                return redirect()->route('checkout.cancel', ['order_code' => $order->group_code])->with('error', 'Il pagamento Stripe non è stato completato.');
            }
        }
        $order->refresh()->load('tickets');
        // Only a verified gateway payment can activate an entry.
        if ($order->status === 'paid') {
            return view('checkout.success', compact('order'));
        }

        // Fallback in caso di tentativi anomali
        return redirect()->route('checkout.cancel', ['order_code' => $order->group_code])->with('error', 'Errore nella convalida del pagamento.');
    }

    public function downloadTicket($unique_code)
    {
        $ticket = Ticket::with('order')->where('unique_ticket_code', $unique_code)->firstOrFail();
        abort_unless(str_starts_with($ticket->order->group_code, 'CRO_'), 404);

        if ($ticket->order->status !== 'paid' || $ticket->status !== 'active') {
            return back()->with('error', 'Il pagamento non è ancora stato confermato. Riprova tra poco.');
        }

        $pdf = Pdf::loadView('pdf.ticket', compact('ticket'));
        $fileName = 'Biglietto_CroTrail_' . str_replace(' ', '_', $ticket->last_name) . '.pdf';
        
        return $pdf->download($fileName);
    }

    public function cancel(string $order_code)
    {
        $order = Order::where('group_code', $order_code)->firstOrFail();
        abort_unless(str_starts_with($order->group_code, 'CRO_'), 404);
        app()->setLocale($order->locale ?? 'it');

        return view('checkout.cancel', compact('order'));
    }
}
