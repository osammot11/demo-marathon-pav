<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __($translationKey.'meta_description') }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }}</title>
    <link rel="alternate" hreflang="it" href="{{ $locale === 'it' ? url()->current() : $switchUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $locale === 'en' ? url()->current() : $switchUrl }}">
    <link rel="icon" type="image/svg+xml" href="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.css') }}">
    <script src="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.js') }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#content">{{ __($translationKey.'nav_main') }}</a>
    <header class="site-header">
        <a class="wordmark" href="{{ $homeUrl }}" aria-label="CroTrail, homepage"><span class="mark-mountain">⌁</span><span>CRO<span class="wordmark-accent">TRAIL</span><small>RAID CRO-MAGNON</small></span></a>
        <nav class="desktop-nav" aria-label="{{ __($translationKey.'nav_main') }}">
            <a href="{{ $homeUrl }}#gare">{{ __($translationKey.'nav_races') }}</a><a href="{{ $homeUrl }}#esperienza">{{ __($translationKey.'nav_experience') }}</a><a href="{{ $homeUrl }}#informazioni">{{ __($translationKey.'nav_info') }}</a>
        </nav>
        <div class="header-actions"><div class="language-switch" aria-label="{{ __($translationKey.'language_label') }}"><a href="{{ $locale === 'it' ? url()->current() : $switchUrl }}" lang="it" hreflang="it" @if($locale === 'it') aria-current="page" @endif>IT</a><span>/</span><a href="{{ $locale === 'en' ? url()->current() : $switchUrl }}" lang="en" hreflang="en" @if($locale === 'en') aria-current="page" @endif>EN</a></div><a class="button button-small" href="{{ $homeUrl }}#gare">{{ __($translationKey.'nav_choose') }} <span>↗</span></a></div>
        <button class="menu-toggle" type="button" aria-label="{{ __($translationKey.'menu_open') }}" aria-expanded="false" aria-controls="mobile-nav" data-open-label="{{ __($translationKey.'menu_open') }}" data-close-label="{{ __($translationKey.'menu_close') }}">☰</button>
    </header>
    @if(true)<div class="test-mode-banner">{{ __($translationKey.'test_mode_label') }}</div>@endif
    <nav id="mobile-nav" class="mobile-nav" aria-label="{{ __($translationKey.'nav_main') }}" hidden><a href="{{ $homeUrl }}#gare">{{ __($translationKey.'nav_races') }}</a><a href="{{ $homeUrl }}#esperienza">{{ __($translationKey.'nav_experience') }}</a><a href="{{ $homeUrl }}#informazioni">{{ __($translationKey.'nav_info') }}</a></nav>
    @yield('content')
    <footer class="site-footer">
        <div><a class="wordmark footer-mark" href="{{ $homeUrl }}"><span class="mark-mountain">⌁</span><span>CRO<span class="wordmark-accent">TRAIL</span><small>RAID CRO-MAGNON</small></span></a><p>{{ __($translationKey.'footer_tagline') }}</p></div>
        <div><strong>{{ __($translationKey.'footer_explore') }}</strong><a href="{{ $homeUrl }}#gare">{{ __($translationKey.'nav_races') }}</a><a href="{{ $homeUrl }}#informazioni">{{ __($translationKey.'footer_info') }}</a><a href="{{ $homeUrl }}#faq">FAQ</a><a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'contatti']) }}">{{ __($translationKey.'contact_kicker') }}</a></div>
        <div><strong>{{ __($translationKey.'footer_edition') }}</strong><p>{{ __($translationKey.'footer_edition_text') }}</p><a href="{{ $demo['registration']['privacy_url'] }}" target="_blank" rel="noopener">{{ __($translationKey.'footer_privacy') }} ↗</a><a href="https://www.crotrail.it/informazioni-sui-cookies/" target="_blank" rel="noopener">{{ __($translationKey.'footer_cookies') }} ↗</a></div>
        <div class="footer-bottom">© {{ date('Y') }} {{ __($translationKey.'footer_copy') }}</div>
    </footer>
</body>
</html>
