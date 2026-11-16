@extends($demoViewNamespace.'::layout')

@section('content')
@php
    $registerUrl = route($routePrefix.'registration', ['demo' => $demoSlug, 'race' => $race['slug']]);
    $money = '€ '.($locale === 'en' ? number_format($registrationPrice, 2, '.', ',') : number_format($registrationPrice, 2, ',', '.'));
@endphp
<main id="content">
    <section class="race-hero">
        <div class="container race-hero-grid">
            <div class="race-hero-copy">
                <a class="back-link" href="{{ $homeUrl }}#gara">← {{ __($translationKey.'race_back') }}</a>
                <p class="eyebrow light">{{ $race['eyebrow'] }} · {{ __($translationKey.'race_hero_kicker') }}</p>
                <h1>{{ __($translationKey.'wordmark_top') }}</h1>
                <p class="hero-lead">{{ $race['line'] }}</p>
            </div>
            <div class="race-hero-number" aria-hidden="true">{{ $race['distance'] }}<small>KM</small></div>
        </div>
    </section>
    <div class="race-facts">
        <div class="container race-facts-inner">
            <div><span>{{ __($translationKey.'fact_distance') }}</span><strong>{{ $race['distance'] }} km</strong></div>
            <div><span>{{ __($translationKey.'fact_meeting') }}</span><strong>08:00 · Viale Pola</strong></div>
            <div><span>{{ __($translationKey.'fact_start') }}</span><strong>09:30 · {{ $race['place'] }}</strong></div>
            <div><span>{{ __($translationKey.'fact_fee') }}</span><strong>{{ $money }}</strong></div>
        </div>
    </div>
    <section class="race-detail container">
        <div>
            <p class="eyebrow">{{ __($translationKey.'race_detail_kicker') }} / {{ $race['distance'] }} km</p>
            <h2>{{ $race['name'] }}</h2>
            <p class="detail-lead">{{ $race['description'] }}</p>
            <p>{{ __($translationKey.'race_detail_p') }}</p>
            <div class="detail-actions">
                <a class="button" href="{{ $registerUrl }}">{{ $registrationOpen ? __($translationKey.'race_enter') : __($translationKey.'race_registration') }} <span>↗</span></a>
                <a class="text-link" href="{{ $homeUrl }}#programma">{{ __($translationKey.'race_program_link') }}</a>
            </div>
        </div>
        <aside class="detail-panel">
            <p class="eyebrow">{{ __($translationKey.'brief') }}</p>
            @foreach(__($translationKey.'brief_rows') as $row)
                <div><span>0{{ $loop->iteration }} / {{ $row['label'] }}</span><strong>{{ $row['value'] }}</strong></div>
            @endforeach
            <div><span>05 / {{ __($translationKey.'fact_registrations') }}</span><strong>{{ $registrationOpen ? __($translationKey.'status_open') : __($translationKey.'status_closed') }}</strong></div>
            <p class="panel-note">{{ __($translationKey.'race_panel_note') }}</p>
        </aside>
    </section>
    <section class="after-section after-compact">
        <div class="container">
            <p class="eyebrow light">{{ __($translationKey.'after_kicker') }}</p>
            <ol class="status-flow">
                @foreach(__($translationKey.'after') as $state)
                    <li><span class="status-dot">{{ $loop->iteration }}</span><h3>{{ $state['title'] }}</h3><p>{{ $state['text'] }}</p></li>
                @endforeach
            </ol>
        </div>
    </section>
</main>
@endsection
