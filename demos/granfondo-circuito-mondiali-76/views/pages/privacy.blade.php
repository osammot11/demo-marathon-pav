@extends($demoViewNamespace.'::layout')

@section('content')
<main id="content" class="registration-page">
    <div class="container registration-shell">
        <div class="registration-heading">
            <a class="back-link dark-link" href="{{ $homeUrl }}">← {{ $demo['name'] }}</a>
            <p class="eyebrow">Demo</p>
            <h1>{{ __($translationKey.'privacy_title') }}</h1>
            <p>{{ __($translationKey.'privacy_demo_text') }}</p>
        </div>
        <div class="registration-form-card contact-card">
            <ul class="check-list">
                @foreach(__($translationKey.'privacy_points') as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</main>
@endsection
