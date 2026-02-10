<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ThingsBoard Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk koneksi ke ThingsBoard platform
    |
    */

    'server' => env('THINGSBOARD_HOST', 'https://demo.thingsboard.io'),
    'access_token' => env('THINGSBOARD_ACCESS_TOKEN'),
    'device_id' => env('THINGSBOARD_DEVICE_ID', '091334f0-a73e-11f0-8c95-7536037a85df'),
    
    // Legacy JWT config (commented out)
    // 'username' => env('THINGSBOARD_USERNAME', 'tenant@thingsboard.org'),
    // 'password' => env('THINGSBOARD_PASSWORD', 'tenant'),
    
    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    
    'endpoints' => [
        'telemetry' => '/api/v1/{accessToken}/telemetry',
        'device' => '/api/device',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    */
    
    'timeout' => env('THINGSBOARD_TIMEOUT', 30),
    
    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    */
    
    'retry_attempts' => env('THINGSBOARD_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('THINGSBOARD_RETRY_DELAY', 1000), // milliseconds
];
