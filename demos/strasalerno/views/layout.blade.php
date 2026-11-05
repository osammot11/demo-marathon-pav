<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __($translationKey.'meta_description') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#142932">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.css') }}">
    <script src="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.js') }}" defer></script>
</head>
<body>
<a class="skip-link" href="#main">{{ $locale === 'it' ? 'Vai al contenuto' : 'Skip to content' }}</a>
<div class="demo-strip">{{ __($translationKey.'test_mode_label') }}</div>
<header class="site-header">
    <div class="header-inner wrap">
        <a class="wordmark" href="{{ $homeUrl }}" aria-label="StraSalerno · Home"><span class="mark"><svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="24" cy="24" r="21" stroke="currentColor" stroke-width="2"/><path d="M2 27c8-6 13-6 21 0s13 6 23 0M2 33c8-6 13-6 21 0s13 6 23 0" stroke="currentColor" stroke-width="2"/></svg></span><span>STRA<span>SALERNO</span><small>HALF MARATHON & MORE</small></span></a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="{{ __($translationKey.'nav_menu') }}" data-menu-toggle><span></span><span></span></button>
        <nav id="primary-nav" class="nav" aria-label="{{ $locale === 'it' ? 'Navigazione principale' : 'Main navigation' }}" data-menu>
            <a href="{{ $homeUrl }}#distanze">{{ __($translationKey.'nav_races') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'percorsi']) }}">{{ __($translationKey.'nav_routes') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'programma']) }}">{{ __($translationKey.'nav_schedule') }}</a>
            <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'contatti']) }}">{{ __($translationKey.'nav_contact') }}</a>
            <a class="nav-button" href="{{ $homeUrl }}#distanze">{{ __($translationKey.'nav_register') }} <span>↗</span></a>
            <a class="language" href="{{ $switchUrl }}" lang="{{ $locale === 'it' ? 'en' : 'it' }}">{{ $locale === 'it' ? 'EN' : 'IT' }}</a>
        </nav>
    </div>
</header>
@yield('content')
<footer class="footer">
    <div class="wrap footer-top">
        <div><a class="footer-brand" href="{{ $homeUrl }}">STRA<span>SALERNO</span></a><p>ASD Atletica Vis Nova Salerno<br>{{ __($translationKey.'contact_location') }}</p></div>
        <div class="footer-links"><strong>{{ __($translationKey.'nav_races') }}</strong>@foreach($races as $slug => $item)<a href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $slug]) }}">{{ $item['name'] }}</a>@endforeach</div>
        <div class="footer-links"><strong>{{ __($translationKey.'nav_contact') }}</strong><a href="mailto:info@visnovasalerno.it">info@visnovasalerno.it</a><a href="tel:+393296170449">+39 329 617 0449</a><a href="https://www.visnovasalerno.it/strasalerno/" target="_blank" rel="noopener">visnovasalerno.it ↗</a></div>
    </div>
    <div class="wrap footer-bottom"><span>© {{ date('Y') }} ASD Atletica Vis Nova Salerno · {{ __($translationKey.'edition_note') }}</span><a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'privacy']) }}">{{ __($translationKey.'footer_privacy') }}</a></div>
</footer>
</body>
</html>
