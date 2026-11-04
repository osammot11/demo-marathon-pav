<?php

namespace App\Services;

use LogicException;

class CurrentDemo
{
    private ?string $consoleSlug = null;

    public function set(string $slug): void
    {
        if (app()->bound('request')) {
            request()->attributes->set('current_demo_slug', $slug);
        } else {
            $this->consoleSlug = $slug;
        }
    }

    public function slug(): string
    {
        $slug = app()->bound('request') ? request()->attributes->get('current_demo_slug') : $this->consoleSlug;
        if (! is_string($slug) || $slug === '') {
            throw new LogicException('A demo context is required for demo data.');
        }

        return $slug;
    }
}
