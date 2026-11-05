@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main"><section class="subhero"><div class="wrap"><p class="eyebrow light">DEMO</p><h1>{{ __($translationKey.'privacy_title') }}</h1><p>{{ __($translationKey.'privacy_text') }}</p></div></section><section class="section"><div class="wrap privacy-card"><p>{{ __($translationKey.'privacy_text') }}</p><a class="text-link" href="{{ $homeUrl }}">{{ $locale === 'it' ? 'Torna alla home' : 'Back to home' }} <span>↗</span></a></div></section></main>
@endsection
