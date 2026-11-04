<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ __($translationKey.'meta_description') }}">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.css') }}">
</head>
<body>
    <header class="site-header"><a class="wordmark" href="{{ $homeUrl }}">{{ $demo['name'] }}<small>DEMO</small></a>
        <nav><a href="{{ $homeUrl }}#gare">{{ __($translationKey.'races_title') }}</a><a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'contatti']) }}">{{ __($translationKey.'contact_title') }}</a></nav>
        <div class="language-switch"><a href="{{ $locale === 'it' ? url()->current() : $switchUrl }}">IT</a> / <a href="{{ $locale === 'en' ? url()->current() : $switchUrl }}">EN</a></div>
    </header>
    <div class="test-mode-banner">{{ __($translationKey.'test_mode_label') }}</div>
    @yield('content')
    <footer><a href="{{ $homeUrl }}">{{ $demo['name'] }}</a><span>Fictional event · platform demonstration</span></footer>
</body>
</html>
