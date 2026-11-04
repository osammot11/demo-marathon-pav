@extends('crotrail.layout')

@section('content')
<main id="content">
    <section class="hero">
        <div class="hero-image" role="img" aria-label="{{ __('crotrail.hero_image_alt') }}"></div>
        <div class="hero-shade"></div>
        <div class="hero-content container">
            <p class="eyebrow light"><span class="eyebrow-line"></span> {{ __('crotrail.hero_kicker') }}</p>
            <h1>{{ __('crotrail.hero_h1') }}<br><em>{{ __('crotrail.hero_h2') }}</em></h1>
            <p class="hero-lead">{{ __('crotrail.hero_lead') }}</p>
            <div class="hero-actions"><a href="#gare" class="button">{{ __('crotrail.hero_explore') }} <span>↗</span></a><a href="#esperienza" class="button-ghost">{{ __('crotrail.hero_discover') }} <span>↓</span></a></div>
        </div>
        <div class="hero-bottom container"><span>100 KM&nbsp; / &nbsp;42 KM&nbsp; / &nbsp;21 KM</span><span>{{ strtoupper(__('crotrail.hero_scroll')) }} ↓</span></div>
    </section>

    <section class="intro-section container" id="esperienza">
        <div><p class="eyebrow">{{ __('crotrail.intro_kicker') }}</p><h2>{{ __('crotrail.intro_h1') }}<br><em>{{ __('crotrail.intro_h2') }}</em></h2></div>
        <div class="intro-copy"><p>{{ __('crotrail.intro_p1') }}</p><p>{{ __('crotrail.intro_p2') }}</p><span class="note-line">{{ __('crotrail.intro_note') }}</span></div>
    </section>

    <section class="races-section" id="gare"><div class="container">
        <div class="section-heading"><div><p class="eyebrow">{{ __('crotrail.races_kicker') }}</p><h2>{{ __('crotrail.races_h1') }}<br><em>{{ __('crotrail.races_h2') }}</em></h2></div><p>{{ __('crotrail.races_intro') }}</p></div>
        <div class="race-grid">
            @foreach($races as $race)
            <a href="{{ route($routePrefix.'race', $race['distance']) }}" class="race-card race-card-{{ $race['distance'] }}">
                <div class="race-card-top"><span>0{{ $loop->iteration }} / 03</span><span class="race-arrow">↗</span></div>
                <div class="race-card-main"><span class="race-eyebrow">{{ $race['eyebrow'] }}</span><strong>{{ $race['distance'] }}<small>KM</small></strong><h3>{{ $race['name'] }}</h3><p>{{ $race['line'] }}</p></div>
                <div class="race-card-bottom"><span>{{ __('crotrail.races_discover') }}</span><span>↗</span></div>
            </a>
            @endforeach
        </div>
        <p class="small-note">{{ __('crotrail.races_note') }}</p>
    </div></section>

    <section class="story-section"><div class="container story-inner"><div class="story-number">100<span>KM</span></div><div><p class="eyebrow light">{{ __('crotrail.story_kicker') }}</p><h2>{{ __('crotrail.story_h1') }}<br>{{ __('crotrail.story_h2') }}</h2><p>{{ __('crotrail.story_p') }}</p><a class="text-link light-link" href="{{ route($routePrefix.'race', '100') }}">{{ __('crotrail.story_link') }} <span>↗</span></a></div></div></section>

    <section class="how-section container" id="informazioni"><div class="section-heading"><div><p class="eyebrow">{{ __('crotrail.info_kicker') }}</p><h2>{{ __('crotrail.info_h1') }}<br><em>{{ __('crotrail.info_h2') }}</em></h2></div><p>{{ __('crotrail.info_intro') }}</p></div>
        <div class="how-grid">@foreach(__('crotrail.info_items') as $item)<article><span>0{{ $loop->iteration }}</span><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></article>@endforeach</div>
    </section>

    <section class="faq-section" id="faq"><div class="container faq-layout"><div><p class="eyebrow">{{ __('crotrail.faq_kicker') }}</p><h2>{{ __('crotrail.faq_title') }}</h2></div><div class="faq-list">@foreach(__('crotrail.faq') as $item)<details><summary>{{ $item['question'] }}</summary><p>{{ $item['answer'] }}</p></details>@endforeach</div></div></section>

    <section class="closing-section"><div class="container"><p class="eyebrow light">CROTRAIL 2027</p><h2>{{ __('crotrail.close_h1') }}<br><em>{{ __('crotrail.close_h2') }}</em></h2><p>{{ __('crotrail.close_p') }}</p><a href="#gare" class="button">{{ __('crotrail.close_button') }} <span>↗</span></a></div></section>
</main>
@endsection
