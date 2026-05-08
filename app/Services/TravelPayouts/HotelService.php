<?php

namespace App\Services\TravelPayouts;

use App\Models\City;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelService
{
    private const BASE_URL = 'https://engine.hotellook.com/api/v2/cache.json';

    public function searchHotels(string $city, int $limit = 8, string $currency = 'KES'): array
    {
        $city = trim($city);
        if (empty($city)) {
            return [];
        }

        try {
            $response = Http::timeout(10)
                ->get(self::BASE_URL, [
                    'location' => $city,
                    'currency' => strtoupper($currency),
                    'limit'    => min($limit, 15),
                    'lang'     => 'en',
                ]);

            if (!$response->successful()) {
                Log::warning("Hotel API request failed", [
                    'city'   => $city,
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
                return [];
            }

            return $response->json() ?? [];

        } catch (Exception $e) {
            Log::error("HotelService failed", [
                'city' => $city,
                'error' => $e->getMessage()
            ]);
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

        Log::info("Found hotels for city", [
            'city' => $city->name,
            'count' => count($hotels)
        ]);

        // You can add saving logic here later
    }
}
