<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Services\CroTrailRegistration;

class CroTrailController extends Controller
{
    private function pageData(string $page, ?string $distance = null): array
    {
        $locale = request()->is('en', 'en/*') ? 'en' : 'it';
        app()->setLocale($locale);
        $races = __('crotrail.races');
        $routePrefix = $locale === 'en' ? 'en.' : '';
        $otherPrefix = $locale === 'en' ? '' : 'en.';

        return [
            'locale' => $locale,
            'races' => $races,
            'race' => $distance === null ? null : $races[$distance],
            'routePrefix' => $routePrefix,
            'homeUrl' => route($routePrefix.'home'),
            'switchUrl' => route($otherPrefix.$page, $distance),
            'registrationOpen' => $distance !== null && app(CroTrailRegistration::class)->isOpen($distance),
            'registrationPrice' => $distance === null ? null : app(CroTrailRegistration::class)->price($distance),
            'nonMemberSurcharge' => app(CroTrailRegistration::class)->nonMemberSurcharge(),
            'paymentMethods' => app(CroTrailRegistration::class)->methods(),
            'title' => $page === 'home' ? __('crotrail.title_home') :
                ($page === 'race' ? $races[$distance]['name'].' | CroTrail 2027' :
                __('crotrail.title_registration', ['distance' => $distance])),
        ];
    }

    public function home(): View
    {
        return view('crotrail.home', $this->pageData('home'));
    }

    public function race(string $distance): View
    {
        return view('crotrail.race', $this->pageData('race', $distance));
    }

    public function registration(string $distance): View
    {
        return view('crotrail.registration', $this->pageData('registration', $distance));
    }
}
