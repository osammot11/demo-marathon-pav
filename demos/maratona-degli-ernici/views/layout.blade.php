<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __($translationKey.'meta_description') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#10231f">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.css') }}">
    <script src="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.js') }}" defer></script>
</head>
<body>
<a class="skip-link" href="#main">{{ $locale === 'it' ? 'Vai al contenuto' : 'Skip to content' }}</a>
<div class="demo-bar"><span class="demo-dot"></span>{{ __($translationKey.'test_mode_label') }}</div>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="{{ $homeUrl }}" aria-label="Maratona degli Ernici · Home">
            <span class="brand-symbol" aria-hidden="true"><svg viewBox="0 0 42 42" fill="none"><path d="M3 33 15 10l7 13 5-9 12 19H3Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M9 33h26" stroke="currentColor" stroke-width="2.5"/></svg></span>
            <span class="brand-text">MARATONA <strong>DEGLI ERNICI</strong><small>SORA · VEROLI</small></span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="{{ __($translationKey.'nav_menu') }}" data-menu-toggle><span></span><span></span></button>
        <nav id="primary-nav" class="primary-nav" aria-label="{{ $locale === 'it' ? 'Navigazione principale' : 'Main navigation' }}" data-menu>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'percorso']) }}">{{ __($translationKey.'nav_route') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'logistica']) }}">{{ __($translationKey.'nav_logistics') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'contatti']) }}">{{ __($translationKey.'nav_contact') }}</a>
            <a class="nav-cta" href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => '44']) }}">{{ __($translationKey.'nav_register') }} <span aria-hidden="true">↗</span></a>
            <a class="language-link" href="{{ $switchUrl }}" lang="{{ $locale === 'it' ? 'en' : 'it' }}">{{ $locale === 'it' ? 'EN' : 'IT' }}</a>
        </nav>
    </div>
</header>
@yield('content')
<footer class="site-footer">
    <div class="footer-top">
        <div><a class="footer-brand" href="{{ $homeUrl }}">MARATONA<br>DEGLI ERNICI</a><p>ASD Ernica Veroli · {{ __($translationKey.'contact_location') }}</p></div>
        <div><span class="footer-heading">{{ $locale === 'it' ? 'Esplora' : 'Explore' }}</span><a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'percorso']) }}">{{ __($translationKey.'nav_route') }}</a><a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'logistica']) }}">{{ __($translationKey.'nav_logistics') }}</a><a href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => '44']) }}">{{ __($translationKey.'nav_register') }}</a></div>
        <div><span class="footer-heading">{{ $locale === 'it' ? 'Contatti' : 'Contact' }}</span><a href="mailto:ernicaveroli@gmail.com">ernicaveroli@gmail.com</a><a href="tel:+393665934640">+39 366 593 4640</a><a href="https://www.ernica.it/" target="_blank" rel="noopener">ernica.it ↗</a></div>
    </div>
    <div class="footer-bottom"><span>© {{ date('Y') }} ASD Ernica Veroli · {{ __($translationKey.'edition_note') }}</span><a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'privacy']) }}">{{ __($translationKey.'footer_privacy') }}</a></div>
</footer>
</body>
</html>
