<?php

namespace App\Services;

use App\Models\Order;
use RuntimeException;

class DemoStripeGateway
{
    public function __construct(private DemoRegistry $registry) {}

    public function paymentIntent(Order $order)
    {
        if (! $this->registry->testStripeReady()) {
            throw new RuntimeException('Only Stripe test credentials are allowed.');
        }
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        if ($order->stripe_payment_intent_id) {
            $intent = \Stripe\PaymentIntent::retrieve($order->stripe_payment_intent_id);
            if ($intent->status !== 'canceled') return $intent;
        }

        $intent = \Stripe\PaymentIntent::create([
            'amount' => (int) round($order->total_amount * 100),
            'currency' => 'eur', 'payment_method_types' => ['card'],
            'description' => 'TEST '.$order->demo_slug.' registration '.$order->group_code,
            'metadata' => [
                'demo_slug' => $order->demo_slug, 'order_id' => (string) $order->id,
                'group_code' => $order->group_code,
            ],
        ]);
        $order->update(['stripe_payment_intent_id' => $intent->id]);
        return $intent;
    }
}
