@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main">
    <section class="hero">
        <div class="hero-landscape" aria-hidden="true"></div>
        <div class="hero-content shell">
            <p class="eyebrow hero-eyebrow"><span class="small-line"></span>{{ __($translationKey.'hero_kicker') }}</p>
            <h1><span>{{ __($translationKey.'hero_title_1') }}</span><em>{{ __($translationKey.'hero_title_2') }}</em></h1>
            <p class="hero-lead">{{ __($translationKey.'hero_lead') }}</p>
            <div class="hero-actions">
                <a class="button button-primary" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => '44']) }}">{{ __($translationKey.'hero_route') }} <span aria-hidden="true">↗</span></a>
                <a class="text-button" href="{{ route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => '44']) }}">{{ __($translationKey.'hero_register') }} <span aria-hidden="true">→</span></a>
            </div>
        </div>
        <div class="hero-bottom shell"><span>{{ __($translationKey.'edition_note') }}</span><a href="#sfida">{{ __($translationKey.'hero_scroll') }} <span aria-hidden="true">↓</span></a></div>
    </section>

    <section class="stats-band" aria-label="{{ $locale === 'it' ? 'Dati del percorso 2026' : '2026 route facts' }}">
        <div class="shell stats-grid"><div><strong>44<small>KM</small></strong><span>{{ $locale === 'it' ? 'Distanza' : 'Distance' }}</span></div><div><strong>3.560<small>M+</small></strong><span>{{ $locale === 'it' ? 'Dislivello' : 'Elevation gain' }}</span></div><div><strong>12<small>H</small></strong><span>{{ $locale === 'it' ? 'Tempo massimo 2026' : '2026 time limit' }}</span></div><div class="stats-route"><span>SORA <b>→</b> VEROLI</span><small>LAZIO · APPENNINO</small></div></div>
    </section>

    <section id="sfida" class="route-section section-pad">
        <div class="shell section-heading"><p class="eyebrow">{{ __($translationKey.'route_kicker') }}</p><h2>{{ __($translationKey.'route_title') }}</h2><p>{{ __($translationKey.'route_intro') }}</p></div>
        <div class="shell route-visual"><div class="route-visual-art" aria-hidden="true"><span class="route-label route-label-start">SORA</span><span class="route-label route-label-finish">VEROLI</span></div><div class="route-visual-copy"><span class="overline">44K · 3.560 M+</span><h3>{{ __($translationKey.'route_terrain') }}</h3><p>{{ __($translationKey.'route_terrain_text') }}</p><a class="arrow-link" href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'percorso']) }}">{{ __($translationKey.'route_page_link') }} <span aria-hidden="true">↗</span></a></div></div>
        <div class="shell stage-strip">@foreach(__($translationKey.'stages') as $stage)<div><span>{{ $stage['km'] }}</span><strong>{{ $stage['name'] }}</strong><small>{{ $stage['detail'] }}</small></div>@endforeach</div>
    </section>

    <section class="journey-section section-pad">
        <div class="shell"><div class="journey-heading"><div><p class="eyebrow">{{ __($translationKey.'journey_kicker') }}</p><h2>{{ __($translationKey.'journey_title') }}</h2></div><p>{{ __($translationKey.'journey_intro') }}</p></div>
            <div class="journey-grid">@foreach(__($translationKey.'journey_steps') as $step)<article class="journey-card"><span>{{ $step['number'] }}</span><h3>{{ $step['title'] }}</h3><p>{{ $step['text'] }}</p></article>@endforeach</div>
        </div>
    </section>

    <section class="weekend-section section-pad">
        <div class="shell weekend-layout"><div class="weekend-heading"><p class="eyebrow">{{ __($translationKey.'weekend_kicker') }}</p><h2>{{ __($translationKey.'weekend_title') }}</h2><p>{{ __($translationKey.'weekend_note') }}</p><a class="arrow-link" href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'logistica']) }}">{{ __($translationKey.'nav_logistics') }} <span aria-hidden="true">↗</span></a></div><div class="timeline">@foreach(__($translationKey.'weekend_items') as $item)<div class="timeline-item"><span>{{ $item['time'] }}</span><div><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></div></div>@endforeach</div></div>
    </section>

    <section class="closing-section"><div class="shell closing-inner"><p class="eyebrow">SORA → VEROLI</p><h2>{{ __($translationKey.'closing_title') }}</h2><p>{{ __($translationKey.'closing_text') }}</p><a class="button button-light" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => '44']) }}">{{ __($translationKey.'closing_button') }} <span aria-hidden="true">↗</span></a></div></section>
</main>
@endsection
