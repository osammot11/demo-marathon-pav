@extends($demoViewNamespace.'::layout')
@section('content')
@php
$competitive = $race['kind'] === 'competitive';
$requirements = $competitive ? __($translationKey.'race_requirements_comp') : ($race['slug'] === 'family' ? __($translationKey.'race_requirements_family') : __($translationKey.'race_requirements_fun'));
@endphp
<main id="main">
    <section class="subhero"><div class="wrap subhero-grid"><div><p class="eyebrow light">{{ $race['eyebrow'] }}</p><h1>{{ $race['name'] }}</h1><p>{{ $race['description'] }}</p><a class="button coral" href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => $race['slug']]) }}">{{ __($translationKey.'race_register') }} <span>↗</span></a></div><div class="big-distance">{{ $race['distance'] }}<small>KM</small></div></div></section>
    <section class="section"><div class="wrap race-detail-grid"><div><p class="eyebrow">{{ __($translationKey.'race_route') }}</p><h2>{{ $race['line'] }}</h2><p>{{ $race['description'] }}</p><a class="text-link" href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'percorsi']) }}">{{ __($translationKey.'nav_routes') }} <span>↗</span></a></div><aside class="info-card"><span class="eyebrow">{{ __($translationKey.'race_requirements') }}</span><p>{{ $requirements }}</p><div class="info-price"><span>{{ __($translationKey.'race_test_price') }}</span><strong>€ {{ number_format($registrationPrice, 2, ',', '.') }}</strong></div><a class="button dark" href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => $race['slug']]) }}">{{ __($translationKey.'register') }} <span>↗</span></a></aside></div><div class="wrap"><p class="source-note">{{ __($translationKey.'race_detail_note') }}</p></div></section>
    <section class="other-races section"><div class="wrap"><p class="eyebrow">{{ __($translationKey.'races_overline') }}</p><h2>{{ __($translationKey.'races_title') }}</h2><div class="other-race-grid">@foreach($races as $slug => $item)<a href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $slug]) }}"><span>{{ $item['distance'] }} KM</span><strong>{{ $item['name'] }}</strong><b>↗</b></a>@endforeach</div></div></section>
</main>
@endsection
