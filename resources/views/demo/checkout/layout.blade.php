<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') | {{ $demo['name'] }}</title>
    <link rel="stylesheet" href="{{ app(\App\Services\DemoRegistry::class)->assetUrl($demo, 'site.css') }}">
    @yield('head')
</head>
<body class="checkout-page">
    <header class="site-header"><a class="wordmark" href="{{ route(app()->getLocale() === 'en' ? 'demo.en.home' : 'demo.home', ['demo' => $demo['slug']]) }}">{{ $demo['name'] }}</a></header>
    <div class="test-mode-banner">{{ __($demo['translation_key'].'test_mode_label') }}</div>
    <main class="checkout-main container">@yield('content')</main>
</body>
</html>
