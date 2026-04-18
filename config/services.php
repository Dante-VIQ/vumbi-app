<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

'travelpayouts' => [
    'token'  => env('TRAVELPAYOUTS_TOKEN'),
    'marker' => env('TRAVELPAYOUTS_MARKER'),
],

        'awin' => [
        'api_key' => env('AWIN_API_KEY'),
        'publisher_id' => env('AWIN_PUBLISHER_ID'),
    'cache_ttl' => env('AWIN_CACHE_TTL', 21600),
    ],

    'bonusarrive' => [
        'api_key' => env('BONUSARRIVE_API_KEY'),
        'affiliate_id' => env('BONUSARRIVE_AFFILIATE_ID'),
        'cache_ttl' => env('BONUSARRIVE_CACHE_TTL', 14400),

    ],
];
