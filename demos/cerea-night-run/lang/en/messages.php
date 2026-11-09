<?php
$messages = [
    'meta_description' => 'Coast Run: a demonstration of event registration.',
    'hero_kicker' => 'PLATFORM DEMO · COAST', 'hero_title' => 'Run toward the sea.',
    'hero_text' => 'Two distances, one horizon. This fictional event demonstrates the demo platform.',
    'races_title' => 'Choose your race', 'race_link' => 'Explore race', 'register' => 'Register',
    'contact_title' => 'Contact', 'contact_text' => 'This event is a demo. Contact Produce a Value for project information.',
    'privacy_demo_text' => 'This is a demonstration website. Do not enter real personal information; use fictional details and a Stripe test card only.',
    'reg_intro' => 'Enter fictional details to try the checkout.',
    'reg_form_title' => 'Participant details', 'reg_base_price' => 'Base fee', 'reg_total_price' => 'Total',
    'reg_discount_code' => 'Discount code (optional)', 'reg_payment' => 'Payment', 'reg_stripe' => 'Test card',
    'reg_privacy' => 'I have read the privacy notice.', 'footer_privacy' => 'Privacy', 'reg_pay' => 'Continue to payment',
    'reg_closed_button' => 'Registration closed', 'demo_data_notice' => 'Use fictional details only. No real charge or email will be sent.',
    'test_mode_label' => 'Demo · test payment',
    'form_labels' => ['first_name' => 'First name', 'last_name' => 'Last name', 'email' => 'Email', 'dob' => 'Date of birth', 'phone' => 'Phone'],
    'checkout_order' => 'Order', 'checkout_payment_title' => 'Test payment',
    'checkout_payment_intro' => 'Use a Stripe test card. No real charge will be made.',
    'checkout_participants' => 'Participants', 'checkout_total' => 'Total', 'checkout_pay' => 'Pay',
    'checkout_back' => 'Back to registration', 'checkout_error' => 'Payment failed.',
    'checkout_pending' => 'Payment processing.', 'checkout_success' => 'Registration confirmed',
    'test_success' => 'Demo registration confirmed', 'checkout_thanks' => 'Thanks for trying the demo.',
    'checkout_review_title' => 'Payment received', 'checkout_review_text' => 'Registration awaiting review.',
    'checkout_review_status' => 'Under review', 'checkout_ticket_view' => 'View ticket',
    'checkout_tickets' => 'Ticket', 'checkout_cancel' => 'Payment not completed',
    'checkout_cancel_text' => 'You can retry the test payment.', 'checkout_retry' => 'Retry',
    'races' => [
        '42' => ['distance' => '42', 'name' => 'Marathon 42 km', 'eyebrow' => 'The classic distance', 'line' => 'The seafront is your finish line.', 'description' => 'A symbolic course between city and coast. Fictional event for demonstration.', 'place' => 'Coast'],
        '10' => ['distance' => '10', 'name' => 'Run 10 km', 'eyebrow' => 'Start light', 'line' => 'Ten kilometres of energy.', 'description' => 'An accessible challenge to experience race day. Fictional event for demonstration.', 'place' => 'Coast'],
    ],
];

return array_replace($messages, json_decode('{"hero_kicker": "CEREA · VERONA · RUNNING BY NIGHT", "hero_title": "The city lights up. You run.", "hero_text": "Five kilometres to make your own. A city circuit, an evening run and a celebration that carries on beyond the finish line.", "races_title": "The race", "race_link": "Explore the 5K", "meta_description": "Cerea Night Run: 5 km in Cerea. Race information and registration.", "contact_text": "ASD Atletica Cerea · Via Monti Lessini, 5 · 37053 Cerea (VR), Italy", "next": "Next edition", "pending": "Date and times to be confirmed", "edition": "2026 edition reference", "intro": "One evening. Five kilometres. All of Cerea.", "description": "The latest published edition, on 6 June 2026, featured a FIDAL certified 5 km city course closed to traffic, as part of Verona con la corsa. Details for the next event will be confirmed by the organisers.", "where": "The meeting point", "place": "Pelaloca sports ground", "place_text": "Via Monti Lessini, 5 · Cerea (VR), Italy. Venue shown on the 2026 poster; subject to confirmation for the next edition.", "night": "Stay after the finish.", "night_text": "The 2026 event continued with live music and a DJ set. An evening to share, even after the clock stops.", "program": "Schedule and information", "program_text": "The next event date, start time, bib collection and entry deadline have not been confirmed. Find the official reference for the latest edition here.", "poster": "Official 2026 poster", "rules": "2026 race rules", "source": "Official website", "test_mode_label": "Independent preview · test entries and payments", "reg_intro": "Try registration using fictional details. Illustrative €15 fee, not an official price. This preview accepts adults only; sporting eligibility and categories for the next event will be agreed with the organisers.", "price_note": "Illustrative fee, not an official price", "footer_note": "Demo proposal for ASD Atletica Cerea", "services": "2026 edition facilities", "services_text": "Bag storage, changing rooms, showers and refreshments are listed on the official poster. Availability for the next edition is to be confirmed."}', true), [
'races' => ['5' => ['distance' => '5', 'name' => 'Cerea Night Run · 5 km', 'eyebrow' => 'CITY RUN / 5K', 'line' => 'Your challenge after sunset.', 'description' => 'A 5 km city circuit. Next edition course and certification to be confirmed.', 'place' => 'Cerea (VR)']],
]);
