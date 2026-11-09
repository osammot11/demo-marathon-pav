@extends($demoViewNamespace.'::layout')
@section('content')
@php
    $oldParticipants = old('participants', [['race' => $race['slug']]]);
    $oldParticipants = is_array($oldParticipants) && $oldParticipants !== [] ? array_values($oldParticipants) : [['race' => $race['slug']]];
@endphp
<main id="main">
    <section class="subhero register-hero"><div class="wrap"><p class="eyebrow light">{{ $race['name'] }} · {{ $race['distance'] }} KM</p><h1>{{ __($translationKey.'reg_title') }}</h1><p>{{ __($translationKey.'group_intro') }}</p></div></section>
    <section class="section form-section"><div class="wrap form-layout"><div class="form-card group-form-card"><p class="eyebrow">{{ __($translationKey.'group_eyebrow') }}</p><h2>{{ __($translationKey.'group_title') }}</h2><p class="group-lead">{{ __($translationKey.'group_subtitle') }}</p>
    @if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @include('demo.registration-fields')
    </div><aside class="form-side"><div class="form-side-inner"><span class="eyebrow light">{{ __($translationKey.'edition_note') }}</span><h3>{{ __($translationKey.'group_side_number') }}</h3><p>{{ __($translationKey.'group_side_text') }}</p><div class="form-side-rule"></div><p>{{ __($translationKey.'group_side_eligibility') }}</p><div class="form-side-rule"></div><small>{{ __($translationKey.'demo_data_notice') }}</small></div></aside></div></section>
</main>
@endsection
