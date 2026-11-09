<?php
namespace Tests\Feature;
use App\Models\Order;
use App\Services\CurrentDemo;
use App\Services\DemoStripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;
class SharedGroupRegistrationTest extends TestCase
{
 use RefreshDatabase;
 public function test_all_demos_enable_groups_and_cerea_creates_two_tickets_in_one_order(): void
 {
  config()->set('services.stripe.key','pk_test_example');config()->set('services.stripe.secret','sk_test_example');
  foreach(['cerea-night-run'=>'5','cro-trail'=>'21','coast-run'=>'42','maratona-degli-ernici'=>'44','strasalerno'=>'half'] as $demo=>$race){
   $this->get("/$demo/iscrizione/$race")->assertOk()->assertSee('data-demo-group',false)->assertSee('Aggiungi un partecipante');
   $this->get("/$demo/en/iscrizione/$race")->assertOk()->assertSee('Add a participant');
  }
  $gateway=Mockery::mock(DemoStripeGateway::class);$gateway->shouldReceive('paymentIntent')->once()->andReturn((object)[]);app()->instance(DemoStripeGateway::class,$gateway);
  $person=['race'=>'5','first_name'=>'Demo','last_name'=>'Runner','email'=>'demo@example.test','dob'=>'1990-01-01'];
  $this->post('/cerea-night-run/iscrizione/5/gruppo',['locale'=>'en','privacy_consent'=>1,'payment_method'=>'stripe','participants'=>[$person,$person],'total'=>1])->assertRedirect();
  app(CurrentDemo::class)->set('cerea-night-run');$order=Order::firstOrFail();$this->assertSame(30.0,(float)$order->total_amount);$this->assertCount(2,$order->tickets);$this->assertSame('5',$order->race_slug);
  $this->get('/coast-run/checkout/'.$order->group_code.'/payment')->assertNotFound();
 }
 public function test_groups_preserve_demo_specific_fields_documents_and_shuttle(): void
 {
  config()->set('services.stripe.key','pk_test_example');config()->set('services.stripe.secret','sk_test_example');Storage::fake('local');
  $gateway=Mockery::mock(DemoStripeGateway::class);$gateway->shouldReceive('paymentIntent')->twice()->andReturn((object)[]);app()->instance(DemoStripeGateway::class,$gateway);
  $person=['race'=>'21','first_name'=>'Demo','last_name'=>'Runner','email'=>'demo@example.test','dob'=>'1990-01-01','birth_place'=>'Cerea','residence_address'=>'Via Demo','city'=>'Cerea','zip_code'=>'37053','province'=>'VR','region'=>'Veneto','country'=>'Italia','nationality'=>'it','phone'=>'123456789','membership_type'=>'none','medical_certificate_expires_on'=>now()->addYear()->toDateString(),'medical_certificate_attestation'=>1,'medical_certificate'=>UploadedFile::fake()->create('cert.pdf',10,'application/pdf')];
  $base=['locale'=>'it','privacy_consent'=>1,'payment_method'=>'stripe'];
  $this->post('/cro-trail/iscrizione/21/gruppo',$base+['participants'=>[$person,$person]])->assertRedirect()->assertSessionHasNoErrors();
  app(CurrentDemo::class)->set('cro-trail');$order=Order::firstOrFail();$this->assertSame(76.0,(float)$order->total_amount);$this->assertCount(2,$order->tickets);$ticket=$order->tickets->first();$this->assertSame('Via Demo',$ticket->residence_address);$this->assertSame('IT',$ticket->nationality);$this->assertNull($ticket->membership_number);Storage::disk('local')->assertExists($ticket->medical_certificate_path);
  $person['race']='44';$person['shuttle_needed']=1;
  $this->post('/maratona-degli-ernici/iscrizione/44/gruppo',$base+['participants'=>[$person,array_replace($person,['shuttle_needed'=>0])]])->assertRedirect()->assertSessionHasNoErrors();
  app(CurrentDemo::class)->set('maratona-degli-ernici');$tickets=Order::firstOrFail()->tickets;$this->assertTrue((bool)$tickets[0]->shuttle_needed);$this->assertFalse((bool)$tickets[1]->shuttle_needed);
 }
}
