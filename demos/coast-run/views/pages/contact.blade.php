@extends($demoViewNamespace.'::layout')
@section('content')
<main class="container detail-page"><a class="back-link" href="{{ $homeUrl }}">← COAST RUN</a><p class="eyebrow">CONTACT</p><h1>{{ __($translationKey.'contact_title') }}</h1><p class="lead">{{ __($translationKey.'contact_text') }}</p></main>
@endsection
