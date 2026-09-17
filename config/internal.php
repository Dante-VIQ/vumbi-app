<?php

return [
    'api_key' => env('INTERNAL_API_KEY'),
    'allowed_ips' => array_filter(
        array_map('trim', explode(',', (string) env('INTERNAL_ALLOWED_IPS', '')))
    ),
];