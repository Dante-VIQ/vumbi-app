<?php

namespace App\Services\ApiClients;

use Illuminate\Support\Facades\Http;

class TravelPayoutsClient
{
    public function getHotels(string $location): array
    {
        $response = Http::get('https://engine.hotellook.com/api/v2/cache.json', [
            'location' => $location,
            'currency' => 'USD',
            'limit' => 20
        ]);

        return $response->successful()
            ? $response->json()
            : [];
    }
}