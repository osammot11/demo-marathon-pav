@extends($demoViewNamespace.'::layout')
@section('content')
@php
    $t = fn ($key) => __($translationKey.$key);
    $pageUrl = fn ($page) => route($routePrefix.'page', ['demo' => $demoSlug, 'page' => $page]);
@endphp
@section('title', $t('giving_h1').' | '.$demo['name'])
<main id="content">
    <section class="page-hero"><div class="container"><a class="back-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a><p class="eyebrow">{{ $t('giving_kicker') }}</p><h1>{{ $t('giving_h1') }}</h1><p class="hero-lead">{{ $t('giving_lead') }}</p></div></section>
    <section class="section container info-grid">
        <article class="info-card feature"><p class="eyebrow dark">{{ $t('giving_charity_title') }}</p><h2>Gennie Dean Caring and Sharing Cancer Support Group</h2><p>{{ $t('giving_charity_text') }}</p></article>
        <article class="info-card"><p class="eyebrow dark">{{ $t('giving_sponsor_title') }}</p><p>{{ $t('giving_sponsor_text') }}</p><ul class="tick-list">@foreach($t('giving_sponsor_tiers') as $tier)<li>{{ $tier }}</li>@endforeach</ul></article>
        <article class="info-card"><p class="eyebrow dark">{{ $t('giving_volunteer_title') }}</p><p>{{ $t('giving_volunteer_text') }}</p><p class="small">{{ $t('giving_form_note') }}</p><a class="button" href="{{ $pageUrl('contatti') }}">{{ $t('nav_contact') }} →</a></article>
    </section>
</main>
@endsection
