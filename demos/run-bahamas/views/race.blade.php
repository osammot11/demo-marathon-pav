@extends($demoViewNamespace.'::layout')

@section('content')
@php
    $registry = app(\App\Services\DemoRegistry::class);
    $t = fn ($key) => __($translationKey.$key);
    $money = fn ($v) => '€ '.($locale === 'en' ? number_format($v, 2, '.', ',') : number_format($v, 2, ',', '.'));
    $img = fn ($f) => $registry->assetUrl($demo, 'images/'.$f);
    $regUrl = route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => $race['slug']]);
    $isVirtual = str_starts_with($race['slug'], 'virtual-');
@endphp
<main id="content">
    <section class="race-hero" style="--hero-image:url('{{ $img('goodmans-bay.jpg') }}')">
        <div class="container">
            <a class="back-link" href="{{ $homeUrl }}#races">← {{ $t('back_races') }}</a>
            <p class="eyebrow">{{ $race['eyebrow'] }}</p>
            <h1>{{ $race['name'] }}</h1>
            <p class="hero-lead">{{ $race['line'] }}</p>
            <div class="hero-actions"><a class="button" href="{{ $regUrl }}">{{ $registrationOpen ? $t('race_enter') : $t('race_registration') }} →</a></div>
        </div>
    </section>
    <div class="race-facts"><div class="container race-facts-inner">
        <div><span>{{ $t('race_distance') }}</span><strong>{{ $race['distance'] }} {{ $t('miles') }} <small>/ {{ $race['km'] }} {{ $t('km') }}</small></strong></div>
        <div><span>{{ $t('race_when') }}</span><strong>{{ $t('race_when_value') }}</strong></div>
        <div><span>{{ $t('race_start') }}</span><strong>{{ $race['start'] }}</strong></div>
        <div><span>{{ $t('race_fee_label') }}</span><strong>{{ $money($registrationPrice) }}@if($race['slug'] === 'relay') <small>{{ $t('per_runner') }}</small>@endif</strong></div>
    </div></div>

    <section class="section container race-detail">
        <div>
            <p class="eyebrow dark">{{ $t('race_details') }}</p>
            <h2>{{ $race['line'] }}</h2>
            <p class="detail-lead">{{ $race['description'] }}</p>
            @if($race['slug'] === 'relay')<p class="callout">{{ $t('relay_note') }}</p>@endif
            <ul class="tick-list">@foreach($race['facts'] as $fact)<li>{{ $fact }}</li>@endforeach</ul>
            <a class="button" href="{{ $regUrl }}">{{ $registrationOpen ? $t('race_enter') : $t('race_registration') }} →</a>
        </div>
        <aside class="detail-panel">
            <p class="eyebrow dark">{{ $t('race_get_ready') }}</p>
            <p>{{ $t('race_ready_text') }}</p>
            <div class="registration-fact"><span>{{ $t('race_place') }}</span><strong>{{ $race['place'] }}</strong></div>
            <div class="registration-fact"><span>{{ $t('race_status') }}</span><strong>{{ $registrationOpen ? $t('race_status_open') : $t('race_status_soon') }}</strong></div>
            <img src="{{ $img('goodmans-bay.jpg') }}" width="930" height="620" loading="lazy" alt="Goodman’s Bay">
        </aside>
    </section>

    <section class="section-light"><div class="container">
        <p class="eyebrow dark">{{ $t('other_kicker') }}</p>
        <h2>{{ $t('other_title') }}</h2>
        <div class="other-race-grid">
            @foreach($races as $slug => $other)
                @if($slug !== $race['slug'])
                    <a href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $slug]) }}"><span>{{ $other['distance'] }} {{ $t('miles') }}</span><strong>{{ $other['name'] }}</strong><b>→</b></a>
                @endif
            @endforeach
        </div>
    </div></section>
</main>
@endsection
