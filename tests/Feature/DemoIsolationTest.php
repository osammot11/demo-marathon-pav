<?php

namespace Tests\Feature;

use App\Models\DemoDiscountCode;
use App\Models\DemoSetting;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use App\Services\CurrentDemo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DemoIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_non_admin_cannot_open_shared_panel(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin?demo=cro-trail')->assertForbidden();
    }

    public function test_global_scopes_hide_other_demo_rows_and_force_slug_on_create(): void
    {
        app(CurrentDemo::class)->set('cro-trail');
        [$order, $ticket] = $this->makeTicket('CRO_ONLY', 'TKT_CRO_ONLY');
        $this->assertSame('cro-trail', $order->demo_slug);
        $this->assertSame('cro-trail', $ticket->demo_slug);

        DemoSetting::create(['demo_slug' => 'coast-run', 'key' => 'price.42', 'value' => '45']);
        DemoDiscountCode::create(['demo_slug' => 'coast-run', 'code' => 'CRO10', 'amount' => 10]);
        $this->assertDatabaseHas('demo_settings', ['demo_slug' => 'cro-trail', 'key' => 'price.42']);
        $this->assertDatabaseHas('demo_discount_codes', ['demo_slug' => 'cro-trail', 'code' => 'CRO10']);

        app(CurrentDemo::class)->set('coast-run');
        $this->assertNull(Order::find($order->id));
        $this->assertNull(Ticket::find($ticket->id));
        $this->assertSame(0, DemoSetting::count());
        $this->assertSame(0, DemoDiscountCode::count());
    }

    public function test_order_ticket_admin_and_export_urls_cannot_cross_demo_boundaries(): void
    {
        app(CurrentDemo::class)->set('cro-trail');
        [$order, $ticket] = $this->makeTicket('CRO_PRIVATE', 'TKT_PRIVATE');
        app(CurrentDemo::class)->set('coast-run');
        $this->makeTicket('COAST_VISIBLE', 'TKT_COAST');

        $this->get('/coast-run/checkout/'.$order->group_code.'/success')->assertNotFound();
        $this->get('/coast-run/ticket/'.$ticket->unique_ticket_code)->assertNotFound();

        $this->get('/admin?demo=coast-run')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create(['is_demo_admin' => true]));
        $this->get('/admin?demo=coast-run')->assertOk()->assertSee('COAST_VISIBLE')->assertDontSee('CRO_PRIVATE')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/admin/tickets/'.$ticket->id.'?demo=coast-run')->assertNotFound();
        $this->patch('/admin/tickets/'.$ticket->id, [
            'demo' => 'coast-run', 'first_name' => 'Changed', 'last_name' => 'Runner', 'email' => 'changed@example.test',
        ])->assertNotFound();
        $this->post('/admin/tickets/'.$ticket->id.'/approve', ['demo' => 'coast-run'])->assertNotFound();
        $this->get('/admin/tickets/'.$ticket->id.'/document?demo=coast-run')->assertNotFound();
        $csv = $this->get('/admin/tickets/export?demo=coast-run')->assertOk()->streamedContent();
        $this->assertStringContainsString('COAST_VISIBLE', $csv);
        $this->assertStringNotContainsString('CRO_PRIVATE', $csv);

        app(CurrentDemo::class)->set('cro-trail');
        $this->assertSame('Alex', $ticket->fresh()->first_name);
    }

    public function test_invalid_or_unknown_slug_and_asset_traversal_are_rejected(): void
    {
        $this->get('/not-a-demo')->assertNotFound();
        $this->get('/cro-trail/unknown-page')->assertNotFound();
        $this->get('/coast-run/assets/%2e%2e%2fmanifest.php')->assertNotFound();
        $this->get('/coast-run/assets/manifest.php')->assertNotFound();
        $asset = $this->get('/coast-run/assets/site.css')->assertOk();
        $this->assertStringContainsString('max-age=3600', $asset->headers->get('Cache-Control'));
        $etag = $asset->headers->get('ETag');
        $this->assertNotEmpty($etag);
        $this->withHeader('If-None-Match', $etag)->get('/coast-run/assets/site.css')->assertStatus(304);
    }

    public function test_admin_prices_discounts_and_csv_import_remain_with_selected_demo(): void
    {
        $this->actingAs(User::factory()->create(['is_demo_admin' => true]));
        $this->post('/admin/prices', ['demo' => 'coast-run', 'race' => '42', 'price' => '41.50'])->assertRedirect();
        $this->post('/admin/discounts', ['demo' => 'coast-run', 'code' => 'COAST5', 'amount' => '5.00'])->assertRedirect();
        $this->post('/admin/imports', [
            'demo' => 'coast-run',
            'csv_file' => UploadedFile::fake()->createWithContent('group.csv', "first_name,last_name,email,race\nSam,Walker,sam@example.test,42\n"),
        ])->assertRedirect();

        $this->get('/admin?demo=coast-run')->assertOk()->assertSee('Sam Walker')->assertSee('COAST5')->assertSee('41.5');
        $this->get('/admin?demo=cro-trail')->assertOk()->assertDontSee('Sam Walker')->assertDontSee('COAST5');
        app(CurrentDemo::class)->set('coast-run');
        $this->assertSame(1, DemoSetting::count());
        $this->assertSame(1, DemoDiscountCode::count());
        $this->assertSame(1, Ticket::count());
        app(CurrentDemo::class)->set('cro-trail');
        $this->assertSame(0, DemoSetting::count());
        $this->assertSame(0, DemoDiscountCode::count());
        $this->assertSame(0, Ticket::count());
    }

    private function makeTicket(string $orderCode, string $ticketCode): array
    {
        $order = Order::create([
            'demo_slug' => 'wrong', 'group_code' => $orderCode, 'race_slug' => '42',
            'total_amount' => 39, 'payment_method' => 'stripe', 'status' => 'paid', 'locale' => 'it',
        ]);
        $ticket = $order->tickets()->create([
            'demo_slug' => 'wrong', 'first_name' => 'Alex', 'last_name' => 'Runner', 'email' => 'alex@example.test',
            'route_choice' => '42 km', 'dob' => '1990-01-01', 'birth_place' => 'N/A',
            'residence_address' => 'N/A', 'city' => 'N/A', 'zip_code' => 'N/A',
            'province' => 'N/A', 'region' => 'N/A', 'country' => 'N/A', 'nationality' => 'IT',
            'phone' => 'N/A', 'tshirt_size' => 'N/A', 'payment_tag' => 'Test',
            'price_paid' => 39, 'status' => 'active', 'unique_ticket_code' => $ticketCode,
        ]);
        return [$order, $ticket];
    }
}
