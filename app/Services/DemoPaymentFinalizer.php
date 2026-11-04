<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DemoPaymentFinalizer
{
    public function finalize(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $locked = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($locked->status === 'paid') return;

            $locked->update(['status' => 'paid']);
            foreach ($locked->tickets as $ticket) {
                $review = $ticket->eligibility_status === 'pending_review';
                $ticket->update([
                    'status' => $review ? 'pending_review' : 'active',
                    'unique_ticket_code' => $review ? null : 'TKT_'.strtoupper(Str::random(16)),
                ]);
                Log::info('Demo registration updated; outbound email disabled', [
                    'demo' => $locked->demo_slug, 'ticket_id' => $ticket->id, 'status' => $ticket->status,
                ]);
            }
        });
    }
}
