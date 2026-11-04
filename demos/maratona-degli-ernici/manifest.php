<?php

return [
    'name' => 'Maratona degli Ernici',
    'locales' => ['it', 'en'],
    'pages' => ['contatti' => 'contact', 'percorso' => 'route', 'logistica' => 'logistics', 'privacy' => 'privacy'],
    'races' => [
        '44' => ['price' => 50.00],
    ],
    'registration' => [
        'open' => true,
        'fields' => ['first_name', 'last_name', 'email', 'dob', 'birth_place', 'city', 'phone'],
        'optional_fields' => [],
        'minimum_age' => 18,
        'membership' => ['enabled' => false, 'types' => [], 'non_member_surcharge' => 0],
        'medical_certificate' => true,
        'shuttle' => ['enabled' => true],
        'privacy_url' => '/maratona-degli-ernici/privacy',
    ],
    'operations' => ['shuttle' => true, 'bib_pickup' => true],
];
