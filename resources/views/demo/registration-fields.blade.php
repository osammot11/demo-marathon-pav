@php
    $registration = $demo['registration'];
    $hasMembership = (bool) data_get($registration, 'membership.enabled', false);
    $hasMedical = (bool) ($registration['medical_certificate'] ?? false);
    $optional = $registration['optional_fields'] ?? [];
@endphp
<form method="post" action="{{ route('demo.checkout.store', ['demo' => $demoSlug, 'race' => $race['slug']]) }}" enctype="multipart/form-data" data-registration-pricing data-base-price="{{ $registrationPrice }}" data-non-member-surcharge="{{ $nonMemberSurcharge }}" data-locale="{{ $locale }}">
    @csrf
    <input type="hidden" name="locale" value="{{ $locale }}">
    <fieldset class="athlete-fields" @disabled(!$registrationOpen)>
        <div class="form-grid">
            @foreach($registration['fields'] as $name)
                @php($type = match ($name) { 'dob' => 'date', 'email' => 'email', 'phone' => 'tel', default => 'text' })
                <label>{{ __($translationKey.'form_labels.'.$name) }}
                    <input name="{{ $name }}" type="{{ $type }}" value="{{ old($name) }}" @if(!in_array($name, $optional, true)) required @endif @if($name === 'email') autocomplete="email" @endif>
                </label>
            @endforeach
        </div>
        @if($hasMembership)
            <div class="eligibility-fields">
                <h3>{{ __($translationKey.'reg_eligibility_title') }}</h3>
                <div class="form-grid">
                    <label>{{ __($translationKey.'reg_membership_type') }}
                        <select name="membership_type" required>
                            <option value="">{{ __($translationKey.'reg_membership_placeholder') }}</option>
                            @foreach($registration['membership']['types'] as $type)
                                <option value="{{ $type }}" @selected(old('membership_type') === $type)>{{ $type === 'none' ? __($translationKey.'reg_membership_none') : strtoupper($type) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>{{ __($translationKey.'reg_membership_number') }}
                        <input name="membership_number" type="text" value="{{ old('membership_number') }}" maxlength="100">
                    </label>
                </div>
            </div>
        @endif
        @if($hasMedical)
            <div class="eligibility-fields">
                <div class="form-grid">
                    <label>{{ __($translationKey.'reg_medical_expiry') }}<input name="medical_certificate_expires_on" type="date" value="{{ old('medical_certificate_expires_on') }}" required></label>
                    <label>{{ __($translationKey.'reg_medical_certificate') }}<input name="medical_certificate" type="file" accept=".pdf,.jpg,.jpeg,.png" required></label>
                </div>
                <p class="eligibility-hint">{{ __($translationKey.'reg_medical_hint') }}</p>
                <label class="check-line"><input type="checkbox" name="medical_certificate_attestation" value="1" required @checked(old('medical_certificate_attestation'))><span>{{ __($translationKey.'reg_medical_attestation') }}</span></label>
            </div>
        @endif
    </fieldset>
    <div class="registration-fact"><span>{{ __($translationKey.'reg_base_price') }}</span><strong>€ {{ number_format($registrationPrice ?? 0, 2, ',', '.') }}</strong></div>
    @if($hasMembership && $nonMemberSurcharge > 0)
        <div class="registration-fact" data-surcharge-row @if(old('membership_type') !== 'none') hidden @endif><span>{{ __($translationKey.'reg_non_member_supplement') }}</span><strong>+ € {{ number_format($nonMemberSurcharge, 2, ',', '.') }}</strong></div>
    @endif
    <div class="registration-fact total-fact"><span>{{ __($translationKey.'reg_total_price') }}</span><strong data-total-price>€ {{ number_format(($registrationPrice ?? 0) + (old('membership_type') === 'none' ? $nonMemberSurcharge : 0), 2, ',', '.') }}</strong></div>
    <label>{{ __($translationKey.'reg_discount_code') }}<input name="discount_code" type="text" value="{{ old('discount_code') }}" maxlength="100"></label>
    <fieldset class="payment-choice"><legend>{{ __($translationKey.'reg_payment') }}</legend><label><input type="radio" name="payment_method" value="stripe" @disabled(!$registrationOpen) checked> {{ __($translationKey.'reg_stripe') }}</label></fieldset>
    <label class="check-line"><input type="checkbox" name="privacy_consent" value="1" required @disabled(!$registrationOpen) @checked(old('privacy_consent'))><span>{{ __($translationKey.'reg_privacy') }} <a href="{{ $registration['privacy_url'] }}" target="_blank" rel="noopener">{{ __($translationKey.'footer_privacy') }} ↗</a></span></label>
    <p class="eligibility-hint">{{ __($translationKey.'demo_data_notice') }}</p>
    <button class="button form-submit" type="submit" @disabled(!$registrationOpen)>{{ $registrationOpen ? __($translationKey.'reg_pay') : __($translationKey.'reg_closed_button') }} <span>↗</span></button>
</form>
