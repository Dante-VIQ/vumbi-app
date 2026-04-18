<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Awin Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('AWIN_ENABLED', true),

    'api_key' => env('AWIN_API_KEY'),

    'publisher_id' => env('AWIN_PUBLISHER_ID'),

    'account_id' => env('AWIN_ACCOUNT_ID'),

    // Default limit for API calls
    'default_limit' => env('AWIN_DEFAULT_LIMIT', 10),

    // Cache time in seconds (6 hours default)
    'cache_ttl' => env('AWIN_CACHE_TTL', 21600),
];