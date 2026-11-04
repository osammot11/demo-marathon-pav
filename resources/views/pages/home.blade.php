@extends('layouts.app')

@push('head')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "SportsEvent",
        "name": "Maratona Alzheimer 2026",
        "startDate": "2026-09-12T09:30:00+02:00",
        "endDate": "2026-09-13T14:00:00+02:00",
        "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
        "eventStatus": "https://schema.org/EventScheduled",
        "url": "https://www.maratonaalzheimer.it/it/",
        "image": "{{ url('/images/maratona-alzheimer/hero.jpg') }}",
        "description": "Un weekend di sport e solidarietà tra Cesena e Cesenatico per i diritti delle persone con demenza.",
        "location": {"@@type": "Place", "name": "Cesena e Cesenatico", "address": {"@@type": "PostalAddress", "addressRegion": "Emilia-Romagna", "addressCountry": "IT"}},
        "organizer": {"@@type": "Organization", "name": "Fondazione Maratona Alzheimer", "url": "https://www.maratonaalzheimer.it/it/"}
    }
    </script>
@endpush

@section('content')
    <div class="home-hero alzheimer-hero">
        <section class="hero-height light-color alzheimer-hero__content">
            <p class="hero-kicker">Cesena · Cesenatico · 12–13 settembre 2026</p>
            <h1 class="hero-title top-margin-mid">INSIEME PER<br><span>CANCELLARE</span><br>IL SILENZIO</h1>
            <p class="hero-copy top-margin-large">Corri, cammina, partecipa. Ogni passo sostiene i diritti, la cura, la prevenzione e la ricerca per le persone con demenza.</p>
            <div class="hero-actions top-margin-xl">
                <a href="/iscrizione" class="btn">Partecipa</a>
                <a href="#percorsi" class="btn btn-outline">Scopri i percorsi</a>
            </div>
        </section>

        <div class="race-strip" aria-label="Dati dell'evento">
            <div><strong>15ª EDIZIONE</strong><span>Dal 2012 insieme</span></div>
            <div><strong>21,097 KM</strong><span>Mezza Maratona FIDAL</span></div>
            <div><strong>2 · 8 · 16 KM</strong><span>Marce per tutti</span></div>
            <div><strong>50%</strong><span>Della quota alla Fondazione</span></div>
        </div>
    </div>

    <main>
        <section class="grid-2 intro-section alzheimer-intro">
            <div>
                <p class="section-kicker">Sport, comunità, diritti</p>
                <h2>Una corsa che lascia il segno, ben oltre il traguardo.</h2>
                <p class="top-margin-large">Maratona Alzheimer è il grande evento sportivo solidale nato per dare voce alle persone che vivono con una demenza e alle loro famiglie.</p>
                <p class="top-margin-large">Da Cesena al mare di Cesenatico, runner, famiglie, bambini, volontari e caregiver si muovono nella stessa direzione: costruire una cultura della cura più aperta e inclusiva.</p>
                <a href="#missione" class="text-link top-margin-large">Conosci la nostra missione</a>
            </div>
            <figure class="alzheimer-story-card">
                <img src="/images/maratona-alzheimer/mezza-maraton.jpg" alt="Partecipanti alla Mezza Maratona Alzheimer 2026" loading="lazy" decoding="async">
                <figcaption><span class="numero">{{ $totalIscritti }}</span><span>partecipanti già iscritti online</span></figcaption>
            </figure>
        </section>

        <section id="percorsi" class="race-section">
            <div class="section-heading">
                <div><p class="section-kicker">Il tuo passo conta</p><h2>Scegli come esserci.</h2></div>
                <p>Dalla gara competitiva alle camminate aperte a tutti: percorsi diversi, un unico messaggio solidale.</p>
            </div>

            <div class="route-grid top-margin-xl">
                <article class="race-card race-card--half">
                    <span class="route-badge">Competitiva FIDAL</span>
                    <div class="race-card__body">
                        <p class="race-card__number">21K</p><h3>Mezza Maratona</h3>
                        <p>Da Piazza del Popolo a Cesena fino al Parco di Levante a Cesenatico. Un percorso veloce, pianeggiante e solidale.</p>
                        <a href="/iscrizione?percorso=Mezza%20Maratona%2021K" class="btn top-margin-large">Iscriviti alla 21K</a>
                    </div>
                </article>
                <article class="race-card race-card--walks">
                    <span class="route-badge">Non competitiva</span>
                    <div class="race-card__body">
                        <p class="race-card__number">16·8·2</p><h3>Marce Alzheimer</h3>
                        <p>Grande, Piccola o Mini: tre distanze accessibili per unire generazioni, città e territori verso lo stesso arrivo.</p>
                        <a href="/iscrizione?percorso=Grande%20Marcia%2016K" class="btn top-margin-large">Scegli la marcia</a>
                    </div>
                </article>
            </div>
            <div class="activity-pills top-margin-large" aria-label="Altre attività">
                <a href="/iscrizione?percorso=Kids%20Run">Kids Run</a>
                <a href="/iscrizione?percorso=Dog%20Walking">Dog Walking</a>
                <a href="/iscrizione?percorso=Maratona%20Virtuale">Maratona Virtuale</a>
            </div>
        </section>

        <section id="missione" class="alzheimer-manifesto">
            <div class="manifesto-mark" aria-hidden="true"><img src="/images/maratona-alzheimer/logo.png" alt=""></div>
            <div>
                <p class="section-kicker">Perché partecipare</p>
                <h2>Ogni passo rompe il silenzio.</h2>
                <p class="top-margin-large">Il 50% della quota della Mezza Maratona sostiene i progetti della Fondazione Maratona Alzheimer: tutela dei diritti, modelli innovativi di cura, prevenzione e ricerca scientifica.</p>
                <a href="https://alzheimer.donareonline.it/" class="btn btn-dark top-margin-xl" target="_blank" rel="noopener">Sostieni la Fondazione</a>
            </div>
        </section>

        <section id="programma" class="program-section">
            <div class="section-heading">
                <div><p class="section-kicker">Un intero weekend</p><h2>Due giorni da vivere insieme.</h2></div>
                <p>Sport, informazione e inclusione si incontrano al Parco di Levante e lungo il percorso Cesena–Cesenatico.</p>
            </div>
            <div class="grid-3 top-margin-xl">
                <div class="card program-card"><span class="card-index">SABATO 12</span><h3>Il Village</h3><ul class="program-list"><li><strong>09:30</strong> Dog Walking</li><li><strong>10:00</strong> Evergreen Walk</li><li><strong>16:00</strong> Kids Run</li></ul></div>
                <div class="card program-card featured-card"><span class="card-index">DOMENICA 13</span><h3>La Mezza</h3><ul class="program-list"><li><strong>08:00</strong> Partenza da Cesena</li><li><strong>10:00</strong> Premiazioni</li><li><strong>11:00</strong> Festa al Parco</li></ul></div>
                <div class="card program-card"><span class="card-index">DOMENICA 13</span><h3>Le Marce</h3><ul class="program-list"><li><strong>08:30</strong> Grande Marcia 16 km</li><li><strong>09:30</strong> Piccola Marcia 8 km</li><li><strong>10:00</strong> Mini Marcia 2 km</li></ul></div>
            </div>
        </section>

        <section class="quick-links">
            <a href="/regolamento"><span>Regolamento</span><strong>01</strong></a>
            <a href="/#programma"><span>Programma</span><strong>02</strong></a>
            <a href="/contatti"><span>Contatti</span><strong>03</strong></a>
        </section>

        <section class="faq-section">
            <p class="section-kicker">Informazioni utili</p><h2>Prima di partire</h2>
            <div class="faq-accordion top-margin-xl">
                <div class="faq-item"><button class="faq-question" aria-expanded="false"><span>Quando si svolge Maratona Alzheimer 2026?</span><span class="faq-icon">+</span></button><div class="faq-answer"><p>Il Village apre sabato 12 settembre al Parco di Levante. Mezza Maratona e Marce Alzheimer si svolgono domenica 13 settembre 2026 tra Cesena e Cesenatico.</p></div></div>
                <div class="faq-item"><button class="faq-question" aria-expanded="false"><span>Devo essere un runner per partecipare?</span><span class="faq-icon">+</span></button><div class="faq-answer"><p>No. Oltre alla Mezza Maratona competitiva puoi scegliere camminate da 16, 8 o 2 km, Kids Run, Dog Walking o la Maratona Virtuale.</p></div></div>
                <div class="faq-item"><button class="faq-question" aria-expanded="false"><span>Come viene usata la quota di iscrizione?</span><span class="faq-icon">+</span></button><div class="faq-answer"><p>Il 50% della quota della Mezza Maratona viene devoluto alla Fondazione Maratona Alzheimer per progetti dedicati a diritti, cura, prevenzione e ricerca.</p></div></div>
            </div>
        </section>

        <section class="final-cta">
            <p class="section-kicker">13 settembre 2026</p><h2>Il tuo passo può fare rumore.</h2>
            <a href="/iscrizione" class="btn top-margin-xl">Partecipa a Maratona Alzheimer</a>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="/accordion.js"></script>
@endpush
