@extends($demoViewNamespace.'::layout')
@section('content')
<main class="container detail-page"><a class="back-link" href="{{ $homeUrl }}#gare">← {{ __($translationKey.'races_title') }}</a>
    <p class="eyebrow">{{ $race['eyebrow'] }}</p><h1>{{ $race['name'] }}</h1><p class="lead">{{ $race['line'] }}</p><p>{{ $race['description'] }}</p>
    <div class="registration-fact"><span>TEST PRICE</span><strong>€ {{ number_format($registrationPrice, 2, ',', '.') }}</strong></div>
    <a class="button" href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => $race['slug']]) }}">{{ __($translationKey.'register') }} ↗</a>
</main>
@endsection
