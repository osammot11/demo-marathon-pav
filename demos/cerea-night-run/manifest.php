<?php
return [
 'name' => 'Cerea Night Run', 'locales' => ['it', 'en'],
 'pages' => ['contatti' => 'contact', 'privacy' => 'privacy', 'programma' => 'schedule'],
 'races' => ['5' => ['price' => 15.00]],
 'registration' => ['open' => true, 'fields' => ['first_name','last_name','email','dob','phone'], 'optional_fields' => ['phone'], 'minimum_age' => 18,
 'membership' => ['enabled' => false, 'types' => [], 'non_member_surcharge' => 0], 'medical_certificate' => false,
 'privacy_page' => 'privacy', 'privacy_url' => '/cerea-night-run/privacy'],
];
