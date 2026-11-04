<?php

namespace App\Services;

use App\Jobs\SendTicketEmail;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderPaymentFinalizer
{
    public function finalize(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($lockedOrder->status === 'paid') {
                return;
            }

            $lockedOrder->update(['status' => 'paid']);
            foreach ($lockedOrder->tickets as $ticket) {
                $requiresDocumentReview = str_starts_with($lockedOrder->group_code, 'CRO_')
                    && $ticket->eligibility_status === 'pending_review';
                $ticket->update([
                    'status' => $requiresDocumentReview ? 'pending_review' : 'active',
                    'unique_ticket_code' => $requiresDocumentReview
                        ? null
                        : ($ticket->unique_ticket_code ?? 'TKT_' . strtoupper(Str::random(16))),
                ]);
                SendTicketEmail::dispatch($ticket)->afterCommit();
            }
        });
    }
}
