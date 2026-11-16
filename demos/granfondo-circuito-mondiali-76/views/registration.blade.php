@extends($demoViewNamespace.'::layout')

@section('content')
@php
    $money = '€ '.($locale === 'en' ? number_format($registrationPrice, 2, '.', ',') : number_format($registrationPrice, 2, ',', '.'));
    $zero = '€ '.($locale === 'en' ? '0.00' : '0,00');
@endphp
<main id="content" class="registration-page">
    <div class="container registration-shell">
        <div class="registration-heading">
            <a class="back-link dark-link" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $race['slug']]) }}">← {{ __($translationKey.'reg_back') }}</a>
            <p class="eyebrow">{{ __($translationKey.'reg_kicker') }} / {{ $race['distance'] }} km</p>
            <h1>{{ __($translationKey.'reg_h1') }}<br><em>{{ __($translationKey.'reg_h2') }}</em></h1>
            <p>{{ $registrationOpen ? __($translationKey.'reg_open_intro') : __($translationKey.'reg_intro') }}</p>
        </div>
        <div class="registration-layout">
            <div class="registration-form-card registration-information">
                <p class="eyebrow">{{ $race['name'] }} · {{ $registrationOpen ? $money : __($translationKey.'reg_status_value') }}</p>
                <h2>{{ __($translationKey.'reg_form_title') }}</h2>
                @unless($registrationOpen)
                    <div class="registration-closed" role="status"><strong>{{ __($translationKey.'reg_status_value') }}</strong><p>{{ __($translationKey.'reg_details') }}</p></div>
                @endunless
                @if($errors->any())
                    <div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                @include('demo.registration-fields')
            </div>
            <aside class="registration-aside">
                <span class="aside-label">{{ __($translationKey.'reg_selected') }}</span>
                <strong>{{ $race['distance'] }} <small>KM</small></strong>
                <h3>{{ $race['name'] }}</h3>
                <div class="aside-rule"></div>
                <div><span>{{ __($translationKey.'reg_aside_meeting') }}</span><b>08:00 · Viale Pola</b></div>
                <div><span>{{ __($translationKey.'reg_aside_start') }}</span><b>09:30 · {{ $race['place'] }}</b></div>
                <div class="aside-rule"></div>
                <p class="aside-label">{{ __($translationKey.'reg_aside_cost') }}</p>
                <div><span>{{ __($translationKey.'reg_aside_fee') }}</span><b>{{ $money }}</b></div>
                <div><span>{{ __($translationKey.'reg_aside_extra') }}</span><b>{{ $zero }}</b></div>
                <div class="aside-total"><span>{{ __($translationKey.'reg_aside_total') }}</span><b>{{ $money }}</b></div>
                <p class="aside-foot">{{ __($translationKey.'reg_aside_after') }}</p>
                <p class="aside-foot">{{ __($translationKey.'reg_aside_note') }}</p>
            </aside>
        </div>
    </div>
</main>
@endsection
