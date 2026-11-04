<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DemoPaymentFinalizer;
use App\Services\DemoRegistry;
use App\Services\CurrentDemo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleStripe(Request $request, DemoRegistry $registry, DemoPaymentFinalizer $finalizer, CurrentDemo $current)
    {
        $secret = config('services.stripe.webhook_secret');
        if (! $registry->testStripeReady() || blank($secret)) return response('Test webhook unavailable', 503);

        try {
            $event = \Stripe\Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature'), $secret);
        } catch (\UnexpectedValueException|\Stripe\Exception\SignatureVerificationException $exception) {
            return response('Invalid webhook', 400);
        }

        if ($event->livemode) return response('Live payments are disabled', 400);
        if ($event->type !== 'payment_intent.succeeded') return response('Ignored', 200);
        $intent = $event->data->object;
        $demo = $intent->metadata->demo_slug ?? null;
        $orderId = $intent->metadata->order_id ?? null;
        $code = $intent->metadata->group_code ?? null;
        if (! is_string($demo) || ! isset($registry->all()[$demo]) || ! ctype_digit((string) $orderId)) {
            return response('Invalid demo metadata', 400);
        }
        $current->set($demo);
        $order = Order::query()->whereKey((int) $orderId)->where('group_code', $code)->first();
        if (! $order || $order->payment_method !== 'stripe'
            || $order->stripe_payment_intent_id !== $intent->id
            || $intent->currency !== 'eur'
            || $intent->amount_received !== (int) round($order->total_amount * 100)) {
            Log::warning('Demo webhook order mismatch', ['demo' => $demo, 'code' => $code]);
            return response('Order mismatch', 400);
        }

        $finalizer->finalize($order);
        return response('OK', 200);
    }
}
