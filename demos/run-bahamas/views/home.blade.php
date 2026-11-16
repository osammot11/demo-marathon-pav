@extends($demoViewNamespace.'::layout')

@section('content')
@php
    $registry = app(\App\Services\DemoRegistry::class);
    $t = fn ($key) => __($translationKey.$key);
    $pageUrl = fn ($page) => route($routePrefix.'page', ['demo' => $demoSlug, 'page' => $page]);
    $raceUrl = fn ($slug) => route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $slug]);
    $money = fn ($v) => '€ '.($locale === 'en' ? number_format($v, 2, '.', ',') : number_format($v, 2, ',', '.'));
    $img = fn ($f) => $registry->assetUrl($demo, 'images/'.$f);
    $inPerson = array_filter($races, fn ($r, $slug) => ! str_starts_with($slug, 'virtual-'), ARRAY_FILTER_USE_BOTH);
    $virtual = array_filter($races, fn ($r, $slug) => str_starts_with($slug, 'virtual-'), ARRAY_FILTER_USE_BOTH);
@endphp
<main id="content">
    <section class="hero" style="--hero-image:url('{{ $img('goodmans-bay.jpg') }}')">
        <div class="container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow">{{ $t('hero_kicker') }}</p>
                <h1><span>{{ $t('hero_h1_a') }}</span> <em>{{ $t('hero_h1_b') }}</em></h1>
                <p class="hero-lead">{{ $t('hero_lead') }}</p>
                <div class="hero-meta"><span>{{ $t('hero_when') }}</span><span>{{ $t('hero_where') }}</span></div>
                <div class="hero-actions">
                    <a class="button" href="#races">{{ $t('hero_cta') }} <span aria-hidden="true">→</span></a>
                    <a class="button button-ghost" href="#weekend">{{ $t('hero_cta2') }}</a>
                </div>
            </div>
            <aside class="countdown" data-countdown="2027-01-31T11:00:00Z" data-done="{{ $t('count_done') }}" aria-label="{{ $t('count_label') }}">
                <p>{{ $t('count_label') }}</p>
                <div class="countdown-grid">
                    <div><b data-unit="days">00</b><span>{{ $t('count_days') }}</span></div>
                    <div><b data-unit="hours">00</b><span>{{ $t('count_hours') }}</span></div>
                    <div><b data-unit="minutes">00</b><span>{{ $t('count_minutes') }}</span></div>
                    <div><b data-unit="seconds">00</b><span>{{ $t('count_seconds') }}</span></div>
                </div>
            </aside>
        </div>
    </section>

    <section class="stats-band" aria-label="{{ $t('races_title') }}">
        <div class="container stats-grid">
            @foreach($t('stats') as $stat)
                <div><strong>{{ $stat['n'] }}</strong><span>{{ $t('stats_unit') }}</span><small>{{ $stat['l'] }}</small></div>
            @endforeach
        </div>
    </section>

    <section class="section container" id="races">
        <p class="eyebrow dark">{{ $t('races_kicker') }}</p>
        <h2>{{ $t('races_title') }}</h2>
        <p class="section-lead">{{ $t('races_lead') }}</p>
        <h3 class="group-title">{{ $t('races_inperson') }}</h3>
        <div class="race-grid">
            @foreach($inPerson as $slug => $r)
                <a class="race-card" href="{{ $raceUrl($slug) }}">
                    <span class="race-eyebrow">{{ $r['eyebrow'] }}</span>
                    <strong class="race-distance">{{ $r['distance'] }}<small>{{ $t('stats_unit') }}</small></strong>
                    <h4>{{ $r['name'] }}</h4>
                    <p>{{ $r['line'] }}</p>
                    <dl><div><dt>{{ $t('race_start') }}</dt><dd>{{ $r['start'] }}</dd></div><div><dt>{{ $t('race_from') }}</dt><dd>{{ $money($registry->price($demo, $slug)) }}@if($slug === 'relay') <small>{{ $t('per_runner') }}</small>@endif</dd></div></dl>
                    <b>{{ $t('race_link') }} →</b>
                </a>
            @endforeach
        </div>
        <h3 class="group-title">{{ $t('races_virtual') }}</h3>
        <div class="race-grid race-grid-virtual">
            @foreach($virtual as $slug => $r)
                <a class="race-card race-card-virtual" href="{{ $raceUrl($slug) }}">
                    <span class="race-eyebrow">{{ $r['eyebrow'] }}</span>
                    <strong class="race-distance">{{ $r['distance'] }}<small>{{ $t('stats_unit') }}</small></strong>
                    <h4>{{ $r['name'] }}</h4>
                    <dl><div><dt>{{ $t('race_from') }}</dt><dd>{{ $money($registry->price($demo, $slug)) }}</dd></div></dl>
                    <b>{{ $t('race_link') }} →</b>
                </a>
            @endforeach
        </div>
    </section>

    <section class="section-dark" id="weekend">
        <div class="container">
            <p class="eyebrow">{{ $t('weekend_kicker') }}</p>
            <h2>{{ $t('weekend_title') }}</h2>
            <p class="section-lead">{{ $t('weekend_lead') }}</p>
            <ol class="timeline">
                @foreach($t('schedule') as $row)
                    <li><div class="tl-when"><span>{{ $row['d'] }}</span><b>{{ $row['t'] }}</b></div><div class="tl-what"><h3>{{ $row['h'] }}</h3><p>{{ $row['p'] }}</p></div></li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="section container course" id="course">
        <div class="course-copy">
            <p class="eyebrow dark">{{ $t('course_kicker') }}</p>
            <h2>{{ $t('course_title') }}</h2>
            <p class="section-lead">{{ $t('course_text') }}</p>
            <ul class="route-points">@foreach($t('course_points') as $point)<li>{{ $point }}</li>@endforeach</ul>
            <h3 class="group-title">{{ $t('support_title') }}</h3>
            <ul class="tick-list">@foreach($t('support') as $item)<li>{{ $item }}</li>@endforeach</ul>
        </div>
        <div class="course-photos">
            <figure><img src="{{ $img('goodmans-bay.jpg') }}" width="930" height="620" loading="lazy" alt="{{ $t('course_caption_a') }}"><figcaption>{{ $t('course_caption_a') }}</figcaption></figure>
            <figure><img src="{{ $img('sunset-course.jpg') }}" width="1200" height="676" loading="lazy" alt="{{ $t('course_caption_b') }}"><figcaption>{{ $t('course_caption_b') }}</figcaption></figure>
        </div>
    </section>

    <section class="split">
        <div class="split-card trip">
            <p class="eyebrow">{{ $t('trip_kicker') }}</p>
            <h2>{{ $t('trip_title') }}</h2>
            <p>{{ $t('trip_text') }}</p>
            <ul class="mini-list"><li>{{ $t('trip_card_a') }} · Warwick Paradise Island</li><li>{{ $t('trip_card_b') }} · Breezes, Baha Mar</li><li>{{ $t('trip_card_c') }} · RUNBAHS −10%</li></ul>
            <a class="button" href="{{ $pageUrl('travel') }}">{{ $t('trip_cta') }} →</a>
        </div>
        <div class="split-card giving">
            <p class="eyebrow">{{ $t('giving_kicker') }}</p>
            <h2>{{ $t('giving_title') }}</h2>
            <p>{{ $t('giving_text') }}</p>
            <a class="button button-ghost" href="{{ $pageUrl('giving-back') }}">{{ $t('giving_cta') }} →</a>
        </div>
    </section>

    <section class="section container organizers">
        <div class="organizers-poster"><img src="{{ $img('poster.jpg') }}" width="1100" height="1424" loading="lazy" alt="Run Bahamas — 31 January 2027, Goodman’s Bay, Nassau"></div>
        <div>
            <p class="eyebrow dark">{{ $t('org_kicker') }}</p>
            <h2>{{ $t('org_title') }}</h2>
            <p class="section-lead">{{ $t('org_text') }}</p>
            <a class="button" href="{{ $pageUrl('contatti') }}">{{ $t('nav_contact') }} →</a>
        </div>
    </section>

    <section class="section container faq" id="faq">
        <p class="eyebrow dark">{{ $t('faq_kicker') }}</p>
        <h2>{{ $t('faq_title') }}</h2>
        <div class="faq-list">
            @foreach($t('faq') as $item)
                <details><summary>{{ $item['q'] }}</summary><p>{{ $item['a'] }}</p></details>
            @endforeach
        </div>
    </section>

    <section class="final-cta">
        <div class="container">
            <h2>{{ $t('cta_title') }}</h2>
            <p>{{ $t('cta_text') }}</p>
            <a class="button" href="#races">{{ $t('hero_cta') }} →</a>
        </div>
    </section>
</main>
@endsection
