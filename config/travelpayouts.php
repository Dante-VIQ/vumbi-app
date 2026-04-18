<?php

return [
    'token'   => env('TRAVELPAYOUTS_TOKEN'),
    'marker'  => env('TRAVELPAYOUTS_MARKER'),
    'base_url' => 'https://api.travelpayouts.com', // or hotellook endpoint
];


// Temporary test in searchHotels method
dd([
    'token' => $this->token ? 'Present' : 'Missing',
    'marker' => $this->marker ? 'Present' : 'Missing',
    'response' => $response->status(),
    'body' => $response->json()
]);