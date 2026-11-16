<?php

// Run Bahamas 2027 — demo. Prezzi = quota gara "early" visibile su RunSignup (nov 2026), senza SignUp fee.
// Valuta del checkout condiviso: EUR (pagamenti di prova). In produzione servirebbe USD/BSD.
$inPerson = ['shuttle' => ['enabled' => true]];
$virtual = ['shuttle' => ['enabled' => false]];

return [
    'name' => 'Run Bahamas 2027',
    'locales' => ['it', 'en'],
    'pages' => [
        'travel' => 'travel',
        'giving-back' => 'giving',
        'contatti' => 'contact',
        'privacy' => 'privacy',
        'notes' => 'notes',
    ],
    'races' => [
        'marathon' => ['price' => 155.00, 'registration' => $inPerson],
        'half-marathon' => ['price' => 105.00, 'registration' => $inPerson],
        'relay' => ['price' => 42.50, 'registration' => $inPerson], // 170 per squadra da 4 = 42,50 a corridore (ipotesi)
        '10k' => ['price' => 55.00, 'registration' => $inPerson],
        '5k' => ['price' => 40.00, 'registration' => $inPerson],
        'student-5k' => ['price' => 30.00, 'registration' => $inPerson],
        'virtual-half' => ['price' => 105.00, 'registration' => $virtual],
        'virtual-marathon' => ['price' => 155.00, 'registration' => $virtual],
    ],
    'registration' => [
        'open' => true,
        'fields' => ['first_name', 'last_name', 'dob', 'email', 'phone', 'city', 'country'],
        'optional_fields' => ['phone'],
        'membership' => ['enabled' => false, 'types' => [], 'non_member_surcharge' => 0],
        'medical_certificate' => false,
        'privacy_page' => 'privacy',
        'privacy_url' => '/run-bahamas/privacy',
    ],
    'group_registration' => ['enabled' => true, 'max_participants' => 10],
];
