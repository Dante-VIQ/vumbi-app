<?php

return [
    'enabled' => env('BONUSARRIVE_ENABLED', true),

    'api_key' => env('BONUSARRIVE_API_KEY'),

    'm_id'    => env('BONUSARRIVE_M_ID', 3448),   // Important: Your merchant ID

    'default_limit' => env('BONUSARRIVE_DEFAULT_LIMIT', 8),

    'cache_ttl'     => env('BONUSARRIVE_CACHE_TTL', 14400), // 4 hours
];