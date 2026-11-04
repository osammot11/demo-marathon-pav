@extends($demoViewNamespace.'::layout')

@section('content')
<main id="content" class="registration-page"><div class="container registration-shell">
    <div class="registration-heading">
        <a class="back-link dark-link" href="{{ $homeUrl }}">← {{ __($translationKey.'back_races') }}</a>
        <p class="eyebrow">{{ __($translationKey.'contact_kicker') }}</p>
        <h1>{{ __($translationKey.'contact_title') }}</h1>
        <p>{{ __($translationKey.'contact_text') }}</p>
        <a class="button" href="https://www.crotrail.it/" target="_blank" rel="noopener">{{ __($translationKey.'contact_official_link') }} ↗</a>
    </div>
</div></main>
@endsection
