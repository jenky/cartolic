<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cartolic Storage Driver
    |--------------------------------------------------------------------------
    |
    | This configuration options determines the storage driver that will
    | be used to store cart's data. In addition, you may set any
    | custom options as needed by the particular driver you choose.
    |
    */

    'driver' => env('CARTOLIC_DRIVER', 'session'),

    'storage' => [
        'session' => [
            'driver' => env('SESSION_DRIVER', 'file'),
            'storage_key' => 'cart',
        ],

        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'table' => 'carts',
            'guard' => 'web',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cartolic Currency
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default currency for your application, which
    | will be used by the the cart and items.
    |
    */

    'currency' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Currency Formatter
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default currency formatter for your
    | application, which will be used to format the money.
    |
    */

    'formatter' => \NumberFormatter::class,

    /*
    |--------------------------------------------------------------------------
    | Currency Locale
    |--------------------------------------------------------------------------
    |
    | The currency locale determines the default locale that will be used
    | by the money formatter.
    |
    */

    'locale' => 'en_US',
];
