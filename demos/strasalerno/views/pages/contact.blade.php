@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main"><section class="subhero"><div class="wrap"><p class="eyebrow light">{{ __($translationKey.'nav_contact') }}</p><h1>{{ __($translationKey.'contact_title') }}</h1><p>{{ __($translationKey.'contact_intro') }}</p></div></section>
<section class="section"><div class="wrap contact-grid"><a href="mailto:info@visnovasalerno.it"><span>EMAIL</span><strong>info@visnovasalerno.it</strong><b>↗</b></a><a href="tel:+393296170449"><span>TELEFONO / PHONE</span><strong>+39 329 617 0449</strong><b>↗</b></a><a href="https://www.visnovasalerno.it/strasalerno/" target="_blank" rel="noopener"><span>{{ __($translationKey.'contact_website') }}</span><strong>visnovasalerno.it</strong><b>↗</b></a><div><span>SALERNO</span><strong>{{ __($translationKey.'contact_location') }}</strong></div></div></section></main>
@endsection
