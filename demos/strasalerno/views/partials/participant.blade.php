@php
    $selectedRace = $participant['race'] ?? $race['slug'];
    $selectedRace = isset($demo['races'][$selectedRace]) ? $selectedRace : $race['slug'];
    $competitive = (bool) data_get($demo, 'races.'.$selectedRace.'.registration.membership.enabled', false);
    $membership = $participant['membership_type'] ?? '';
    $certificateRequired = $competitive && $membership !== 'fidal' && $membership !== '';
@endphp
<fieldset class="participant-card" data-participant>
    <legend><span data-participant-number>{{ is_numeric($index) ? ((int) $index + 1) : '#' }}</span> · {{ __($translationKey.'group_participant') }}</legend>
    <button class="remove-participant" type="button" data-remove-participant>{{ __($translationKey.'group_remove') }} ×</button>
    <div class="form-grid">
        <label>{{ __($translationKey.'group_race') }}
            <select name="participants[{{ $index }}][race]" data-race-select required>
                @foreach($races as $slug => $option)
                    <option value="{{ $slug }}" data-price="{{ number_format(app(\App\Services\DemoRegistry::class)->price($demo, $slug), 2, '.', '') }}" data-competitive="{{ data_get($demo, 'races.'.$slug.'.registration.membership.enabled') ? '1' : '0' }}" @selected($selectedRace === $slug)>{{ $option['name'] }} · € {{ number_format(app(\App\Services\DemoRegistry::class)->price($demo, $slug), 2, ',', '.') }}</option>
                @endforeach
            </select>
        </label>
        <div class="participant-price"><span>{{ __($translationKey.'reg_base_price') }}</span><strong data-person-price></strong></div>
        <label>{{ __($translationKey.'form_labels.first_name') }}<input type="text" name="participants[{{ $index }}][first_name]" value="{{ $participant['first_name'] ?? '' }}" autocomplete="given-name" required></label>
        <label>{{ __($translationKey.'form_labels.last_name') }}<input type="text" name="participants[{{ $index }}][last_name]" value="{{ $participant['last_name'] ?? '' }}" autocomplete="family-name" required></label>
        <label>{{ __($translationKey.'form_labels.email') }}<input type="email" name="participants[{{ $index }}][email]" value="{{ $participant['email'] ?? '' }}" autocomplete="email" required></label>
        <label>{{ __($translationKey.'form_labels.dob') }}<input type="date" name="participants[{{ $index }}][dob]" value="{{ $participant['dob'] ?? '' }}" required></label>
        <label>{{ __($translationKey.'form_labels.city') }}<input type="text" name="participants[{{ $index }}][city]" value="{{ $participant['city'] ?? '' }}" required></label>
        <label>{{ __($translationKey.'form_labels.phone') }}<input type="tel" name="participants[{{ $index }}][phone]" value="{{ $participant['phone'] ?? '' }}"></label>
    </div>
    <div class="participant-eligibility" data-competitive-fields @if(! $competitive) hidden @endif>
        <h3>{{ __($translationKey.'reg_eligibility_title') }}</h3>
        <div class="form-grid">
            <label>{{ __($translationKey.'reg_membership_type') }}
                <select name="participants[{{ $index }}][membership_type]" data-membership-select @if($competitive) required @endif>
                    <option value="">{{ __($translationKey.'reg_membership_placeholder') }}</option>
                    <option value="fidal" @selected($membership === 'fidal')>FIDAL</option>
                    <option value="runcard" @selected($membership === 'runcard')>RunCard</option>
                    <option value="eps" @selected($membership === 'eps')>EPS</option>
                </select>
            </label>
            <label>{{ __($translationKey.'reg_membership_number') }}<input type="text" name="participants[{{ $index }}][membership_number]" value="{{ $participant['membership_number'] ?? '' }}" data-membership-number @if($competitive) required @endif></label>
            <label>{{ __($translationKey.'reg_medical_expiry') }}<input type="date" name="participants[{{ $index }}][medical_certificate_expires_on]" value="{{ $participant['medical_certificate_expires_on'] ?? '' }}" data-medical-expiry @if($certificateRequired) required @endif></label>
            <label>{{ __($translationKey.'reg_medical_certificate') }}<input type="file" name="participants[{{ $index }}][medical_certificate]" accept=".pdf,.jpg,.jpeg,.png" data-medical-file @if($certificateRequired) required @endif></label>
        </div>
        <p class="eligibility-hint">{{ __($translationKey.'reg_medical_hint') }}</p>
        <label class="check-line"><input type="checkbox" name="participants[{{ $index }}][medical_certificate_attestation]" value="1" data-medical-attestation @checked(! empty($participant['medical_certificate_attestation']))><span>{{ __($translationKey.'reg_medical_attestation') }}</span></label>
    </div>
</fieldset>
