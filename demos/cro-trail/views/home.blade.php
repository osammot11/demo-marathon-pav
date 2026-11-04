@extends($demoViewNamespace.'::layout')

@section('content')
<main id="content">
    <section class="hero">
        <div class="hero-image" role="img" aria-label="{{ __($translationKey.'hero_image_alt') }}"></div>
        <div class="hero-shade"></div>
        <div class="hero-content container">
            <p class="eyebrow light"><span class="eyebrow-line"></span> {{ __($translationKey.'hero_kicker') }}</p>
            <h1>{{ __($translationKey.'hero_h1') }}<br><em>{{ __($translationKey.'hero_h2') }}</em></h1>
            <p class="hero-lead">{{ __($translationKey.'hero_lead') }}</p>
            <div class="hero-actions"><a href="#gare" class="button">{{ __($translationKey.'hero_explore') }} <span>↗</span></a><a href="#esperienza" class="button-ghost">{{ __($translationKey.'hero_discover') }} <span>↓</span></a></div>
        </div>
        <div class="hero-bottom container"><span>100 KM&nbsp; / &nbsp;42 KM&nbsp; / &nbsp;21 KM</span><span>{{ strtoupper(__($translationKey.'hero_scroll')) }} ↓</span></div>
    </section>

    <section class="intro-section container" id="esperienza">
        <div><p class="eyebrow">{{ __($translationKey.'intro_kicker') }}</p><h2>{{ __($translationKey.'intro_h1') }}<br><em>{{ __($translationKey.'intro_h2') }}</em></h2></div>
        <div class="intro-copy"><p>{{ __($translationKey.'intro_p1') }}</p><p>{{ __($translationKey.'intro_p2') }}</p><span class="note-line">{{ __($translationKey.'intro_note') }}</span></div>
    </section>

    <section class="races-section" id="gare"><div class="container">
        <div class="section-heading"><div><p class="eyebrow">{{ __($translationKey.'races_kicker') }}</p><h2>{{ __($translationKey.'races_h1') }}<br><em>{{ __($translationKey.'races_h2') }}</em></h2></div><p>{{ __($translationKey.'races_intro') }}</p></div>
        <div class="race-grid">
            @foreach($races as $race)
            <a href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $race['distance']]) }}" class="race-card race-card-{{ $race['distance'] }}">
                <div class="race-card-top"><span>0{{ $loop->iteration }} / 03</span><span class="race-arrow">↗</span></div>
                <div class="race-card-main"><span class="race-eyebrow">{{ $race['eyebrow'] }}</span><strong>{{ $race['distance'] }}<small>KM</small></strong><h3>{{ $race['name'] }}</h3><p>{{ $race['line'] }}</p></div>
                <div class="race-card-bottom"><span>{{ __($translationKey.'races_discover') }}</span><span>↗</span></div>
            </a>
            @endforeach
        </div>
        <p class="small-note">{{ __($translationKey.'races_note') }}</p>
    </div></section>

    <section class="story-section"><div class="container story-inner"><div class="story-number">100<span>KM</span></div><div><p class="eyebrow light">{{ __($translationKey.'story_kicker') }}</p><h2>{{ __($translationKey.'story_h1') }}<br>{{ __($translationKey.'story_h2') }}</h2><p>{{ __($translationKey.'story_p') }}</p><a class="text-link light-link" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => '100']) }}">{{ __($translationKey.'story_link') }} <span>↗</span></a></div></div></section>

    <section class="how-section container" id="informazioni"><div class="section-heading"><div><p class="eyebrow">{{ __($translationKey.'info_kicker') }}</p><h2>{{ __($translationKey.'info_h1') }}<br><em>{{ __($translationKey.'info_h2') }}</em></h2></div><p>{{ __($translationKey.'info_intro') }}</p></div>
        <div class="how-grid">@foreach(__($translationKey.'info_items') as $item)<article><span>0{{ $loop->iteration }}</span><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></article>@endforeach</div>
    </section>

    <section class="faq-section" id="faq"><div class="container faq-layout"><div><p class="eyebrow">{{ __($translationKey.'faq_kicker') }}</p><h2>{{ __($translationKey.'faq_title') }}</h2></div><div class="faq-list">@foreach(__($translationKey.'faq') as $item)<details><summary>{{ $item['question'] }}</summary><p>{{ $item['answer'] }}</p></details>@endforeach</div></div></section>

    <section class="closing-section"><div class="container"><p class="eyebrow light">CROTRAIL 2027</p><h2>{{ __($translationKey.'close_h1') }}<br><em>{{ __($translationKey.'close_h2') }}</em></h2><p>{{ __($translationKey.'close_p') }}</p><a href="#gare" class="button">{{ __($translationKey.'close_button') }} <span>↗</span></a></div></section>
</main>
@endsection
