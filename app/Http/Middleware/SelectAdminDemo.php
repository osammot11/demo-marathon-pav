<?php

namespace App\Http\Middleware;

use App\Services\CurrentDemo;
use App\Services\DemoRegistry;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SelectAdminDemo
{
    public function __construct(private DemoRegistry $registry, private CurrentDemo $current) {}

    public function handle(Request $request, Closure $next): Response
    {
        $all = $this->registry->all();
        abort_if($all === [], 404);
        $slug = $request->input('demo', isset($all['cro-trail']) ? 'cro-trail' : array_key_first($all));
        abort_unless(is_string($slug) && isset($all[$slug]), 404);
        $this->current->set($slug);
        $request->attributes->set('demo', $all[$slug]);

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        return $response;
    }
}
