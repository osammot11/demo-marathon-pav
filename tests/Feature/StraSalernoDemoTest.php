<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\DemoDiscountCode;
use App\Services\CurrentDemo;
use App\Services\DemoPaymentFinalizer;
use App\Services\DemoStripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class StraSalernoDemoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.stripe.key', 'pk_test_example');
        config()->set('services.stripe.secret', 'sk_test_example');
    }

    public function test_four_races_pages_locales_and_assets_are_scoped(): void
    {
        $this->get('/strasalerno')->assertOk()->assertSee('Quattro percorsi')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/strasalerno/en')->assertOk()->assertSee('Four routes');
        foreach (['half', '10k', 'run', 'family'] as $race) {
            $this->get('/strasalerno/gare/'.$race)->assertOk();
            $this->get('/strasalerno/en/iscrizione/'.$race)->assertOk();
        }
        foreach (['percorsi', 'programma', 'contatti', 'privacy'] as $page) {
            $this->get('/strasalerno/'.$page)->assertOk();
            $this->get('/strasalerno/en/'.$page)->assertOk();
        }
        $this->get('/strasalerno/iscrizione/half')->assertSee('name="participants[0][membership_type]"', false)
            ->assertSee('name="participants[0][medical_certificate]"', false)
            ->assertSee('Aggiungi un partecipante');
        $this->get('/strasalerno/en/iscrizione/half')->assertSee('/strasalerno/en/privacy');
        $this->get('/strasalerno/iscrizione/run')->assertSee('data-competitive-fields', false)
            ->assertSee('name="participants[0][race]"', false);
        $this->get('/strasalerno/assets/site.css')->assertOk()->assertHeader('Content-Type', 'text/css; charset=utf-8');
        $this->get('/strasalerno/assets/images/coastline.svg')->assertOk()->assertHeader('Content-Type', 'image/svg+xml');
        $this->get('/strasalerno/gare/unknown')->assertNotFound();
    }

    public function test_each_distance_uses_its_own_requirements_and_test_price(): void
    {
        Storage::fake('local');
        $gateway = Mockery::mock(DemoStripeGateway::class);
        $gateway->shouldReceive('paymentIntent')->times(3)->andReturnUsing(function ($order) {
            $order->update(['stripe_payment_intent_id' => 'pi_test_'.$order->id]);
            return (object) ['id' => 'pi_test_'.$order->id, 'status' => 'requires_payment_method'];
        });
        app()->instance(DemoStripeGateway::class, $gateway);

        $base = [
            'locale' => 'it', 'payment_method' => 'stripe', 'privacy_consent' => 1,
            'first_name' => 'Demo', 'last_name' => 'Runner', 'email' => 'demo@example.test',
            'dob' => '1990-01-01', 'city' => 'Salerno',
        ];
        $this->post('/strasalerno/iscrizione/half', $base)->assertSessionHasErrors('membership_type');
        $this->post('/strasalerno/iscrizione/half', $base + ['membership_type' => 'runcard', 'membership_number' => 'TEST'])
            ->assertSessionHasErrors(['medical_certificate', 'medical_certificate_expires_on']);
        $this->post('/strasalerno/iscrizione/half', array_replace($base, [
            'dob' => now()->subYears(19)->toDateString(), 'membership_type' => 'runcard', 'membership_number' => 'TEST',
            'medical_certificate_expires_on' => now()->addYear()->toDateString(),
            'medical_certificate' => UploadedFile::fake()->create('test.pdf', 20, 'application/pdf'),
            'medical_certificate_attestation' => 1,
        ]))->assertSessionHasErrors('dob');

        $this->post('/strasalerno/iscrizione/half', $base + [
            'membership_type' => 'fidal', 'membership_number' => 'FIDAL-DEMO',
        ])->assertRedirect();
        $this->post('/strasalerno/iscrizione/10k', $base + [
            'membership_type' => 'eps', 'membership_number' => 'EPS-DEMO',
            'medical_certificate_expires_on' => now()->addYear()->toDateString(),
            'medical_certificate' => UploadedFile::fake()->create('test.pdf', 20, 'application/pdf'),
            'medical_certificate_attestation' => 1,
        ])->assertRedirect();
        $this->post('/strasalerno/iscrizione/run', $base)->assertRedirect();

        app(CurrentDemo::class)->set('strasalerno');
        $orders = Order::orderBy('id')->get();
        $this->assertSame([25.0, 15.0, 10.0], $orders->pluck('total_amount')->map(fn ($v) => (float) $v)->all());
        $this->assertSame('pending_review', $orders[0]->tickets->first()->eligibility_status);
        $this->assertNull($orders[0]->tickets->first()->medical_certificate_path);
        Storage::disk('local')->assertExists($orders[1]->tickets->first()->medical_certificate_path);
        app(DemoPaymentFinalizer::class)->finalize($orders[2]);
        $code = $orders[2]->tickets->first()->fresh()->unique_ticket_code;
        $this->get('/strasalerno/ticket/'.$code)->assertOk();
        $this->get('/cro-trail/ticket/'.$code)->assertNotFound();
    }

    public function test_mixed_group_has_one_discounted_order_and_individual_tickets(): void
    {
        Storage::fake('local');
        app(CurrentDemo::class)->set('strasalerno');
        DemoDiscountCode::create(['code' => 'GROUP2', 'amount' => 2]);
        $gateway = Mockery::mock(DemoStripeGateway::class);
        $gateway->shouldReceive('paymentIntent')->once()->andReturnUsing(function ($order) {
            $order->update(['stripe_payment_intent_id' => 'pi_test_group']);
            return (object) ['id' => 'pi_test_group', 'status' => 'requires_payment_method'];
        });
        app()->instance(DemoStripeGateway::class, $gateway);

        $base = ['locale' => 'it', 'payment_method' => 'stripe', 'privacy_consent' => 1, 'discount_code' => 'GROUP2'];
        $this->post('/cro-trail/iscrizione/21/gruppo', $base)->assertNotFound();
        $people = [
            ['race' => 'half', 'first_name' => 'Ada', 'last_name' => 'Demo', 'email' => 'ada@example.test',
                'dob' => '1990-01-01', 'city' => 'Salerno', 'membership_type' => 'fidal', 'membership_number' => 'F-TEST',
                'price' => '0.50'],
            ['race' => '10k', 'first_name' => 'Bea', 'last_name' => 'Demo', 'email' => 'bea@example.test',
                'dob' => '1990-01-01', 'city' => 'Salerno', 'membership_type' => 'eps', 'membership_number' => 'E-TEST',
                'medical_certificate_expires_on' => now()->addYear()->toDateString(),
                'medical_certificate_attestation' => 1],
            ['race' => 'run', 'first_name' => 'Ciro', 'last_name' => 'Demo', 'email' => 'ciro@example.test',
                'dob' => '1990-01-01', 'city' => 'Salerno'],
        ];
        $this->post('/strasalerno/iscrizione/half/gruppo', $base + ['participants' => $people])
            ->assertSessionHasErrors('participants.1.medical_certificate');
        $this->post('/strasalerno/iscrizione/half/gruppo', $base + ['participants' => array_fill(0, 11, $people[2])])
            ->assertSessionHasErrors('participants');
        $this->assertDatabaseCount('orders', 0);

        $people[1]['medical_certificate'] = UploadedFile::fake()->create('test.pdf', 20, 'application/pdf');
        $response = $this->post('/strasalerno/iscrizione/half/gruppo', $base + ['participants' => $people])->assertRedirect();
        $this->assertStringContainsString('/strasalerno/checkout/', $response->headers->get('Location'));
        app(CurrentDemo::class)->set('strasalerno');
        $order = Order::with('tickets')->firstOrFail();
        $this->assertNull($order->race_slug);
        $this->assertSame(44.0, (float) $order->total_amount);
        $this->assertCount(3, $order->tickets);
        $this->assertSame(['half', '10k', 'run'], $order->tickets->pluck('race_slug')->all());
        $this->assertSame([23.0, 13.0, 8.0], $order->tickets->pluck('price_paid')->map(fn ($v) => (float) $v)->all());
        $this->assertSame(['pending_review', 'pending_review', 'not_required'], $order->tickets->pluck('eligibility_status')->all());
        Storage::disk('local')->assertExists($order->tickets[1]->medical_certificate_path);

        app(DemoPaymentFinalizer::class)->finalize($order);
        $run = $order->tickets[2]->fresh();
        $this->assertSame('active', $run->status);
        $this->assertNotNull($run->unique_ticket_code);
        $this->get('/strasalerno/checkout/'.$order->group_code.'/success')->assertOk()
            ->assertSee('Ada Demo')->assertSee('Bea Demo')->assertSee('Ciro Demo');
        $this->get('/cro-trail/checkout/'.$order->group_code.'/payment')->assertNotFound();
        $this->get('/cro-trail/ticket/'.$run->unique_ticket_code)->assertNotFound();
    }
}
