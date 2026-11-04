<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    @if(! app()->environment('production'))<meta name="robots" content="noindex, nofollow">@endif
    <title>@yield('title') | CroTrail 2027</title>
    <link rel="icon" type="image/svg+xml" href="/crotrail-favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/crotrail.css?v=4">
    @yield('head')
</head>
<body class="checkout-page">
    <header class="site-header"><a class="wordmark" href="{{ app()->getLocale() === 'en' ? route('en.home') : route('home') }}"><span class="mark-mountain">⌁</span><span>CRO<span class="wordmark-accent">TRAIL</span><small>RAID CRO-MAGNON</small></span></a></header>
    @if(config('crotrail.test_mode'))<div class="test-mode-banner">{{ __('crotrail.test_mode_label') }}</div>@endif
    <main class="checkout-main container">@yield('content')</main>
</body>
</html>
