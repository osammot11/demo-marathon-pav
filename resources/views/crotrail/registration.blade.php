@extends('crotrail.layout')

@section('content')
<main id="content" class="registration-page"><div class="container registration-shell">
    <div class="registration-heading">
        <a class="back-link dark-link" href="{{ route($routePrefix.'race', $race['distance']) }}">← {{ __('crotrail.reg_back') }}</a>
        <p class="eyebrow">{{ __('crotrail.reg_kicker') }} / {{ $race['distance'] }} km</p>
        <h1>{{ __('crotrail.reg_h1') }}<br><em>{{ __('crotrail.reg_h2') }}</em></h1>
        <p>{{ $registrationOpen ? __('crotrail.reg_open_intro') : __('crotrail.reg_intro') }}</p>
    </div>
    <div class="registration-layout"><div class="registration-form-card registration-information">
            <p class="eyebrow">{{ $race['name'] }} · {{ $registrationOpen ? '€ '.number_format($registrationPrice, 2, ',', '.') : __('crotrail.reg_status_value') }}</p>
            <h2>{{ __('crotrail.reg_form_title') }}</h2>
            @unless($registrationOpen)<div class="registration-closed" role="status"><strong>{{ __('crotrail.reg_status_value') }}</strong><p>{{ __('crotrail.reg_details') }}</p></div>@endunless
            @if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="post" action="{{ route('checkout.store', $race['distance']) }}" enctype="multipart/form-data" data-registration-pricing data-base-price="{{ $registrationPrice }}" data-non-member-surcharge="{{ $nonMemberSurcharge }}" data-locale="{{ $locale }}">
                @csrf
                <input type="hidden" name="locale" value="{{ $locale }}">
                <fieldset class="athlete-fields" @disabled(!$registrationOpen)>
                <div class="form-grid">
                    @foreach(__('crotrail.form_labels') as $name => $label)
                        <label>{{ $label }}<input name="{{ $name }}" type="{{ $name === 'dob' ? 'date' : ($name === 'email' ? 'email' : ($name === 'phone' ? 'tel' : 'text')) }}" value="{{ old($name) }}" @if($name !== 'codice_fiscale') required @endif @if($name === 'email') autocomplete="email" @endif></label>
                    @endforeach
                </div>
                <div class="eligibility-fields">
                    <h3>{{ __('crotrail.reg_eligibility_title') }}</h3>
                    <div class="form-grid">
                        <label>{{ __('crotrail.reg_membership_type') }}
                            <select name="membership_type" required>
                                <option value="">{{ __('crotrail.reg_membership_placeholder') }}</option>
                                @foreach(['uisp' => 'UISP', 'eps' => 'EPS', 'fidal' => 'FIDAL', 'runcard' => 'RunCard', 'none' => __('crotrail.reg_membership_none')] as $type => $label)
                                    <option value="{{ $type }}" @selected(old('membership_type') === $type)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>{{ __('crotrail.reg_membership_number') }}
                            <input name="membership_number" type="text" value="{{ old('membership_number') }}" maxlength="100" autocomplete="off">
                            <small>{{ __('crotrail.reg_membership_number_hint') }}</small>
                        </label>
                        <label>{{ __('crotrail.reg_medical_expiry') }}
                            <input name="medical_certificate_expires_on" type="date" value="{{ old('medical_certificate_expires_on') }}" required>
                        </label>
                        <label>{{ __('crotrail.reg_medical_certificate') }}
                            <input name="medical_certificate" type="file" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" required>
                        </label>
                    </div>
                    <p class="eligibility-hint">{{ __('crotrail.reg_medical_hint') }}</p>
                    <label class="check-line"><input type="checkbox" name="medical_certificate_attestation" value="1" required @checked(old('medical_certificate_attestation'))><span>{{ __('crotrail.reg_medical_attestation') }}</span></label>
                </div>
                </fieldset>
                <div class="registration-fact"><span>{{ __('crotrail.reg_base_price') }}</span><strong>{{ $registrationOpen ? '€ '.number_format($registrationPrice, 2, ',', '.') : __('crotrail.reg_fee_pending') }}</strong></div>
                @if($registrationOpen)
                    <div class="registration-fact" data-surcharge-row @if(old('membership_type') !== 'none') hidden @endif><span>{{ __('crotrail.reg_non_member_supplement') }}</span><strong>+ € {{ number_format($nonMemberSurcharge, 2, ',', '.') }}</strong></div>
                    <div class="registration-fact total-fact"><span>{{ __('crotrail.reg_total_price') }}</span><strong data-total-price>€ {{ number_format($registrationPrice + (old('membership_type') === 'none' ? $nonMemberSurcharge : 0), 2, ',', '.') }}</strong></div>
                    <p class="eligibility-hint">{{ __('crotrail.reg_review_note') }}</p>
                @endif
                <fieldset class="payment-choice"><legend>{{ __('crotrail.reg_payment') }}</legend>
                    <label><input type="radio" name="payment_method" value="stripe" @disabled(!$registrationOpen || !in_array('stripe', $paymentMethods, true)) {{ old('payment_method', 'stripe') === 'stripe' && $registrationOpen && in_array('stripe', $paymentMethods, true) ? 'checked' : '' }}> {{ __('crotrail.reg_stripe') }}</label>
                    <label><input type="radio" name="payment_method" value="paypal" @disabled(!$registrationOpen || !in_array('paypal', $paymentMethods, true)) {{ old('payment_method') === 'paypal' && $registrationOpen ? 'checked' : '' }}> {{ __('crotrail.reg_paypal') }}</label>
                </fieldset>
                <label class="check-line"><input type="checkbox" name="privacy_consent" value="1" required @disabled(!$registrationOpen) {{ old('privacy_consent') ? 'checked' : '' }}><span>{{ __('crotrail.reg_privacy') }} <a href="https://www.crotrail.it/normative-sulla-privacy/" target="_blank" rel="noopener">{{ __('crotrail.footer_privacy') }} ↗</a></span></label>
                <button class="button form-submit" type="submit" @disabled(!$registrationOpen)>{{ $registrationOpen ? __('crotrail.reg_pay') : __('crotrail.reg_closed_button') }} <span>↗</span></button>
            </form>
    </div><aside class="registration-aside"><span class="aside-label">{{ __('crotrail.reg_selected') }}</span><strong>{{ $race['distance'] }} <small>KM</small></strong><h3>{{ $race['name'] }}</h3><p>{{ $race['description'] }}</p><div class="aside-rule"></div><div><span>{{ __('crotrail.fact_when') }}</span><b>{{ __('crotrail.june_2027') }}</b></div><p class="aside-foot">{{ __('crotrail.reg_aside_note') }}</p></aside></div>
</div></main>
@endsection
