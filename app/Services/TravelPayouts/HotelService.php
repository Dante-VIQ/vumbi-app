<?php

namespace App\Services\TravelPayouts;

use App\Models\City;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelService
{
    private const BASE_URL = 'https://engine.hotellook.com/api/v2/cache.json';

    public function searchHotels(string $city, int $limit = 8): array
    {
        try {
            $response = Http::timeout(10)->get('https://engine.hotellook.com/api/v2/cache.json', [
                'location' => $city,
                'currency' => 'USD',           // Try USD first
                'limit' => min($limit, 12),
                'lang' => 'en',
            ]);

            Log::info('Hotel API Response', [
                'city' => $city,
                'status' => $response->status(),
            ]);

            return $response->successful() ? $response->json() : [];
        } catch (Exception $e) {
            Log::error('HotelService failed', ['city' => $city, 'error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Used by the Build Job
     */
    public function build(City $city): void
    {
        // TODO: Implement logic to save hotels into database
        // For now, just search and log
        $hotels = $this->searchHotels($city->name);

        Log::info('Found hotels for city', [
            'city' => $city->name,
            'count' => count($hotels),
        ]);

        // You can add saving logic here later
    }
}
