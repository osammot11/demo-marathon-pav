<?php

return [
    // Deliberately hard-coded: this installation must never send participant email.
    'outbound_email_enabled' => false,
    'retention_days' => (int) env('DEMO_DATA_RETENTION_DAYS', 30),
];
