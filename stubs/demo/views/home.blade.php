@extends($demoViewNamespace.'::layout')
@section('content')
<main id="content">
    <section class="hero"><div class="sun"></div><div class="container hero-content">
        <p class="eyebrow">{{ __($translationKey.'hero_kicker') }}</p>
        <h1>{{ __($translationKey.'hero_title') }}</h1>
        <p>{{ __($translationKey.'hero_text') }}</p>
        <a class="button" href="#gare">{{ __($translationKey.'races_title') }} ↗</a>
    </div><div class="wave"></div></section>
    <section class="container races-section" id="gare"><p class="eyebrow">01 / RACES</p><h2>{{ __($translationKey.'races_title') }}</h2>
        <div class="race-grid">@foreach($races as $race)<a class="race-card" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $race['distance']]) }}"><span>{{ $race['eyebrow'] }}</span><strong>{{ $race['distance'] }}<small>KM</small></strong><h3>{{ $race['name'] }}</h3><p>{{ $race['line'] }}</p><b>{{ __($translationKey.'race_link') }} ↗</b></a>@endforeach</div>
    </section>
</main>
@endsection
