<?php

return [
    'registration_open' => env('CROTRAIL_REGISTRATION_OPEN', false),
    'test_mode' => env('CROTRAIL_TEST_MODE', false),
    'email_mode' => 'log',
    'non_member_surcharge' => env('CROTRAIL_NON_MEMBER_SURCHARGE'),
    'document_policy_approved' => env('CROTRAIL_DOCUMENT_POLICY_APPROVED', false),
    'prices' => [
        '100' => env('CROTRAIL_PRICE_100'),
        '42' => env('CROTRAIL_PRICE_42'),
        '21' => env('CROTRAIL_PRICE_21'),
    ],
    'brevo_key' => null,
    'sender_name' => env('CROTRAIL_SENDER_NAME', 'CroTrail'),
    'sender_email' => env('CROTRAIL_SENDER_EMAIL'),
];
