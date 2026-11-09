@php
    $en = $locale === 'en';
    $groupLabels = $en
        ? ['participant'=>'Participant', 'add'=>'Add a participant', 'remove'=>'Remove', 'race'=>'Race', 'count'=>'Participants', 'intro'=>'One order, one payment. Choose a race and enter the details for each participant.', 'discount'=>'Any discount is applied to each participant. The final total is calculated at checkout.']
        : ['participant'=>'Partecipante', 'add'=>'Aggiungi un partecipante', 'remove'=>'Rimuovi', 'race'=>'Gara', 'count'=>'Partecipanti', 'intro'=>'Un solo ordine e un solo pagamento. Scegli la gara e compila i dati per ogni partecipante.', 'discount'=>'L’eventuale sconto si applica a ogni partecipante. Il totale finale viene calcolato al checkout.'];
    $participants = old('participants', [['race'=>$race['slug']]]);
    $participants = is_array($participants) && count($participants) ? array_values($participants) : [['race'=>$race['slug']]];
    $maxPeople = min(10, max(1, (int) data_get($demo, 'group_registration.max_participants', 10)));
    $privacyUrl = isset($registration['privacy_page']) ? route($routePrefix.'page', ['demo'=>$demoSlug,'page'=>$registration['privacy_page']]) : $registration['privacy_url'];
@endphp
<p>{{ $groupLabels['intro'] }}</p>
<form method="post" action="{{ route('demo.checkout.group.store',['demo'=>$demoSlug,'race'=>$race['slug']]) }}" enctype="multipart/form-data" data-demo-group data-max="{{ $maxPeople }}" data-open="{{ $registrationOpen ? '1' : '0' }}">
@csrf
<input type="hidden" name="locale" value="{{ $locale }}">
<div data-group-people>
@foreach($participants as $index=>$participant)
@include('demo.group-participant')
@endforeach
</div>
<template data-person-template>@include('demo.group-participant',['index'=>'__INDEX__','participant'=>['race'=>$race['slug']]])</template>
<button type="button" class="button" data-group-add @disabled(!$registrationOpen)>+ {{ $groupLabels['add'] }}</button>
<p>{{ $en ? 'Up to' : 'Fino a' }} {{ $maxPeople }} {{ strtolower($groupLabels['count']) }}.</p>
<div class="registration-fact"><span>{{ $groupLabels['count'] }}</span><strong data-group-count>{{ count($participants) }}</strong></div>
<div class="registration-fact"><span>{{ __($translationKey.'reg_total_price') }}</span><strong data-group-sum>—</strong></div>
<p class="eligibility-hint">{{ $groupLabels['discount'] }}</p>
<label>{{ __($translationKey.'reg_discount_code') }}<input name="discount_code" value="{{ old('discount_code') }}" maxlength="100"></label>
<fieldset class="payment-choice"><legend>{{ __($translationKey.'reg_payment') }}</legend><label><input type="radio" name="payment_method" value="stripe" checked @disabled(!$registrationOpen)> {{ __($translationKey.'reg_stripe') }}</label></fieldset>
<label class="check-line"><input type="checkbox" name="privacy_consent" value="1" required @checked(old('privacy_consent')) @disabled(!$registrationOpen)><span>{{ __($translationKey.'reg_privacy') }} <a href="{{ $privacyUrl }}" target="_blank" rel="noopener">{{ __($translationKey.'footer_privacy') }} ↗</a></span></label>
<p class="eligibility-hint">{{ __($translationKey.'demo_data_notice') }}</p>
<button class="button form-submit" type="submit" @disabled(!$registrationOpen)>{{ $registrationOpen ? __($translationKey.'reg_pay') : __($translationKey.'reg_closed_button') }} ↗</button>
</form>
@include('demo.group-registration-script')
