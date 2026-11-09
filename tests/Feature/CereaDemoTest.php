<?php
namespace Tests\Feature;
use App\Models\Order;
use App\Services\CurrentDemo;
use App\Services\DemoStripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
class CereaDemoTest extends TestCase
{
 use RefreshDatabase;
 public function test_cerea_pages_assets_and_test_registration_are_isolated(): void
 {
  config()->set('services.stripe.key','pk_test_example');
  config()->set('services.stripe.secret','sk_test_example');
  $this->get('/cerea-night-run')->assertOk()->assertSee('La città si accende')->assertHeader('X-Robots-Tag','noindex, nofollow');
  $this->get('/cerea-night-run/en')->assertOk()->assertSee('The city lights up');
  foreach (['programma','contatti','privacy','gare/5','iscrizione/5'] as $path) {
   $this->get('/cerea-night-run/'.$path)->assertOk();
   $this->get('/cerea-night-run/en/'.$path)->assertOk();
  }
  $this->get('/cerea-night-run/assets/site.css')->assertOk()->assertHeader('Content-Type','text/css; charset=utf-8');
  $this->get('/cerea-night-run/assets/locandina-2026.jpg')->assertOk();
  $this->get('/cerea-night-run/gare/10')->assertNotFound();
  $data=['locale'=>'it','payment_method'=>'stripe','privacy_consent'=>1,'first_name'=>'Demo','last_name'=>'Cerea','email'=>'cerea@example.test','dob'=>'1990-01-01','price'=>1];
  $gateway=Mockery::mock(DemoStripeGateway::class);
  $gateway->shouldReceive('paymentIntent')->once()->andReturn((object)['id'=>'pi_cerea','status'=>'requires_payment_method']);
  app()->instance(DemoStripeGateway::class,$gateway);
  $this->post('/cerea-night-run/iscrizione/5',array_replace($data,['dob'=>now()->subYears(10)->toDateString()]))->assertSessionHasErrors('dob');
  $this->post('/cerea-night-run/iscrizione/5',$data)->assertRedirect();
  app(CurrentDemo::class)->set('cerea-night-run');
  $order=Order::firstOrFail();
  $this->assertSame(15.0,(float)$order->total_amount);
  $this->assertSame('cerea-night-run',$order->tickets->first()->demo_slug);
  $this->get('/coast-run/checkout/'.$order->group_code.'/payment')->assertNotFound();
 }
}
