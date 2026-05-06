<?php

namespace App\Services\TravelPayouts;

use Illuminate\Support\Facades\Http;

class HotelService
{
    public function searchHotels(string $city): array
    {
        $response = Http::get('https://engine.hotellook.com/api/v2/cache.json', [
            'location' => $city,
            'currency' => 'KES',
            'limit' => 6
        ]);

        if (!$response->successful()) {
            return [];
        }

        return $response->json();
    }
}