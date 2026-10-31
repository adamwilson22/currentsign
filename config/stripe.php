<?php

return [
    'secret' => env('STRIPE_SECRET_KEY'),
    // Publishable keys are public (also bundled in the mobile app). Fallback keeps Payment Sheet working
    // if production .env has not been updated yet.
    'publishable' => env(
        'STRIPE_PUBLISHABLE_KEY',
        'pk_test_51RMdKXRqfNhRksjr7ud8RzdzFAMINrTOZIIEMqpoO0nseih9nxcnZysCtfuHPhUM9EyzJKMK6RkDCKQ6VPfQcK8E0063EyJgY1'
    ),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'usd'),
    'trial_sends' => (int) env('STRIPE_TRIAL_SENDS', 5),

    /*
    | Optional Dashboard Price IDs. If empty, Checkout uses price_data.
    */
    'prices' => [
        'basic' => env('STRIPE_PRICE_BASIC'),
        'standard' => env('STRIPE_PRICE_STANDARD'),
        'premium' => env('STRIPE_PRICE_PREMIUM'),
    ],

    'plans' => [
        'basic' => [
            'id' => 'basic',
            'name' => 'Basic',
            'amount' => 1500, // cents
            'docs_per_month' => 10,
        ],
        'standard' => [
            'id' => 'standard',
            'name' => 'Standard',
            'amount' => 4900,
            'docs_per_month' => 50,
        ],
        'premium' => [
            'id' => 'premium',
            'name' => 'Premium',
            'amount' => 9900,
            'docs_per_month' => null, // unlimited
        ],
    ],
];
