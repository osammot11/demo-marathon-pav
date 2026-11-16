@extends($demoViewNamespace.'::layout')

@section('content')
@php
    $t = fn ($key) => __($translationKey.$key);
    $money = fn ($v) => '€ '.($locale === 'en' ? number_format($v, 2, '.', ',') : number_format($v, 2, ',', '.'));
@endphp
<main id="content" class="registration-page"><div class="container registration-shell">
    <div class="registration-heading">
        <a class="back-link dark-link" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $race['slug']]) }}">← {{ $t('reg_back') }}</a>
        <p class="eyebrow dark">{{ $t('reg_kicker') }} / {{ $race['name'] }}</p>
        <h1>{{ $t('reg_h1') }} <em>{{ $t('reg_h2') }}</em></h1>
        <p>{{ $registrationOpen ? $t('reg_open_intro') : $t('reg_intro') }}</p>
    </div>
    <div class="registration-layout">
        <div class="registration-form-card">
            <h2>{{ $t('reg_form_title') }}</h2>
            @if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @if($race['slug'] === 'relay')<p class="callout">{{ $t('relay_note') }}</p>@endif
            @include('demo.registration-fields')
        </div>
        <aside class="registration-aside">
            <span class="aside-label">{{ $t('reg_selected') }}</span>
            <strong>{{ $race['distance'] }} <small>{{ strtoupper($t('miles')) }}</small></strong>
            <h3>{{ $race['name'] }}</h3>
            <p>{{ $race['line'] }}</p>
            <div class="aside-rule"></div>
            <div class="aside-row"><span>{{ $t('race_when') }}</span><b>{{ $t('race_when_value') }}</b></div>
            <div class="aside-row"><span>{{ $t('race_start') }}</span><b>{{ $race['start'] }}</b></div>
            <div class="aside-row"><span>{{ $t('reg_base_price') }}</span><b>{{ $money($registrationPrice) }}</b></div>
            <p class="aside-foot">{{ $t('reg_aside_note') }}</p>
        </aside>
    </div>
</div></main>
@endsection
