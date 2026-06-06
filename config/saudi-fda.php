<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SFDA Environment
    |--------------------------------------------------------------------------
    |
    | Choose which SFDA environment to connect to: 'sandbox' or 'production'.
    | In sandbox mode, all API calls are free and safe for testing.
    |
    */
    'environment' => env('SFDA_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | Your SFDA API credentials. You can obtain these from the SFDA developer
    | portal after registering your application.
    |
    | SFDA_CONSUMER_KEY    = Client Identifier (Consumer Key)
    | SFDA_CONSUMER_SECRET = Client Secret (Consumer Secret)
    |
    | These are used for OAuth2 authentication (client_credentials grant).
    | The package handles token caching and automatic refresh for you.
    |
    */
    'credentials' => [
        'consumer_key' => env('SFDA_CONSUMER_KEY', ''),
        'consumer_secret' => env('SFDA_CONSUMER_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Cache
    |--------------------------------------------------------------------------
    |
    | Settings for caching the SFDA access token. The token is valid for
    | 24 hours (86400 seconds). The package caches it to avoid requesting
    | a new token on every API call.
    |
    | The cache TTL is automatically set to (token_expires_in - 300 seconds)
    | to ensure a safety margin before expiry.
    |
    */
    'token_cache' => [
        'enabled' => env('SFDA_TOKEN_CACHE_ENABLED', true),
        'store' => env('SFDA_TOKEN_CACHE_STORE', 'file'),
        'key' => env('SFDA_TOKEN_CACHE_KEY', 'sfda_access_token'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | Base URLs for all SFDA API services. These point to the sandbox
    | environment by default. In production, the URLs will be different.
    |
    | You may override individual URLs if SFDA changes their endpoints.
    |
    */
    'api' => [

        'timeout' => env('SFDA_API_TIMEOUT', 60),

        'retry_on_401' => env('SFDA_API_RETRY_ON_401', 1),

        'oauth' => [
            'base' => env('SFDA_OAUTH_BASE', 'https://apis.sfda.gov.sa:9002/v2/oauth'),
        ],

        'cosmetics' => [
            'base' => env('SFDA_COSMETICS_BASE', 'https://apis.sfda.gov.sa:9002/v2'),
        ],

        'drugs' => [
            'base' => env('SFDA_DRUGS_BASE', 'https://apis.sfda.gov.sa:9002/v2'),
        ],

        'food' => [
            'base' => env('SFDA_FOOD_BASE', 'https://apis.sfda.gov.sa:9002/v2'),
        ],

        'medical_devices' => [
            'base' => env('SFDA_MEDICAL_DEVICES_BASE', 'https://apis.sfda.gov.sa:9002/v2'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Controls SFDA API request/response logging.
    | When enabled, sensitive data (credentials, tokens) is masked.
    |
    */
    'logging' => [
        'enabled' => env('SFDA_LOGGING_ENABLED', true),
        'channel' => env('SFDA_LOG_CHANNEL', 'stack'),
        'level' => env('SFDA_LOG_LEVEL', 'info'),

        /*
        |--------------------------------------------------------------------------
        | Database Logging
        |--------------------------------------------------------------------------
        |
        | When enabled, every API request/response is stored in the
        | `sfda_api_logs` table for auditing and debugging.
        |
        | Run `php artisan vendor:publish --tag=saudi-fda-migrations`
        | then `php artisan migrate` to create the required table.
        |
        */
        'database' => [
            'enabled' => env('SFDA_LOG_DATABASE_ENABLED', false),
            'connection' => env('SFDA_LOG_DATABASE_CONNECTION'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Routes
    |--------------------------------------------------------------------------
    |
    | Whether to register the built-in API routes (GET /api/saudi-fda/...).
    | If you prefer to define your own routes, set this to false.
    |
    */
    'routes' => [
        'enabled' => env('SFDA_ROUTES_ENABLED', true),
        'prefix' => env('SFDA_ROUTES_PREFIX', 'api/saudi-fda'),
        'middleware' => env('SFDA_ROUTES_MIDDLEWARE', 'api'),
    ],

];
