@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main"><section class="subhero"><div class="wrap"><p class="eyebrow light">{{ __($translationKey.'weekend_overline') }}</p><h1>{{ __($translationKey.'schedule_title') }}</h1><p>{{ __($translationKey.'schedule_intro') }}</p></div></section>
<section class="section"><div class="wrap schedule-grid"><article><span>01</span><h2>{{ __($translationKey.'schedule_bib') }}</h2><p>{{ __($translationKey.'schedule_bib_text') }}</p></article><article><span>02</span><h2>{{ __($translationKey.'schedule_start') }}</h2><p>{{ __($translationKey.'schedule_start_text') }}</p></article><article><span>03</span><h2>{{ __($translationKey.'schedule_parking') }}</h2><p>{{ __($translationKey.'schedule_parking_text') }}</p></article></div><div class="wrap"><p class="source-note">{{ __($translationKey.'race_detail_note') }}</p></div></section></main>
@endsection
