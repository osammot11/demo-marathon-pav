@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main"><section class="subhero register-subhero"><div class="shell subhero-content"><p class="eyebrow">SORA → VEROLI · 44 KM</p><h1>{{ __($translationKey.'reg_title') }}</h1><p>{{ __($translationKey.'reg_intro') }}</p></div></section>
<section class="registration-section section-pad"><div class="shell registration-layout"><div class="registration-form-card"><p class="eyebrow">44 KM / 3.560 M+</p><h2>{{ __($translationKey.'reg_form_title') }}</h2>@if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@include('demo.registration-fields')</div><aside class="registration-aside"><span class="eyebrow">{{ __($translationKey.'edition_note') }}</span><h3>{{ __($translationKey.'journey_title') }}</h3><ol>@foreach(__($translationKey.'journey_steps') as $step)<li><strong>{{ $step['number'] }} · {{ $step['title'] }}</strong><span>{{ $step['text'] }}</span></li>@endforeach</ol><p>{{ __($translationKey.'demo_data_notice') }}</p></aside></div></section></main>
@endsection
