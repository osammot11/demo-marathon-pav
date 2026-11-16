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
    <link rel="stylesheet" href="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.css') }}">
    <script src="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.js') }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#content">{{ __($translationKey.'skip') }}</a>
    <header class="site-header">
        <a class="wordmark" href="{{ $homeUrl }}" aria-label="{{ $demo['name'] }}"><span class="wordmark-76" aria-hidden="true">76</span><span class="wordmark-text"><strong>{{ __($translationKey.'wordmark_top') }}</strong><small>{{ __($translationKey.'wordmark_sub') }}</small></span></a>
        <nav class="desktop-nav" aria-label="{{ __($translationKey.'nav_main') }}">
            <a href="{{ $homeUrl }}#gara">{{ __($translationKey.'nav_race') }}</a>
            <a href="{{ $homeUrl }}#programma">{{ __($translationKey.'nav_program') }}</a>
            <a href="{{ $homeUrl }}#requisiti">{{ __($translationKey.'nav_requirements') }}</a>
            <a href="{{ $homeUrl }}#faq">{{ __($translationKey.'nav_faq') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'contatti']) }}">{{ __($translationKey.'nav_contact') }}</a>
        </nav>
        <div class="header-actions">
            <div class="language-switch" aria-label="{{ __($translationKey.'language_label') }}"><a href="{{ $locale === 'it' ? url()->current() : $switchUrl }}" lang="it" hreflang="it" @if($locale === 'it') aria-current="page" @endif>IT</a><span>/</span><a href="{{ $locale === 'en' ? url()->current() : $switchUrl }}" lang="en" hreflang="en" @if($locale === 'en') aria-current="page" @endif>EN</a></div>
            <a class="button button-small" href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => '136']) }}">{{ __($translationKey.'nav_register') }} <span>↗</span></a>
        </div>
        <button class="menu-toggle" type="button" aria-label="{{ __($translationKey.'menu_open') }}" aria-expanded="false" aria-controls="mobile-nav" data-open-label="{{ __($translationKey.'menu_open') }}" data-close-label="{{ __($translationKey.'menu_close') }}">☰</button>
    </header>
    <div class="band-stripe" aria-hidden="true"></div>
    <div class="test-mode-banner">{{ __($translationKey.'test_mode_label') }}</div>
    <nav id="mobile-nav" class="mobile-nav" aria-label="{{ __($translationKey.'nav_main') }}" hidden>
        <a href="{{ $homeUrl }}#gara">{{ __($translationKey.'nav_race') }}</a>
        <a href="{{ $homeUrl }}#programma">{{ __($translationKey.'nav_program') }}</a>
        <a href="{{ $homeUrl }}#requisiti">{{ __($translationKey.'nav_requirements') }}</a>
        <a href="{{ $homeUrl }}#faq">{{ __($translationKey.'nav_faq') }}</a>
        <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'contatti']) }}">{{ __($translationKey.'nav_contact') }}</a>
        <a href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => '136']) }}">{{ __($translationKey.'nav_register') }} ↗</a>
    </nav>
    @yield('content')
    <footer class="site-footer">
        <div>
            <a class="wordmark footer-mark" href="{{ $homeUrl }}"><span class="wordmark-76" aria-hidden="true">76</span><span class="wordmark-text"><strong>{{ __($translationKey.'wordmark_top') }}</strong><small>{{ __($translationKey.'wordmark_sub') }}</small></span></a>
            <p>{{ __($translationKey.'footer_tagline') }}</p>
        </div>
        <div>
            <strong>{{ __($translationKey.'footer_explore') }}</strong>
            <a href="{{ $homeUrl }}#gara">{{ __($translationKey.'nav_race') }}</a>
            <a href="{{ $homeUrl }}#programma">{{ __($translationKey.'nav_program') }}</a>
            <a href="{{ $homeUrl }}#faq">{{ __($translationKey.'nav_faq') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'contatti']) }}">{{ __($translationKey.'nav_contact') }}</a>
        </div>
        <div>
            <strong>{{ __($translationKey.'footer_edition') }}</strong>
            <p>{{ __($translationKey.'footer_edition_text') }}</p>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'osservazioni']) }}">{{ __($translationKey.'footer_notes') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'privacy']) }}">{{ __($translationKey.'footer_privacy') }}</a>
        </div>
        <div class="footer-bottom">{{ __($translationKey.'footer_source') }}</div>
    </footer>
</body>
</html>
