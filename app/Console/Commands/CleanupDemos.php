<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\CurrentDemo;
use App\Services\DemoRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupDemos extends Command
{
    protected $signature = 'demo:cleanup {--days= : Override retention period in days}';
    protected $description = 'Remove expired demo orders, tickets and their private documents';

    public function handle(DemoRegistry $registry, CurrentDemo $current): int
    {
        $configured = $this->option('days') ?? config('demo_platform.retention_days', 30);
        if (! filter_var($configured, FILTER_VALIDATE_INT) || (int) $configured < 1) {
            $this->error('Retention must be a positive number of days.');
            return self::FAILURE;
        }
        $cutoff = now()->subDays((int) $configured);
        $removed = 0;
        foreach ($registry->all() as $slug => $_) {
            $current->set($slug);
            Order::query()->where('created_at', '<', $cutoff)->chunkById(100, function ($orders) use ($slug, &$removed) {
                foreach ($orders as $order) {
                    $paths = $order->tickets->pluck('medical_certificate_path')
                        ->filter(fn ($path) => is_string($path) && str_starts_with($path, 'demo-documents/'.$slug.'/'))
                        ->all();
                    $order->delete();
                    if ($paths) Storage::disk('local')->delete($paths);
                    $removed++;
                }
            });
        }
        $this->info('Removed '.$removed.' expired demo orders and their tickets/documents.');
        return self::SUCCESS;
    }
}
