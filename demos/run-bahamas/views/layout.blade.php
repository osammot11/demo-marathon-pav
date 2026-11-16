@php($registry = app(\App\Services\DemoRegistry::class))
@php($t = fn ($key) => __($translationKey.$key))
@php($pageUrl = fn ($page) => route($routePrefix.'page', ['demo' => $demoSlug, 'page' => $page]))
@php($firstRace = array_key_first($races))
<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $t('meta_description') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0b1d24">
    <title>@yield('title', $title)</title>
    <link rel="alternate" hreflang="it" href="{{ $locale === 'it' ? url()->current() : $switchUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $locale === 'en' ? url()->current() : $switchUrl }}">
    <link rel="icon" type="image/svg+xml" href="{{ $registry->assetUrl($demo, 'favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,600;0,700;0,800;1,700;1,800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $registry->assetUrl($demo, 'site.css') }}">
    <script src="{{ $registry->assetUrl($demo, 'site.js') }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#content">{{ $t('nav_main') }}</a>
    <header class="site-header">
        <a class="brand" href="{{ $homeUrl }}" aria-label="Run Bahamas 2027"><img src="{{ $registry->assetUrl($demo, 'images/logo.png') }}" width="640" height="186" alt="Run Bahamas"></a>
        <nav class="desktop-nav" aria-label="{{ $t('nav_main') }}">
            <a href="{{ $homeUrl }}#races">{{ $t('nav_races') }}</a>
            <a href="{{ $homeUrl }}#weekend">{{ $t('nav_weekend') }}</a>
            <a href="{{ $pageUrl('travel') }}">{{ $t('nav_travel') }}</a>
            <a href="{{ $pageUrl('giving-back') }}">{{ $t('nav_giving') }}</a>
            <a href="{{ $homeUrl }}#faq">{{ $t('nav_faq') }}</a>
        </nav>
        <div class="header-actions">
            <div class="language-switch" aria-label="{{ $t('language_label') }}">
                <a href="{{ $locale === 'it' ? url()->current() : $switchUrl }}" lang="it" hreflang="it" @if($locale === 'it') aria-current="page" @endif>IT</a><span>/</span><a href="{{ $locale === 'en' ? url()->current() : $switchUrl }}" lang="en" hreflang="en" @if($locale === 'en') aria-current="page" @endif>EN</a>
            </div>
            <a class="button button-small" href="{{ $homeUrl }}#races">{{ $t('nav_register') }}</a>
            <button class="menu-toggle" type="button" aria-label="{{ $t('menu_open') }}" aria-expanded="false" aria-controls="mobile-nav" data-open-label="{{ $t('menu_open') }}" data-close-label="{{ $t('menu_close') }}"><span></span><span></span><span></span></button>
        </div>
    </header>
    <div class="test-mode-banner">{{ $t('test_mode_label') }}</div>
    <nav id="mobile-nav" class="mobile-nav" aria-label="{{ $t('nav_main') }}" hidden>
        <a href="{{ $homeUrl }}#races">{{ $t('nav_races') }}</a>
        <a href="{{ $homeUrl }}#weekend">{{ $t('nav_weekend') }}</a>
        <a href="{{ $pageUrl('travel') }}">{{ $t('nav_travel') }}</a>
        <a href="{{ $pageUrl('giving-back') }}">{{ $t('nav_giving') }}</a>
        <a href="{{ $homeUrl }}#faq">{{ $t('nav_faq') }}</a>
        <a href="{{ $pageUrl('contatti') }}">{{ $t('nav_contact') }}</a>
    </nav>
    @yield('content')
    <footer class="site-footer">
        <div class="footer-brand"><img src="{{ $registry->assetUrl($demo, 'images/logo.png') }}" width="320" height="93" alt="Run Bahamas" loading="lazy"><p>{{ $t('footer_tagline') }}</p></div>
        <div><strong>{{ $t('footer_explore') }}</strong><a href="{{ $homeUrl }}#races">{{ $t('nav_races') }}</a><a href="{{ $pageUrl('travel') }}">{{ $t('nav_travel') }}</a><a href="{{ $pageUrl('giving-back') }}">{{ $t('nav_giving') }}</a><a href="{{ $pageUrl('contatti') }}">{{ $t('nav_contact') }}</a><a href="{{ $pageUrl('privacy') }}">{{ $t('footer_privacy') }}</a><a href="{{ $pageUrl('notes') }}">{{ $t('notes_h1') }}</a></div>
        <div><strong>{{ $t('footer_event') }}</strong><p>{{ $t('footer_event_text') }}</p><a href="https://runbahamas.net/" target="_blank" rel="noopener">{{ $t('footer_official') }} ↗</a></div>
        <div class="footer-bottom">{{ $t('footer_demo') }}</div>
    </footer>
</body>
</html>
