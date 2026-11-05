<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DemoRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class DemoController extends Controller
{
    public function __construct(private DemoRegistry $registry) {}

    public function home(Request $request)
    {
        return $this->render($request, 'home');
    }

    public function race(Request $request, string $demo, string $race)
    {
        return $this->render($request, 'race', $race);
    }

    public function registration(Request $request, string $demo, string $race)
    {
        return $this->render($request, 'registration', $race);
    }

    public function page(Request $request, string $demo, string $page)
    {
        $manifest = $request->attributes->get('demo');
        abort_unless(isset($manifest['pages'][$page]), 404);

        return $this->render($request, 'pages.'.$manifest['pages'][$page], null, $page);
    }

    public function asset(Request $request, string $demo, string $path): Response
    {
        $manifest = $request->attributes->get('demo');
        abort_unless((bool) preg_match('/^[a-zA-Z0-9_\/-]+\.(css|js|svg|png|jpe?g|webp|avif|gif|ico|woff2?)$/', $path), 404);
        $root = realpath($manifest['directory'].'/assets');
        $file = realpath($manifest['directory'].'/assets/'.$path);
        abort_unless($root && $file && str_starts_with($file, $root.DIRECTORY_SEPARATOR) && is_file($file), 404);

        $mimeTypes = [
            'css' => 'text/css; charset=utf-8',
            'js' => 'text/javascript; charset=utf-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];
        $etag = '"'.sha1_file($file).'"';
        $headers = [
            'Cache-Control' => 'public, max-age=3600',
            'Content-Type' => $mimeTypes[strtolower(pathinfo($file, PATHINFO_EXTENSION))],
            'ETag' => $etag,
        ];
        if ($request->header('If-None-Match') === $etag) return response('', 304, $headers);

        return response()->file($file, $headers);
    }

    private function render(Request $request, string $view, ?string $raceSlug = null, ?string $pageSlug = null)
    {
        $demo = $request->attributes->get('demo');
        $locale = app()->getLocale();
        $prefix = $locale === 'en' ? 'demo.en.' : 'demo.';
        $otherPrefix = $locale === 'en' ? 'demo.' : 'demo.en.';
        $routeName = $raceSlug === null ? ($pageSlug === null ? 'home' : 'page') : $view;
        $params = ['demo' => $demo['slug']];
        if ($raceSlug !== null) $params['race'] = $raceSlug;
        if ($pageSlug !== null) $params['page'] = $pageSlug;
        $race = $raceSlug === null ? null : $this->registry->race($demo, $raceSlug);
        $registration = $raceSlug === null ? $demo['registration'] : $this->registry->registrationForRace($demo, $raceSlug);
        $races = __($demo['translation_key'].'races');
        $viewName = $demo['view_namespace'].'::'.$view;
        abort_unless(View::exists($viewName), 404);

        return view($viewName, [
            'demo' => $demo,
            'demoSlug' => $demo['slug'],
            'demoViewNamespace' => $demo['view_namespace'],
            'translationKey' => $demo['translation_key'],
            'locale' => $locale,
            'routePrefix' => $prefix,
            'homeUrl' => route($prefix.'home', ['demo' => $demo['slug']]),
            'switchUrl' => route($otherPrefix.$routeName, $params),
            'races' => $races,
            'race' => $race,
            'registrationOpen' => $raceSlug !== null && $this->registry->isOpen($demo, $raceSlug),
            'registrationPrice' => $raceSlug === null ? null : $this->registry->price($demo, $raceSlug),
            'registration' => $registration,
            'nonMemberSurcharge' => (float) data_get($registration, 'membership.non_member_surcharge', 0),
            'paymentMethods' => $this->registry->testStripeReady() ? ['stripe'] : [],
            'title' => $raceSlug === null ? $demo['name'] : $race['name'].' | '.$demo['name'],
        ]);
    }
}
