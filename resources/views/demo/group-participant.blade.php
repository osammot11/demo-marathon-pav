@php
 $selectedRace = (string) (isset($demo['races'][$participant['race'] ?? '']) ? $participant['race'] : $race['slug']);
@endphp
<section class="demo-group-person" data-group-person>
<div class="demo-group-heading"><h3>{{ $groupLabels['participant'] }} <span data-group-number>{{ is_numeric($index) ? $index+1 : '#' }}</span></h3><button type="button" data-group-remove>{{ $groupLabels['remove'] }} ×</button></div>
<label>{{ $groupLabels['race'] }}<select name="participants[{{ $index }}][race]" data-group-race required>
@foreach($races as $slug=>$option)
<option value="{{ $slug }}" @selected($selectedRace === (string) $slug)>{{ $option['name'] }}</option>
@endforeach
</select></label>
@foreach($races as $slug=>$option)
@php
    $personRegistration = app(\App\Services\DemoRegistry::class)->registrationForRace($demo, (string)$slug);
    $personPrice = app(\App\Services\DemoRegistry::class)->price($demo, (string)$slug);
    $personOpen = app(\App\Services\DemoRegistry::class)->isOpen($demo, (string)$slug);
    $html = view('demo.participant-fields', ['demo'=>$demo, 'demoSlug'=>$demoSlug, 'routePrefix'=>$routePrefix, 'translationKey'=>$translationKey, 'participant'=>$participant, 'registration'=>$personRegistration, 'registrationOpen'=>$personOpen])->render();
    $html = preg_replace('/name="([a-z_]+)"/', 'name="participants['.$index.'][$1]"', $html);
@endphp
<fieldset data-group-panel="{{ $slug }}" data-price="{{ $personPrice }}" data-surcharge="{{ data_get($personRegistration,'membership.non_member_surcharge',0) }}" data-exempt="{{ json_encode(data_get($personRegistration,'medical_certificate_optional_for',[])) }}" data-available="{{ $personOpen ? '1' : '0' }}" @disabled($selectedRace !== (string)$slug || !$personOpen) @if($selectedRace !== (string)$slug) hidden @endif>
{!! $html !!}
<div class="registration-fact"><span>{{ __($translationKey.'reg_base_price') }}</span><strong data-group-price>€ {{ number_format($personPrice,2,',','.') }}</strong></div>
@if(!$personOpen)<p>{{ __($translationKey.'reg_closed_button') }}</p>@endif
</fieldset>
@endforeach
</section>
