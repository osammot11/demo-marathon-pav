@extends($demoViewNamespace.'::layout')
@section('content')
<main class="container detail-page"><a class="back-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a><p class="eyebrow">DEMO</p><h1>{{ __($translationKey.'footer_privacy') }}</h1><p class="lead">{{ __($translationKey.'privacy_demo_text') }}</p></main>
@endsection
