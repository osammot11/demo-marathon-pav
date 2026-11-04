<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __('crotrail.meta_description') }}">
    @if(! app()->environment('production'))<meta name="robots" content="noindex, nofollow">@endif
    <title>{{ $title }}</title>
    <link rel="alternate" hreflang="it" href="{{ $locale === 'it' ? url()->current() : $switchUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $locale === 'en' ? url()->current() : $switchUrl }}">
    <link rel="icon" type="image/svg+xml" href="/crotrail-favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/crotrail.css?v=5">
    <script src="/crotrail.js?v=2" defer></script>
</head>
<body>
    <a class="skip-link" href="#content">{{ __('crotrail.nav_main') }}</a>
    <header class="site-header">
        <a class="wordmark" href="{{ $homeUrl }}" aria-label="CroTrail, homepage"><span class="mark-mountain">⌁</span><span>CRO<span class="wordmark-accent">TRAIL</span><small>RAID CRO-MAGNON</small></span></a>
        <nav class="desktop-nav" aria-label="{{ __('crotrail.nav_main') }}">
            <a href="{{ $homeUrl }}#gare">{{ __('crotrail.nav_races') }}</a><a href="{{ $homeUrl }}#esperienza">{{ __('crotrail.nav_experience') }}</a><a href="{{ $homeUrl }}#informazioni">{{ __('crotrail.nav_info') }}</a>
        </nav>
        <div class="header-actions"><div class="language-switch" aria-label="{{ __('crotrail.language_label') }}"><a href="{{ $locale === 'it' ? url()->current() : $switchUrl }}" lang="it" hreflang="it" @if($locale === 'it') aria-current="page" @endif>IT</a><span>/</span><a href="{{ $locale === 'en' ? url()->current() : $switchUrl }}" lang="en" hreflang="en" @if($locale === 'en') aria-current="page" @endif>EN</a></div><a class="button button-small" href="{{ $homeUrl }}#gare">{{ __('crotrail.nav_choose') }} <span>↗</span></a></div>
        <button class="menu-toggle" type="button" aria-label="{{ __('crotrail.menu_open') }}" aria-expanded="false" aria-controls="mobile-nav" data-open-label="{{ __('crotrail.menu_open') }}" data-close-label="{{ __('crotrail.menu_close') }}">☰</button>
    </header>
    @if(config('crotrail.test_mode'))<div class="test-mode-banner">{{ __('crotrail.test_mode_label') }}</div>@endif
    <nav id="mobile-nav" class="mobile-nav" aria-label="{{ __('crotrail.nav_main') }}" hidden><a href="{{ $homeUrl }}#gare">{{ __('crotrail.nav_races') }}</a><a href="{{ $homeUrl }}#esperienza">{{ __('crotrail.nav_experience') }}</a><a href="{{ $homeUrl }}#informazioni">{{ __('crotrail.nav_info') }}</a></nav>
    @yield('content')
    <footer class="site-footer">
        <div><a class="wordmark footer-mark" href="{{ $homeUrl }}"><span class="mark-mountain">⌁</span><span>CRO<span class="wordmark-accent">TRAIL</span><small>RAID CRO-MAGNON</small></span></a><p>{{ __('crotrail.footer_tagline') }}</p></div>
        <div><strong>{{ __('crotrail.footer_explore') }}</strong><a href="{{ $homeUrl }}#gare">{{ __('crotrail.nav_races') }}</a><a href="{{ $homeUrl }}#informazioni">{{ __('crotrail.footer_info') }}</a><a href="{{ $homeUrl }}#faq">FAQ</a></div>
        <div><strong>{{ __('crotrail.footer_edition') }}</strong><p>{{ __('crotrail.footer_edition_text') }}</p><a href="https://www.crotrail.it/normative-sulla-privacy/" target="_blank" rel="noopener">{{ __('crotrail.footer_privacy') }} ↗</a><a href="https://www.crotrail.it/informazioni-sui-cookies/" target="_blank" rel="noopener">{{ __('crotrail.footer_cookies') }} ↗</a></div>
        <div class="footer-bottom">© {{ date('Y') }} {{ __('crotrail.footer_copy') }}</div>
    </footer>
</body>
</html>
