@extends($demoViewNamespace.'::layout')
@section('content')
<main class="container detail-page registration-page"><a class="back-link" href="{{ route($routePrefix.'race', ['demo' => $demoSlug, 'race' => $race['slug']]) }}">← {{ $race['name'] }}</a>
    <p class="eyebrow">{{ __($translationKey.'register') }} · {{ $race['distance'] }} KM</p><h1>{{ __($translationKey.'reg_form_title') }}</h1><p>{{ __($translationKey.'reg_intro') }}</p>
    @if($errors->any())<div class="form-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div class="registration-form-card">@include('demo.registration-fields')</div>
</main>
@endsection
