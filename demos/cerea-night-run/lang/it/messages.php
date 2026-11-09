<?php
$messages = [
    'meta_description' => 'Coast Run: una demo di iscrizioni per eventi sportivi.',
    'hero_kicker' => 'DEMO DI PIATTAFORMA · COSTA', 'hero_title' => 'Corri verso il mare.',
    'hero_text' => 'Due distanze, un orizzonte. Questa manifestazione è fittizia e serve a mostrare il sistema demo.',
    'races_title' => 'Scegli la tua gara', 'race_link' => 'Scopri la gara', 'register' => 'Iscriviti',
    'contact_title' => 'Contatti', 'contact_text' => 'Questo evento è una demo. Per informazioni sul progetto scrivi a Produce a Value.',
    'privacy_demo_text' => 'Questo è un sito dimostrativo. Non inserire dati personali reali; usa soltanto dati di fantasia e una carta di prova Stripe.',
    'reg_intro' => 'Completa il modulo con dati di fantasia per provare il checkout.',
    'reg_form_title' => 'Dati partecipante', 'reg_base_price' => 'Quota base', 'reg_total_price' => 'Totale',
    'reg_discount_code' => 'Codice sconto (facoltativo)', 'reg_payment' => 'Pagamento', 'reg_stripe' => 'Carta di prova',
    'reg_privacy' => 'Ho letto l’informativa privacy.', 'footer_privacy' => 'Privacy', 'reg_pay' => 'Vai al pagamento',
    'reg_closed_button' => 'Iscrizioni chiuse', 'demo_data_notice' => 'Usa solo dati di fantasia. Nessun addebito reale e nessuna email viene inviata.',
    'test_mode_label' => 'Demo · pagamento di prova',
    'form_labels' => ['first_name' => 'Nome', 'last_name' => 'Cognome', 'email' => 'Email', 'dob' => 'Data di nascita', 'phone' => 'Telefono'],
    'checkout_order' => 'Ordine', 'checkout_payment_title' => 'Pagamento di prova',
    'checkout_payment_intro' => 'Usa una carta test Stripe. Non verrà effettuato un addebito reale.',
    'checkout_participants' => 'Partecipanti', 'checkout_total' => 'Totale', 'checkout_pay' => 'Paga',
    'checkout_back' => 'Torna all’iscrizione', 'checkout_error' => 'Pagamento non riuscito.',
    'checkout_pending' => 'Pagamento in elaborazione.', 'checkout_success' => 'Iscrizione confermata',
    'test_success' => 'Iscrizione demo confermata', 'checkout_thanks' => 'Grazie per aver provato la demo.',
    'checkout_review_title' => 'Pagamento ricevuto', 'checkout_review_text' => 'Iscrizione in attesa di verifica.',
    'checkout_review_status' => 'In verifica', 'checkout_ticket_view' => 'Visualizza biglietto',
    'checkout_tickets' => 'Biglietto', 'checkout_cancel' => 'Pagamento non completato',
    'checkout_cancel_text' => 'Puoi riprovare il pagamento di prova.', 'checkout_retry' => 'Riprova',
    'races' => [
        '42' => ['distance' => '42', 'name' => 'Maratona 42 km', 'eyebrow' => 'La distanza regina', 'line' => 'Il lungomare è il tuo traguardo.', 'description' => 'Un percorso simbolico tra città e costa. Evento fittizio per dimostrazione.', 'place' => 'Costa'],
        '10' => ['distance' => '10', 'name' => 'Corsa 10 km', 'eyebrow' => 'Parti leggero', 'line' => 'Dieci chilometri di energia.', 'description' => 'Una sfida accessibile per vivere la giornata di gara. Evento fittizio per dimostrazione.', 'place' => 'Costa'],
    ],
];

return array_replace($messages, json_decode('{"hero_kicker": "CEREA · VERONA · RUNNING BY NIGHT", "hero_title": "La città si accende. Tu corri.", "hero_text": "Cinque chilometri da vivere fino in fondo. Un circuito cittadino, la corsa della sera e una festa che continua oltre il traguardo.", "races_title": "La gara", "race_link": "Scopri i 5 km", "meta_description": "Cerea Night Run: 5 km nel cuore di Cerea. Gara, informazioni e iscrizione.", "contact_text": "ASD Atletica Cerea · Via Monti Lessini, 5 · 37053 Cerea (VR)", "next": "Prossima edizione", "pending": "Data e orari in aggiornamento", "edition": "Riferimenti dell’edizione 2026", "intro": "Una sera. Cinque chilometri. Tutta Cerea.", "description": "L’ultima edizione pubblicata, il 6 giugno 2026, ha proposto un percorso cittadino di 5 km omologato FIDAL e chiuso al traffico, nel circuito Verona con la corsa. Le informazioni del prossimo appuntamento saranno confermate dall’organizzazione.", "where": "Il punto di ritrovo", "place": "Campo sportivo Pelaloca", "place_text": "Via Monti Lessini, 5 · Cerea (VR). Sede indicata nella locandina 2026; da riconfermare per la prossima edizione.", "night": "Dopo il traguardo, resta.", "night_text": "Nell’edizione 2026 la corsa ha lasciato spazio a musica dal vivo e DJ set. Lo spirito è quello di una serata da condividere, anche quando il cronometro si ferma.", "program": "Programma e informazioni", "program_text": "Data, orario di partenza, ritiro pettorali e scadenza delle iscrizioni del prossimo appuntamento non sono ancora confermati. Qui trovi il riferimento ufficiale dell’ultima edizione.", "poster": "Locandina ufficiale 2026", "rules": "Regolamento 2026", "source": "Sito ufficiale", "test_mode_label": "Anteprima indipendente · iscrizioni e pagamenti di prova", "reg_intro": "Prova l’iscrizione con dati di fantasia. Quota dimostrativa di 15 €, non ufficiale. Per questa prova sono ammessi solo maggiorenni; requisiti sportivi e categorie della prossima edizione saranno definiti con l’organizzazione.", "price_note": "Quota dimostrativa, non ufficiale", "footer_note": "Proposta demo per ASD Atletica Cerea", "services": "Servizi dell’edizione 2026", "services_text": "Deposito borse, spogliatoi, docce e ristoro indicati nella locandina ufficiale. Disponibilità della prossima edizione da confermare."}', true), [
'races' => ['5' => ['distance' => '5', 'name' => 'Cerea Night Run · 5 km', 'eyebrow' => 'CITY RUN / 5K', 'line' => 'La tua sfida, al calare del sole.', 'description' => 'Circuito cittadino di 5 km. Tracciato e omologazione della prossima edizione da confermare.', 'place' => 'Cerea (VR)']],
]);
