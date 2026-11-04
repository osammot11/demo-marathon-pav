<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Maratona Alzheimer' }}</title>
    <meta name="description" content="{{ $description ?? 'Maratona Alzheimer: sport e solidarietà per i diritti delle persone con demenza.' }}">
    @isset($canonical)
        <link rel="canonical" href="{{ $canonical }}">
    @endisset
    <link rel="icon" type="image/png" href="/images/maratona-alzheimer/logo.png">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'Maratona Alzheimer' }}">
    <meta property="og:description" content="{{ $description ?? 'Maratona Alzheimer: sport e solidarietà per i diritti delle persone con demenza.' }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:image" content="{{ url('/images/maratona-alzheimer/hero.jpg') }}">
    <meta property="og:locale" content="it_IT">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Maratona Alzheimer' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Maratona Alzheimer: sport e solidarietà per i diritti delle persone con demenza.' }}">
    <meta name="twitter:image" content="{{ url('/images/maratona-alzheimer/hero.jpg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;500;600;700;800;900&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/style.css?v=70">
    <link rel="stylesheet" href="/iscritti-home.css?v=1.3">
    @stack('head')
</head>

<body class="alzheimer-theme {{ $bodyClass ?? '' }}">
    <div class="site-header">        
        @include('partials.navbar')
    </div>

    @yield('content')

    @include('partials.footer')
    @include('partials.cookie-banner')

    @stack('scripts')
</body>
</html>
