<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\CurrentDemo;
use App\Services\DemoPaymentFinalizer;
use App\Services\DemoStripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class DemoCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.stripe.key', 'pk_test_example');
        config()->set('services.stripe.secret', 'sk_test_example');
        config()->set('services.stripe.webhook_secret', 'whsec_example');
    }

    public function test_live_key_closes_checkout_even_when_demo_is_open(): void
    {
        config()->set('services.stripe.secret', 'sk_live_unsafe');
        $this->get('/coast-run/iscrizione/42')->assertOk()->assertSee('disabled', false);
        $this->post('/coast-run/iscrizione/42', [])->assertForbidden();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_each_demo_validates_its_own_fields_and_prices_on_the_server(): void
    {
        $gateway = Mockery::mock(DemoStripeGateway::class);
        $gateway->shouldReceive('paymentIntent')->once()->andReturnUsing(function ($order) {
            $order->update(['stripe_payment_intent_id' => 'pi_test_coast']);
            return (object) ['id' => 'pi_test_coast', 'status' => 'requires_payment_method'];
        });
        app()->instance(DemoStripeGateway::class, $gateway);
        $this->post('/cro-trail/iscrizione/21', ['locale' => 'it', 'payment_method' => 'stripe'])
            ->assertSessionHasErrors(['membership_type', 'medical_certificate', 'medical_certificate_expires_on']);
        $this->assertDatabaseCount('orders', 0);
        $response = $this->post('/coast-run/iscrizione/10', [
            'locale' => 'it', 'payment_method' => 'stripe', 'privacy_consent' => 1,
            'first_name' => 'Alex', 'last_name' => 'Runner', 'email' => 'alex@example.test',
            'dob' => '1990-01-01',
        ])->assertRedirect();
        $this->assertStringContainsString('/coast-run/checkout/', $response->headers->get('Location'));

        app(CurrentDemo::class)->set('coast-run');
        $order = Order::firstOrFail();
        $this->assertSame('coast-run', $order->demo_slug);
        $this->assertSame(19.0, (float) $order->total_amount);
        $this->assertSame('coast-run', $order->tickets->first()->demo_slug);
        $this->assertSame('pi_test_coast', $order->stripe_payment_intent_id);
    }

    public function test_cro_trail_medical_upload_is_private_and_paid_entry_waits_for_review(): void
    {
        Storage::fake('local');
        $gateway = Mockery::mock(DemoStripeGateway::class);
        $gateway->shouldReceive('paymentIntent')->once()->andReturnUsing(function ($order) {
            $order->update(['stripe_payment_intent_id' => 'pi_test_cro']);
            return (object) ['id' => 'pi_test_cro', 'status' => 'requires_payment_method'];
        });
        app()->instance(DemoStripeGateway::class, $gateway);
        $this->post('/cro-trail/iscrizione/21', [
            'locale' => 'it', 'payment_method' => 'stripe', 'privacy_consent' => 1,
            'first_name' => 'Alex', 'last_name' => 'Runner', 'dob' => '1990-01-01',
            'birth_place' => 'Torino', 'residence_address' => 'Via Roma 1', 'city' => 'Torino',
            'zip_code' => '10100', 'province' => 'TO', 'region' => 'Piemonte',
            'country' => 'Italia', 'nationality' => 'IT', 'email' => 'alex@example.test',
            'phone' => '123456789', 'membership_type' => 'none',
            'medical_certificate_expires_on' => '2027-12-31',
            'medical_certificate' => UploadedFile::fake()->create('certificate.pdf', 20, 'application/pdf'),
            'medical_certificate_attestation' => 1,
        ])->assertRedirect();

        app(CurrentDemo::class)->set('cro-trail');
        $order = Order::firstOrFail();
        $this->assertSame(38.0, (float) $order->total_amount);
        $ticket = $order->tickets->first();
        $this->assertSame('pending_review', $ticket->eligibility_status);
        Storage::disk('local')->assertExists($ticket->medical_certificate_path);
        app(DemoPaymentFinalizer::class)->finalize($order);
        app(DemoPaymentFinalizer::class)->finalize($order);
        $this->assertSame('pending_review', $ticket->fresh()->status);
        $this->assertNull($ticket->fresh()->unique_ticket_code);
        $this->actingAs(User::factory()->create(['is_demo_admin' => true]));
        $this->get('/admin/tickets/'.$ticket->id.'/document?demo=cro-trail')->assertOk();
        $this->post('/admin/tickets/'.$ticket->id.'/approve', ['demo' => 'cro-trail'])->assertRedirect();
        $this->assertSame('active', $ticket->fresh()->status);
        $this->get('/cro-trail/ticket/'.$ticket->fresh()->unique_ticket_code)->assertOk()->assertSee('Alex Runner');
        $this->assertFalse(config('demo_platform.outbound_email_enabled'));
        $this->assertSame('log', config('mail.default'));
    }
}
