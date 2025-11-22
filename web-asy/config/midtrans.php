<?php

return [

    // Other services...

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false), // Default to false
        'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true), // Default to true
        'is_3ds' => env('MIDTRANS_IS_3DS', true), // Default to true
    ],

];

