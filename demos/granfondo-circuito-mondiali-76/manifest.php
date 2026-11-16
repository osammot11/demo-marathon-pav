<?php

return [
    'name' => 'Granfondo Circuito dei Mondiali del ’76',
    'locales' => ['it', 'en'],
    'pages' => ['contatti' => 'contact', 'osservazioni' => 'notes', 'privacy' => 'privacy'],
    'races' => [
        // Corsa singola, 136 km (scheda FCI n. 181344). Quota di prova: la scheda federale non riporta quote.
        '136' => ['price' => 35.00],
    ],
    'registration' => [
        'open' => true,
        'fields' => ['first_name', 'last_name', 'dob', 'city', 'province', 'email', 'phone'],
        'optional_fields' => ['phone'],
        // Categoria più giovane ammessa dalla scheda FCI: JMT 17-18 anni.
        'minimum_age' => 17,
        // Gara riservata a tesserati FCI (Master), Enti della Consulta ed extraregionali: nessuna opzione "non tesserato".
        'membership' => ['enabled' => true, 'types' => ['fci', 'consulta'], 'non_member_surcharge' => 0],
        // La scheda federale non richiede un certificato medico separato.
        'medical_certificate' => false,
        'privacy_url' => '/granfondo-circuito-mondiali-76/privacy',
        'privacy_page' => 'privacy',
    ],
];
