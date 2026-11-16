@extends($demoViewNamespace.'::layout')

@section('content')
@php
    $raceUrl = route($routePrefix.'race', ['demo' => $demoSlug, 'race' => '136']);
    $registerUrl = route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => '136']);
    $notesUrl = route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'osservazioni']);
    $price = app(\App\Services\DemoRegistry::class)->price($demo, '136');
    $money = '€ '.($locale === 'en' ? number_format($price, 2, '.', ',') : number_format($price, 2, ',', '.'));
    $zero = '€ '.($locale === 'en' ? '0.00' : '0,00');
    $anchors = ['programma' => '#programma', 'requisiti' => '#requisiti', 'iscrizione' => $registerUrl, 'dopo' => '#dopo'];
@endphp
<main id="content">
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow"><span class="eyebrow-line"></span> {{ __($translationKey.'hero_kicker') }}</p>
                <h1><span class="h1-small">{{ __($translationKey.'hero_h1_a') }}</span><span class="h1-big">{{ __($translationKey.'hero_h1_b') }}</span><em>{{ __($translationKey.'hero_h1_c') }}</em></h1>
                <p class="hero-lead">{{ __($translationKey.'hero_lead') }}</p>
                <div class="hero-actions">
                    <a class="button" href="{{ $registerUrl }}">{{ __($translationKey.'hero_cta') }} <span>↗</span></a>
                    <a class="button-ghost" href="#programma">{{ __($translationKey.'hero_cta2') }} <span>↓</span></a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-number" aria-hidden="true">76</div>
                <aside class="hero-card" aria-label="{{ __($translationKey.'hero_card_title') }}">
                    <p class="status-pill">{{ __($translationKey.'hero_card_status') }}</p>
                    <dl>
                        @foreach(__($translationKey.'hero_card_rows') as $row)
                            <div><dt>{{ $row['label'] }}</dt><dd>{{ $row['value'] }}</dd></div>
                        @endforeach
                    </dl>
                    <p class="card-note">{{ __($translationKey.'hero_card_note') }}</p>
                </aside>
            </div>
        </div>
    </section>

    <section class="journey-section">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">{{ __($translationKey.'journey_kicker') }}</p><h2>{{ __($translationKey.'journey_h1') }}<br><em>{{ __($translationKey.'journey_h2') }}</em></h2></div>
                <p>{{ __($translationKey.'journey_intro') }}</p>
            </div>
            <ol class="journey-grid">
                @foreach(__($translationKey.'journey') as $step)
                    <li><a href="{{ $anchors[$step['anchor']] }}"><span class="step-number">0{{ $loop->iteration }}</span><h3>{{ $step['title'] }}</h3><p>{{ $step['text'] }}</p><b>↗</b></a></li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="race-section" id="gara">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">{{ __($translationKey.'race_kicker') }}</p><h2>{{ __($translationKey.'race_h1') }}<br><em>{{ __($translationKey.'race_h2') }}</em></h2></div>
                <p>{{ __($translationKey.'race_intro') }}</p>
            </div>
            <div class="race-layout">
                <a class="race-card" href="{{ $raceUrl }}">
                    <span class="race-card-top"><span>{{ $races['136']['eyebrow'] }}</span><span class="race-arrow">↗</span></span>
                    <span class="race-card-number">136<small>KM</small></span>
                    <span class="race-card-bottom"><strong>{{ $races['136']['name'] }}</strong><span>{{ __($translationKey.'race_card_cta') }} ↗</span></span>
                </a>
                <div class="race-info">
                    <dl class="fact-list">
                        @foreach(__($translationKey.'race_facts') as $fact)
                            <div><dt>{{ $fact['label'] }}</dt><dd>{{ $fact['value'] }}</dd></div>
                        @endforeach
                    </dl>
                    <h3>{{ __($translationKey.'race_categories_title') }}</h3>
                    <ul class="chip-list">
                        @foreach(__($translationKey.'categories') as $category)
                            <li>{{ $category }}</li>
                        @endforeach
                    </ul>
                    <p class="small-note">{{ __($translationKey.'race_paralympic') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="program-section" id="programma">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow light">{{ __($translationKey.'program_kicker') }}</p><h2>{{ __($translationKey.'program_h1') }}<br><em>{{ __($translationKey.'program_h2') }}</em></h2></div>
                <p>{{ __($translationKey.'program_intro') }}</p>
            </div>
            <ol class="timeline">
                @foreach(__($translationKey.'program') as $item)
                    <li><time>{{ $item['time'] }}</time><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></li>
                @endforeach
            </ol>
            <p class="timeline-meta"><strong>{{ __($translationKey.'program_updated') }}</strong><span>{{ __($translationKey.'program_ref') }}</span></p>
        </div>
    </section>

    <section class="requirements-section" id="requisiti">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow">{{ __($translationKey.'req_kicker') }}</p><h2>{{ __($translationKey.'req_h1') }}<br><em>{{ __($translationKey.'req_h2') }}</em></h2></div>
                <p>{{ __($translationKey.'req_intro') }}</p>
            </div>
            <div class="req-grid">
                @foreach(__($translationKey.'req') as $item)
                    <article><span class="step-number">0{{ $loop->iteration }}</span><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="price-section" id="iscrizione">
        <div class="container price-layout">
            <div>
                <p class="eyebrow">{{ __($translationKey.'price_kicker') }}</p>
                <h2>{{ __($translationKey.'price_h1') }}<br><em>{{ __($translationKey.'price_h2') }}</em></h2>
                <p class="price-intro">{{ __($translationKey.'price_intro') }}</p>
                <a class="button" href="{{ $registerUrl }}">{{ __($translationKey.'price_cta') }} <span>↗</span></a>
            </div>
            <div class="price-card">
                <div class="price-row"><span>{{ __($translationKey.'price_fee') }}</span><strong>{{ $money }}</strong></div>
                <div class="price-row"><span>{{ __($translationKey.'price_extra') }}</span><strong>{{ $zero }}</strong></div>
                <div class="price-row price-total"><span>{{ __($translationKey.'price_total') }}</span><strong>{{ $money }}</strong></div>
                <p class="small-note">{{ __($translationKey.'price_note') }}</p>
            </div>
        </div>
    </section>

    <section class="after-section" id="dopo">
        <div class="container">
            <div class="section-heading">
                <div><p class="eyebrow light">{{ __($translationKey.'after_kicker') }}</p><h2>{{ __($translationKey.'after_h1') }}<br><em>{{ __($translationKey.'after_h2') }}</em></h2></div>
                <p>{{ __($translationKey.'after_intro') }}</p>
            </div>
            <ol class="status-flow">
                @foreach(__($translationKey.'after') as $state)
                    <li><span class="status-dot">{{ $loop->iteration }}</span><h3>{{ $state['title'] }}</h3><p>{{ $state['text'] }}</p></li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="faq-section" id="faq">
        <div class="container faq-layout">
            <div><p class="eyebrow">{{ __($translationKey.'faq_kicker') }}</p><h2>{{ __($translationKey.'faq_title') }}</h2></div>
            <div class="faq-list">
                @foreach(__($translationKey.'faq') as $item)
                    <details><summary>{{ $item['question'] }}</summary><p>{{ $item['answer'] }}</p></details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="org-band">
        <div class="container org-inner">
            <div><p class="eyebrow">{{ __($translationKey.'org_kicker') }}</p><h3>{{ __($translationKey.'org_h') }}</h3><p>{{ __($translationKey.'org_text') }}</p></div>
            <a class="button-outline" href="{{ $notesUrl }}">{{ __($translationKey.'org_link') }} <span>↗</span></a>
        </div>
    </section>

    <section class="closing-section">
        <div class="container">
            <p class="eyebrow light">{{ __($translationKey.'close_kicker') }}</p>
            <h2>{{ __($translationKey.'close_h1') }}<br><em>{{ __($translationKey.'close_h2') }}</em></h2>
            <p>{{ __($translationKey.'close_p') }}</p>
            <a class="button" href="{{ $registerUrl }}">{{ __($translationKey.'close_button') }} <span>↗</span></a>
        </div>
    </section>
</main>
@endsection
