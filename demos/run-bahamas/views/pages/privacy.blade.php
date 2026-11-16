@extends($demoViewNamespace.'::layout')
@section('content')
@php
    $t = fn ($key) => __($translationKey.$key);
    $pageUrl = fn ($page) => route($routePrefix.'page', ['demo' => $demoSlug, 'page' => $page]);
@endphp
@section('title', $t('privacy_h1').' | '.$demo['name'])
<main id="content">
    <section class="page-hero"><div class="container"><a class="back-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a><p class="eyebrow">DEMO</p><h1>{{ $t('privacy_h1') }}</h1><p class="hero-lead">{{ $t('privacy_demo_text') }}</p></div></section>
</main>
@endsection
