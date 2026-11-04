@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main"><section class="subhero privacy-subhero"><div class="shell subhero-content"><p class="eyebrow">DEMO</p><h1>{{ __($translationKey.'privacy_title') }}</h1><p>{{ __($translationKey.'privacy_intro') }}</p></div></section><section class="section-pad"><div class="shell narrow-copy"><p>{{ __($translationKey.'privacy_body') }}</p><a class="arrow-link" href="{{ $homeUrl }}">← {{ $locale === 'it' ? 'Torna alla homepage' : 'Back to home' }}</a></div></section></main>
@endsection
