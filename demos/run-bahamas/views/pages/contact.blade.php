@extends($demoViewNamespace.'::layout')
@section('content')
@php
    $t = fn ($key) => __($translationKey.$key);
    $pageUrl = fn ($page) => route($routePrefix.'page', ['demo' => $demoSlug, 'page' => $page]);
@endphp
@section('title', $t('contact_h1').' | '.$demo['name'])
<main id="content">
    <section class="page-hero"><div class="container"><a class="back-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a><p class="eyebrow">{{ $t('contact_kicker') }}</p><h1>{{ $t('contact_h1') }}</h1><p class="hero-lead">{{ $t('contact_lead') }}</p></div></section>
    <section class="section container"><article class="info-card feature narrow"><h2>{{ $t('contact_company') }}</h2><p>{{ $t('contact_address') }}</p><p class="small">{{ $t('contact_note') }}</p><a class="button" href="https://runbahamas.net/contact-us/" target="_blank" rel="noopener">{{ $t('contact_official') }} ↗</a></article></section>
</main>
@endsection
