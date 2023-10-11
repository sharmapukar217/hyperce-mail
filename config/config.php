<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locale
    |--------------------------------------------------------------------------
    |
    | This array holds the list of supported locale for application.
    |
    */
    'locale' => [
        'supported' => [
            'en' => ['name' => 'English', 'native' => 'English']
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Settings
    |--------------------------------------------------------------------------
    |
    | Configure the authentication functionality.
    |
    */
    'auth' => [
        'register' => env('ALLOW_REGISTER', true),
        'password_reset' => env('ALLOW_PASSWORD_RESET', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Throttle Settings
    |--------------------------------------------------------------------------
    |
    | Configure the API throttling.
    | For more information see https://laravel.com/docs/master/routing#rate-limiting
    |
    */
    'throttle_middleware' => 'throttle:' . env('THROTTLE_MIDDLEWARE', '60,1'),
];