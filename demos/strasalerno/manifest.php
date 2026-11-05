<?php

return [
    'name' => 'StraSalerno',
    'locales' => ['it', 'en'],
    'pages' => [
        'percorsi' => 'routes',
        'programma' => 'schedule',
        'contatti' => 'contact',
        'privacy' => 'privacy',
    ],
    'races' => [
        'half' => ['price' => 25.00, 'registration' => [
            'minimum_age' => 18,
            'minimum_age_by_membership' => ['runcard' => 20, 'eps' => 20],
            'membership' => ['enabled' => true, 'types' => ['fidal', 'runcard', 'eps'], 'non_member_surcharge' => 0],
            'medical_certificate' => true,
            'medical_certificate_optional_for' => ['fidal'],
        ]],
        '10k' => ['price' => 15.00, 'registration' => [
            'minimum_age' => 18,
            'membership' => ['enabled' => true, 'types' => ['fidal', 'runcard', 'eps'], 'non_member_surcharge' => 0],
            'medical_certificate' => true,
            'medical_certificate_optional_for' => ['fidal'],
        ]],
        'run' => ['price' => 10.00],
        'family' => ['price' => 5.00],
    ],
    'registration' => [
        'open' => true,
        'fields' => ['first_name', 'last_name', 'email', 'dob', 'city', 'phone'],
        'optional_fields' => ['phone'],
        'minimum_age' => 18,
        'membership' => ['enabled' => false, 'types' => [], 'non_member_surcharge' => 0],
        'medical_certificate' => false,
        'privacy_url' => '/strasalerno/privacy',
        'privacy_page' => 'privacy',
    ],
    'operations' => ['bib_pickup' => true],
    'group_registration' => ['enabled' => true, 'max_participants' => 10],
];
