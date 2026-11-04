@extends($demoViewNamespace.'::layout')

@section('content')
<main id="content" class="registration-page"><div class="container registration-shell">
    <div class="registration-heading">
        <a class="back-link dark-link" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $race['distance']]) }}">← {{ __($translationKey.'reg_back') }}</a>
        <p class="eyebrow">{{ __($translationKey.'reg_kicker') }} / {{ $race['distance'] }} km</p>
        <h1>{{ __($translationKey.'reg_h1') }}<br><em>{{ __($translationKey.'reg_h2') }}</em></h1>
        <p>{{ $registrationOpen ? __($translationKey.'reg_open_intro') : __($translationKey.'reg_intro') }}</p>
    </div>
    <div class="registration-layout"><div class="registration-form-card registration-information">
            <p class="eyebrow">{{ $race['name'] }} · {{ $registrationOpen ? '€ '.number_format($registrationPrice, 2, ',', '.') : __($translationKey.'reg_status_value') }}</p>
            <h2>{{ __($translationKey.'reg_form_title') }}</h2>
            @unless($registrationOpen)<div class="registration-closed" role="status"><strong>{{ __($translationKey.'reg_status_value') }}</strong><p>{{ __($translationKey.'reg_details') }}</p></div>@endunless
            @if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @include('demo.registration-fields')
    </div><aside class="registration-aside"><span class="aside-label">{{ __($translationKey.'reg_selected') }}</span><strong>{{ $race['distance'] }} <small>KM</small></strong><h3>{{ $race['name'] }}</h3><p>{{ $race['description'] }}</p><div class="aside-rule"></div><div><span>{{ __($translationKey.'fact_when') }}</span><b>{{ __($translationKey.'june_2027') }}</b></div><p class="aside-foot">{{ __($translationKey.'reg_aside_note') }}</p></aside></div>
</div></main>
@endsection
