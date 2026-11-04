@extends('layouts.app')

@section('content')
    <main>
        <section>
            <h1>Contatti</h1>
            <p class="top-margin-large">Per informazioni su iscrizioni, percorsi e aspetti organizzativi puoi scrivere allo staff di Maratona Alzheimer.</p>
            <a href="mailto:info@maratonaalzheimer.it" class="btn top-margin-xl mobile-fullwidth">Scrivici</a>
        </section>

        <section class="grid-2">
            <div class="card">
                <h2>Email</h2>
                <p class="top-margin-large"><a class="hyperlink" href="mailto:info@maratonaalzheimer.it">info@maratonaalzheimer.it</a><br>+39 0547 91411</p>
            </div>
            <div class="card">
                <h2>Sedi</h2>
                <p class="top-margin-large">Partenza a Cesena e arrivo al Parco di Levante di Cesenatico. Sede organizzativa a Mercato Saraceno (FC).</p>
            </div>
        </section>
    </main>
@endsection
