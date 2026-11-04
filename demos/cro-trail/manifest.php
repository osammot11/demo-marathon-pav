<?php

return [
    'name' => 'CroTrail 2027',
    'locales' => ['it', 'en'],
    'pages' => ['contatti' => 'contact'],
    'races' => [
        '100' => ['price' => 45.00],
        '42' => ['price' => 35.00],
        '21' => ['price' => 28.00],
    ],
    'registration' => [
        'open' => true,
        'fields' => [
            'first_name', 'last_name', 'dob', 'birth_place', 'codice_fiscale',
            'residence_address', 'city', 'zip_code', 'province', 'region',
            'country', 'nationality', 'email', 'phone',
        ],
        'optional_fields' => ['codice_fiscale'],
        'membership' => ['enabled' => true, 'types' => ['uisp', 'eps', 'fidal', 'runcard', 'none'], 'non_member_surcharge' => 10.00],
        'medical_certificate' => true,
        'privacy_url' => 'https://www.crotrail.it/normative-sulla-privacy/',
    ],
];
