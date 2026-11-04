@extends($demoViewNamespace.'::layout')
@section('content')
<main id="main"><section class="subhero contact-subhero"><div class="shell subhero-content"><p class="eyebrow">ASD ERNICA VEROLI</p><h1>{{ __($translationKey.'contact_title') }}</h1><p>{{ __($translationKey.'contact_intro') }}</p></div></section>
<section class="section-pad"><div class="shell contact-grid"><div class="contact-main"><span class="eyebrow">EMAIL</span><a href="mailto:ernicaveroli@gmail.com">ernicaveroli@gmail.com <span aria-hidden="true">↗</span></a></div><div class="contact-side"><div><span class="eyebrow">TELEFONO</span><a href="tel:+393665934640">+39 366 593 4640</a></div><div><span class="eyebrow">{{ __($translationKey.'contact_website') }}</span><a href="https://www.ernica.it/" target="_blank" rel="noopener">ernica.it ↗</a></div><div><span class="eyebrow">SEDE</span><p>{{ __($translationKey.'contact_location') }}</p></div></div></div></section></main>
@endsection
