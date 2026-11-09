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

class ErniciDemoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.stripe.key', 'pk_test_example');
        config()->set('services.stripe.secret', 'sk_test_example');
    }

    public function test_ernici_pages_and_assets_are_available_in_both_languages(): void
    {
        $this->get('/maratona-degli-ernici')->assertOk()->assertSee('Da Sora a Veroli')
            ->assertSee('Prossima edizione · data da confermare')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/maratona-degli-ernici/en')->assertOk()->assertSee('From Sora to Veroli');
        $this->get('/maratona-degli-ernici/percorso')->assertOk()->assertSee('Pizzo Deta');
        $this->get('/maratona-degli-ernici/en/logistica')->assertOk()->assertSee('Shuttle to the start');
        $this->get('/maratona-degli-ernici/contatti')->assertOk()->assertSee('ernicaveroli@gmail.com');
        $this->get('/maratona-degli-ernici/iscrizione/44')->assertOk()
            ->assertSee('name="participants[0][shuttle_needed]"', false)->assertSee('name="participants[0][medical_certificate]"', false);
        $this->get('/maratona-degli-ernici/assets/site.css')->assertOk()
            ->assertHeader('Content-Type', 'text/css; charset=utf-8');
        $this->get('/maratona-degli-ernici/assets/images/ernici-ridge.svg')->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml');
        $this->get('/maratona-degli-ernici/gare/10')->assertNotFound();
    }

    public function test_registration_and_admin_track_documents_shuttle_and_bib_in_one_demo(): void
    {
        Storage::fake('local');
        $gateway = Mockery::mock(DemoStripeGateway::class);
        $gateway->shouldReceive('paymentIntent')->once()->andReturnUsing(function ($order) {
            $order->update(['stripe_payment_intent_id' => 'pi_test_ernici']);
            return (object) ['id' => 'pi_test_ernici', 'status' => 'requires_payment_method'];
        });
        app()->instance(DemoStripeGateway::class, $gateway);

        $data = [
            'locale' => 'it', 'payment_method' => 'stripe', 'privacy_consent' => 1,
            'first_name' => 'Demo', 'last_name' => 'Runner', 'email' => 'demo@example.test',
            'dob' => '1990-01-01', 'birth_place' => 'Veroli', 'city' => 'Veroli',
            'phone' => '0000000000', 'shuttle_needed' => '1',
            'medical_certificate_expires_on' => now()->addYear()->toDateString(),
            'medical_certificate' => UploadedFile::fake()->create('sample.pdf', 20, 'application/pdf'),
            'medical_certificate_attestation' => 1,
        ];
        $this->post('/maratona-degli-ernici/iscrizione/44', array_diff_key($data, ['shuttle_needed' => true]))
            ->assertSessionHasErrors('shuttle_needed');
        $this->post('/maratona-degli-ernici/iscrizione/44', array_replace($data, ['dob' => now()->subYears(17)->toDateString()]))
            ->assertSessionHasErrors('dob');
        $this->post('/maratona-degli-ernici/iscrizione/44', $data)->assertRedirect();

        app(CurrentDemo::class)->set('maratona-degli-ernici');
        $order = Order::firstOrFail();
        $ticket = $order->tickets->firstOrFail();
        $this->assertSame(50.0, (float) $order->total_amount);
        $this->assertTrue((bool) $ticket->shuttle_needed);
        Storage::disk('local')->assertExists($ticket->medical_certificate_path);
        app(DemoPaymentFinalizer::class)->finalize($order);
        $this->assertSame('pending_review', $ticket->fresh()->status);

        $this->actingAs(User::factory()->create(['is_demo_admin' => true]));
        $this->get('/admin?demo=maratona-degli-ernici')->assertOk()
            ->assertSee('Flusso operativo degli atleti')->assertSee('Demo Runner');
        $this->get('/admin/tickets/'.$ticket->id.'?demo=coast-run')->assertNotFound();
        $this->patch('/admin/tickets/'.$ticket->id, [
            'demo' => 'maratona-degli-ernici', 'first_name' => 'Demo', 'last_name' => 'Runner',
            'email' => 'demo@example.test', 'shuttle_needed' => '1',
            'shuttle_assignment' => 'Bus A · 05:00', 'shuttle_boarded' => '0',
            'bib_number' => '44', 'bib_collected' => '0',
        ])->assertRedirect();
        $this->post('/admin/tickets/'.$ticket->id.'/approve', ['demo' => 'maratona-degli-ernici'])->assertRedirect();
        $code = $ticket->fresh()->unique_ticket_code;
        $this->get('/maratona-degli-ernici/ticket/'.$code)->assertOk()
            ->assertSee('Bus A · 05:00')->assertSee('44')->assertSee('Da ritirare');
        $this->patch('/admin/tickets/'.$ticket->id, [
            'demo' => 'maratona-degli-ernici', 'first_name' => 'Demo', 'last_name' => 'Runner',
            'email' => 'demo@example.test', 'shuttle_needed' => '1',
            'shuttle_assignment' => 'Bus A · 05:00', 'shuttle_boarded' => '1',
            'bib_number' => '44', 'bib_collected' => '1',
        ])->assertRedirect();
        $this->assertNotNull($ticket->fresh()->shuttle_boarded_at);
        $this->assertNotNull($ticket->fresh()->bib_collected_at);
        $csv = $this->get('/admin/tickets/export?demo=maratona-degli-ernici')->assertOk()->streamedContent();
        $this->assertStringContainsString('shuttle_assignment', $csv);
        $this->assertStringContainsString('Bus A · 05:00', $csv);
        $this->get('/coast-run/ticket/'.$code)->assertNotFound();
    }
}
