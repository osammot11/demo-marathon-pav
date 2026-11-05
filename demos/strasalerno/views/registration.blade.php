@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main">
    <section class="subhero register-hero"><div class="wrap"><p class="eyebrow light">{{ $race['name'] }} · {{ $race['distance'] }} KM</p><h1>{{ __($translationKey.'reg_title') }}</h1><p>{{ __($translationKey.'reg_intro') }}</p></div></section>
    <section class="section form-section"><div class="wrap form-layout"><div class="form-card"><p class="eyebrow">{{ $race['name'] }}</p><h2>{{ __($translationKey.'reg_form_title') }}</h2>@if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@include('demo.registration-fields')
</div><aside class="form-side"><div class="form-side-inner"><span class="eyebrow light">{{ __($translationKey.'edition_note') }}</span><h3>{{ $race['distance'] }}<small> KM</small></h3><p>{{ $race['line'] }}</p><div class="form-side-rule"></div><p>{{ $race['kind'] === 'competitive' ? __($translationKey.'race_requirements_comp') : ($race['slug'] === 'family' ? __($translationKey.'race_requirements_family') : __($translationKey.'race_requirements_fun')) }}</p><div class="form-side-rule"></div><small>{{ __($translationKey.'demo_data_notice') }}</small></div></aside></div></section>
</main>
@endsection
