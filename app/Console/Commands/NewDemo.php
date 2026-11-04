<?php

namespace App\Console\Commands;

use App\Services\DemoRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class NewDemo extends Command
{
    protected $signature = 'demo:new {slug : Lowercase URL segment for the demo}';
    protected $description = 'Create a functional demo skeleton under demos/{slug}';

    public function handle(DemoRegistry $registry): int
    {
        $slug = (string) $this->argument('slug');
        if (! $registry->isValidSlug($slug)) {
            $this->error('Invalid slug. Use only lowercase letters, numbers and hyphens (max 63 characters).');
            return self::FAILURE;
        }
        $destination = base_path('demos/'.$slug);
        if (File::exists($destination)) {
            $this->error('A demo with this slug already exists.');
            return self::FAILURE;
        }
        $source = base_path('stubs/demo');
        if (! File::isDirectory($source) || ! File::copyDirectory($source, $destination)) {
            $this->error('Could not copy the demo skeleton.');
            return self::FAILURE;
        }

        $name = Str::headline($slug);
        $manifest = $destination.'/manifest.php';
        File::put($manifest, str_replace(
            ['__DEMO_NAME__', '__DEMO_SLUG__'], [$name.' / Demo', $slug], File::get($manifest),
        ));
        $this->info('Created demos/'.$slug.' with sample races, prices, pages, translations and CSS.');
        $this->line('Customize the content and styling, then git push. On the VPS: git pull && ./deploy.sh');
        return self::SUCCESS;
    }
}
