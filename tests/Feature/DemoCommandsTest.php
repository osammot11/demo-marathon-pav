<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Services\CurrentDemo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_demo_rejects_malicious_slug(): void
    {
        $this->artisan('demo:new', ['slug' => '../outside'])->assertFailed();
        $this->artisan('demo:new', ['slug' => 'UPPERCASE'])->assertFailed();
    }

    public function test_new_demo_is_detected_without_registering_a_new_route(): void
    {
        $slug = 'third-demo-test-'.bin2hex(random_bytes(4));
        $directory = base_path('demos/'.$slug);
        try {
            $this->artisan('demo:new', ['slug' => $slug])->assertSuccessful();
            $this->assertFileExists($directory.'/manifest.php');
            $this->get('/'.$slug)->assertOk()->assertSee('Third Demo Test');
            $this->get('/'.$slug.'/en/gare/42')->assertOk();
            $this->get('/'.$slug.'/privacy')->assertOk();
            $this->get('/'.$slug.'/assets/site.css')->assertOk();
        } finally {
            File::deleteDirectory($directory);
        }
    }

    public function test_cleanup_removes_expired_orders_and_private_documents_from_each_demo(): void
    {
        Storage::fake('local');
        app(CurrentDemo::class)->set('cro-trail');
        $old = $this->makeOrder('OLD_CRO');
        $path = 'demo-documents/cro-trail/test.pdf';
        Storage::disk('local')->put($path, 'test');
        $old->tickets()->create($this->ticketData() + ['medical_certificate_path' => $path]);
        $old->forceFill(['created_at' => now()->subDays(40)])->save();
        $recent = $this->makeOrder('RECENT_CRO');

        app(CurrentDemo::class)->set('coast-run');
        $coast = $this->makeOrder('OLD_COAST');
        $coast->forceFill(['created_at' => now()->subDays(40)])->save();

        $this->artisan('demo:cleanup', ['--days' => 30])->assertSuccessful();
        Storage::disk('local')->assertMissing($path);
        app(CurrentDemo::class)->set('cro-trail');
        $this->assertNull(Order::find($old->id));
        $this->assertNotNull(Order::find($recent->id));
        app(CurrentDemo::class)->set('coast-run');
        $this->assertNull(Order::find($coast->id));
    }

    private function makeOrder(string $code): Order
    {
        return Order::create([
            'group_code' => $code, 'race_slug' => '42', 'total_amount' => 0,
            'payment_method' => 'manual_demo', 'status' => 'paid', 'locale' => 'it',
        ]);
    }

    private function ticketData(): array
    {
        return [
            'first_name' => 'Alex', 'last_name' => 'Runner', 'email' => 'alex@example.test',
            'route_choice' => '42 km', 'dob' => '1990-01-01', 'birth_place' => 'N/A',
            'residence_address' => 'N/A', 'city' => 'N/A', 'zip_code' => 'N/A',
            'province' => 'N/A', 'region' => 'N/A', 'country' => 'N/A', 'nationality' => 'IT',
            'phone' => 'N/A', 'tshirt_size' => 'N/A', 'payment_tag' => 'Test',
            'price_paid' => 0, 'status' => 'active',
        ];
    }
}
