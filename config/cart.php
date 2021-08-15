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
        ],
    ],

];
