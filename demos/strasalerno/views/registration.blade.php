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
    <form method="post" action="{{ route('demo.checkout.group.store', ['demo' => $demoSlug, 'race' => $race['slug']]) }}" enctype="multipart/form-data" data-group-form data-max-participants="{{ data_get($demo, 'group_registration.max_participants', 10) }}">
        @csrf
        <input type="hidden" name="locale" value="{{ $locale }}">
        <div data-participants>
            @foreach($oldParticipants as $index => $participant)
                @include($demoViewNamespace.'::partials.participant', ['index' => $index, 'participant' => $participant])
            @endforeach
        </div>
        <button class="add-participant" type="button" data-add-participant>+ {{ __($translationKey.'group_add') }}</button>
        <p class="group-limit">{{ __($translationKey.'group_limit') }}</p>
        <div class="group-summary">
            <div><span>{{ __($translationKey.'group_count') }}</span><strong data-people-count>{{ count($oldParticipants) }}</strong></div>
            <div><span>{{ __($translationKey.'reg_total_price') }}</span><strong data-group-total>€ 0,00</strong></div>
        </div>
        <p class="group-discount-note">{{ __($translationKey.'group_discount_note') }}</p>
        <label class="group-discount">{{ __($translationKey.'reg_discount_code') }}<input name="discount_code" type="text" value="{{ old('discount_code') }}" maxlength="100"></label>
        <fieldset class="payment-choice"><legend>{{ __($translationKey.'reg_payment') }}</legend><label><input type="radio" name="payment_method" value="stripe" @disabled(!$registrationOpen) checked> {{ __($translationKey.'reg_stripe') }}</label></fieldset>
        <label class="check-line"><input type="checkbox" name="privacy_consent" value="1" required @disabled(!$registrationOpen) @checked(old('privacy_consent'))><span>{{ __($translationKey.'group_privacy') }} <a href="{{ route($routePrefix.'page', ['demo' => $demoSlug, 'page' => 'privacy']) }}" target="_blank" rel="noopener">{{ __($translationKey.'footer_privacy') }} ↗</a></span></label>
        <p class="eligibility-hint">{{ __($translationKey.'demo_data_notice') }}</p>
        <button class="button dark form-submit" type="submit" @disabled(!$registrationOpen)>{{ $registrationOpen ? __($translationKey.'group_pay') : __($translationKey.'reg_closed_button') }} <span>↗</span></button>
    </form>
    <template id="participant-template">@include($demoViewNamespace.'::partials.participant', ['index' => '__INDEX__', 'participant' => ['race' => $race['slug']]])</template>
    </div><aside class="form-side"><div class="form-side-inner"><span class="eyebrow light">{{ __($translationKey.'edition_note') }}</span><h3>{{ __($translationKey.'group_side_number') }}</h3><p>{{ __($translationKey.'group_side_text') }}</p><div class="form-side-rule"></div><p>{{ __($translationKey.'group_side_eligibility') }}</p><div class="form-side-rule"></div><small>{{ __($translationKey.'demo_data_notice') }}</small></div></aside></div></section>
</main>
@endsection
