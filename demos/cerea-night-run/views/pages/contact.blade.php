@extends($demoViewNamespace.'::layout')
@section('content')
<main class="container detail-page"><a class="back-link" href="{{ $homeUrl }}">← CEREA NIGHT RUN</a><p class="eyebrow">CONTACT</p><h1>{{ __($translationKey.'contact_title') }}</h1><p class="lead">{{ __($translationKey.'contact_text') }}</p><p><a href="mailto:info@atleticacerea.it">info@atleticacerea.it</a></p><p><a href="tel:+393920307113">+39 392 030 7113</a></p></main>
@endsection
