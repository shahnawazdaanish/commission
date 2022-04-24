<?php

return [
    'base_currency' => 'EUR',
    'cloud_currency_rates' => false,
    'paysera_exchange_api_url' => "https://developers.paysera.com/tasks/api/currency-exchange-rates",
    'offline_exchange_rates' => [
        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53
    ],
    'commissions' => [
        'private' => [
            'deposit' => 0.03,
            'withdraw' => [
                'charge' => 0.3,
                'history' => 'W', // php datetime format (W for weekly, d for daily)
                'max_waiver_amount' => 1000,
                'waiver_currency' => 'EUR',
                'waiver_count' => 3
            ]
        ],
        'business' => [
            'deposit' => 0.03,
            'withdraw' => 0.5
        ]
    ]
];