<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    | Google offers access via OAuth client IDs or service accounts.
    | For more information see: https://developers.google.com/identity/protocols/OAuth2
    |
    | Supported: "oauth", "oauth_json", "service_account",
    */
    'auth_type'                 => env('GOOGLE_ANALYTICS_AUTH_TYPE', 'oauth'),

    /*
    |--------------------------------------------------------------------------
    | Application Credentials
    |--------------------------------------------------------------------------
    |
    | https://developers.google.com/api-client-library/php/auth/service-accounts#creatinganaccount
    */
    'connections'               => [
        'oauth' => [
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        ],

        'oauth_json' => [
            'auth_config' => storage_path('app/analytics/oauth-account-credentials.json'),
        ],

        'service_account' => [
            'application_credentials' => storage_path('app/analytics/service-account-credentials.json'),
        ],
    ],

    /*
     * The view id of which you want to display data.
     */
    'view_id'                   => env('GOOGLE_ANALYTICS_VIEW_ID'),

    /*
     * The amount of minutes the Google API responses will be cached.
     * If you set this to zero, the responses won't be cached at all.
     */
    'cache_lifetime_in_minutes' => 60 * 24,

    /*
     * Here you may configure the "store" that the underlying Google_Client will
     * use to store it's data.  You may also add extra parameters that will
     * be passed on setCacheConfig (see docs for google-api-php-client).
     *
     * Optional parameters: "lifetime", "prefix"
     */
    'cache'                     => [
        'store' => 'file',
    ],
];
