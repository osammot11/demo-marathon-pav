@extends($demoViewNamespace.'::layout')

@section('content')
<main id="content" class="registration-page">
    <div class="container registration-shell">
        <div class="registration-heading">
            <a class="back-link dark-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a>
            <p class="eyebrow">{{ __($translationKey.'contact_title') }}</p>
            <h1>{{ __($translationKey.'contact_heading') }}</h1>
            <p>{{ __($translationKey.'contact_text') }}</p>
        </div>
        <div class="registration-form-card contact-card">
            <p class="eyebrow">{{ __($translationKey.'contact_org') }}</p>
            <h2>{{ __($translationKey.'contact_org_name') }}</h2>
            <dl class="fact-list">
                <div><dt>{{ __($translationKey.'contact_phone') }}</dt><dd>{{ __($translationKey.'contact_phone_value') }}</dd></div>
                <div><dt>{{ __($translationKey.'contact_email') }}</dt><dd>{{ __($translationKey.'contact_email_value') }}</dd></div>
                <div><dt>{{ __($translationKey.'contact_meeting') }}</dt><dd>{{ __($translationKey.'contact_meeting_value') }}</dd></div>
            </dl>
            <p class="small-note">{{ __($translationKey.'contact_note') }}</p>
        </div>
    </div>
</main>
@endsection
