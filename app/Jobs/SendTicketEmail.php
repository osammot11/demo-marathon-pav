<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTicketEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Ticket $ticket) {}

    public function handle(): void
    {
        // No external email client is instantiated in this demo application.
        Log::info('Demo ticket email suppressed', [
            'ticket_id' => $this->ticket->id,
            'outbound_email_enabled' => config('demo_platform.outbound_email_enabled'),
        ]);
    }
}
