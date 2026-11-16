@extends($demoViewNamespace.'::layout')
@section('content')
@php
    $t = fn ($key) => __($translationKey.$key);
    $pageUrl = fn ($page) => route($routePrefix.'page', ['demo' => $demoSlug, 'page' => $page]);
@endphp
@section('title', $t('travel_h1').' | '.$demo['name'])
<main id="content">
    <section class="page-hero"><div class="container"><a class="back-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a><p class="eyebrow">{{ $t('trip_kicker') }}</p><h1>{{ $t('travel_h1') }}</h1><p class="hero-lead">{{ $t('travel_lead') }}</p></div></section>
    <section class="section container info-grid">
        <article class="info-card feature">
            <p class="eyebrow dark">{{ $t('travel_host') }}</p><h2>{{ $t('travel_host_name') }}</h2><p>{{ $t('travel_host_text') }}</p>
            <dl class="key-values">@foreach($t('travel_host_rows') as $row)<div><dt>{{ $row[0] }}</dt><dd>{{ $row[1] }}</dd></div>@endforeach</dl>
            <p class="small">{{ $t('travel_host_how') }}</p>
        </article>
        <article class="info-card"><p class="eyebrow dark">{{ $t('travel_more') }}</p><h3>{{ $t('travel_breezes') }}</h3><p>{{ $t('travel_breezes_text') }}</p><h3>{{ $t('travel_bahamar') }}</h3><p>{{ $t('travel_bahamar_text') }}</p></article>
        <article class="info-card"><p class="eyebrow dark">{{ $t('trip_card_c') }}</p><h3>Bahamasair</h3><dl class="key-values">@foreach($t('travel_flights_rows') as $row)<div><dt>{{ $row[0] }}</dt><dd>{{ $row[1] }}</dd></div>@endforeach</dl></article>
    </section>
</main>
@endsection
