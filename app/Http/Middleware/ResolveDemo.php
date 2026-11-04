<?php

namespace App\Http\Middleware;

use App\Services\DemoRegistry;
use App\Services\CurrentDemo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveDemo
{
    public function __construct(private DemoRegistry $registry, private CurrentDemo $current) {}

    public function handle(Request $request, Closure $next): Response
    {
        $demo = $this->registry->find((string) $request->route('demo'));
        $request->attributes->set('demo', $demo);
        $this->current->set($demo['slug']);
        app()->setLocale($request->segment(2) === 'en' && in_array('en', $demo['locales'], true) ? 'en' : 'it');

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
