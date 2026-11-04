<?php

return [
    'name' => 'Coast Run / Demo',
    'locales' => ['it', 'en'],
    'pages' => ['contatti' => 'contact', 'privacy' => 'privacy'],
    'races' => [
        '42' => ['price' => 39.00],
        '10' => ['price' => 19.00],
    ],
    'registration' => [
        'open' => true,
        'fields' => ['first_name', 'last_name', 'email', 'dob', 'phone'],
        'optional_fields' => ['phone'],
        'membership' => ['enabled' => false, 'types' => [], 'non_member_surcharge' => 0],
        'medical_certificate' => false,
        'privacy_url' => '/coast-run/privacy',
    ],
];
