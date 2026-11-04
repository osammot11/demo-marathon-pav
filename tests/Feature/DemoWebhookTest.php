<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Services\CurrentDemo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.stripe.key', 'pk_test_example');
        config()->set('services.stripe.secret', 'sk_test_example');
        config()->set('services.stripe.webhook_secret', 'whsec_test_signature');
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $this->call('POST', '/webhook/stripe', [], [], [], ['HTTP_STRIPE_SIGNATURE' => 'bad'], '{}')
            ->assertStatus(400);
    }

    public function test_signed_webhook_uses_metadata_and_is_idempotent(): void
    {
        app(CurrentDemo::class)->set('coast-run');
        $order = Order::create([
            'group_code' => 'DEMO_WEBHOOK_1', 'race_slug' => '10', 'total_amount' => 19,
            'payment_method' => 'stripe', 'status' => 'pending', 'locale' => 'en',
            'stripe_payment_intent_id' => 'pi_test_webhook',
        ]);
        $ticket = $order->tickets()->create([
            'first_name' => 'Alex', 'last_name' => 'Runner', 'email' => 'alex@example.test',
            'route_choice' => 'Run 10 km', 'dob' => '1990-01-01', 'birth_place' => 'N/A',
            'residence_address' => 'N/A', 'city' => 'N/A', 'zip_code' => 'N/A',
            'province' => 'N/A', 'region' => 'N/A', 'country' => 'N/A', 'nationality' => 'IT',
            'phone' => 'N/A', 'tshirt_size' => 'N/A', 'payment_tag' => 'Stripe test',
            'price_paid' => 19, 'status' => 'pending', 'eligibility_status' => 'not_required',
        ]);

        $payload = json_encode([
            'id' => 'evt_test_1', 'object' => 'event', 'created' => time(),
            'livemode' => false, 'type' => 'payment_intent.succeeded',
            'data' => ['object' => [
                'id' => 'pi_test_webhook', 'object' => 'payment_intent',
                'amount_received' => 1900, 'currency' => 'eur',
                'metadata' => ['demo_slug' => 'coast-run', 'order_id' => (string) $order->id, 'group_code' => $order->group_code],
            ]],
        ], JSON_THROW_ON_ERROR);
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test_signature');
        $server = ['HTTP_STRIPE_SIGNATURE' => 't='.$timestamp.',v1='.$signature, 'CONTENT_TYPE' => 'application/json'];

        $this->call('POST', '/webhook/stripe', [], [], [], $server, $payload)->assertOk();
        $firstCode = $ticket->fresh()->unique_ticket_code;
        $this->assertSame('paid', $order->fresh()->status);
        $this->assertNotNull($firstCode);
        $this->call('POST', '/webhook/stripe', [], [], [], $server, $payload)->assertOk();
        $this->assertSame($firstCode, $ticket->fresh()->unique_ticket_code);
    }
}
