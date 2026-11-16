@extends($demoViewNamespace.'::layout')
@section('content')
@php
    $t = fn ($key) => __($translationKey.$key);
    $pageUrl = fn ($page) => route($routePrefix.'page', ['demo' => $demoSlug, 'page' => $page]);
@endphp
@section('title', $t('notes_h1').' | '.$demo['name'])
<main id="content">
    <section class="page-hero"><div class="container"><a class="back-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a><p class="eyebrow">DEMO</p><h1>{{ $t('notes_h1') }}</h1><p class="hero-lead">{{ $t('notes_lead') }}</p></div></section>
    <section class="section container"><ol class="notes-list">@foreach($t('notes_rows') as $row)<li><h3>{{ $row[0] }}</h3><p>{{ $row[1] }}</p></li>@endforeach</ol><p class="callout">{{ $t('notes_next') }}</p></section>
</main>
@endsection
