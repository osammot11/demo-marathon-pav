<?php

namespace App\Services;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class DemoRegistry
{
    public function all(): array
    {
        $demos = [];
        foreach (glob(base_path('demos/*/manifest.php')) ?: [] as $file) {
            $slug = basename(dirname($file));
            if ($this->isValidSlug($slug)) {
                $demos[$slug] = $this->find($slug);
            }
        }

        return $demos;
    }

    public function find(string $slug): array
    {
        abort_unless($this->isValidSlug($slug), 404);
        $root = realpath(base_path('demos'));
        $directory = realpath(base_path('demos/'.$slug));
        abort_unless($root && $directory && str_starts_with($directory, $root.DIRECTORY_SEPARATOR), 404);
        $file = $directory.'/manifest.php';
        abort_unless(is_file($file), 404);
        $manifest = require $file;
        abort_unless(is_array($manifest) && isset($manifest['name'], $manifest['races'], $manifest['registration'])
            && is_array($manifest['races']) && is_array($manifest['registration'])
            && is_array($manifest['pages'] ?? []), 404);
        foreach (array_keys($manifest['races']) as $race) {
            abort_unless((bool) preg_match('/^[a-z0-9-]+$/', (string) $race), 404);
        }
        foreach ($manifest['pages'] ?? [] as $page => $view) {
            abort_unless((bool) preg_match('/^[a-z0-9-]+$/', (string) $page)
                && is_string($view) && (bool) preg_match('/^[a-z0-9-]+$/', $view), 404);
        }

        $manifest['slug'] = $slug;
        $manifest['directory'] = $directory;
        $manifest['view_namespace'] = 'demo-'.$slug;
        $manifest['translation_key'] = $manifest['view_namespace'].'::messages.';
        View::addNamespace($manifest['view_namespace'], $directory.'/views');
        Lang::addNamespace($manifest['view_namespace'], $directory.'/lang');

        return $manifest;
    }

    public function race(array $demo, string $race): array
    {
        abort_unless(isset($demo['races'][$race]), 404);
        $translated = __($demo['translation_key'].'races.'.$race);
        abort_unless(is_array($translated), 404);

        return array_merge($translated, ['slug' => $race, 'distance' => $translated['distance'] ?? $race]);
    }

    public function price(array $demo, string $race): float
    {
        $key = 'price.'.$race;
        $override = \App\Models\DemoSetting::query()
            ->where('demo_slug', $demo['slug'])->where('key', $key)->value('value');

        return $override !== null ? (float) $override : (float) $demo['races'][$race]['price'];
    }

    public function isOpen(array $demo, string $race): bool
    {
        return isset($demo['races'][$race])
            && (bool) data_get($demo, 'registration.open', false)
            && $this->testStripeReady();
    }

    public function testStripeReady(): bool
    {
        return Str::startsWith((string) config('services.stripe.key'), 'pk_test_')
            && Str::startsWith((string) config('services.stripe.secret'), 'sk_test_');
    }

    public function assetUrl(array $demo, string $path): string
    {
        $file = $demo['directory'].'/assets/'.$path;
        $version = is_file($file) ? substr(sha1_file($file), 0, 12).'-2' : '2';

        return route('demo.asset', ['demo' => $demo['slug'], 'path' => $path, 'v' => $version]);
    }

    public function isValidSlug(string $slug): bool
    {
        return strlen($slug) <= 63 && (bool) preg_match('/^[a-z0-9-]+$/', $slug)
            && (bool) preg_match('/[a-z0-9]/', $slug)
            && ! in_array($slug, ['admin', 'webhook', 'presentation', 'proposta-commerciale', 'up', 'assets', 'en'], true);
    }
}
