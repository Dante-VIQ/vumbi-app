<?php

namespace App\Services\TravelPayouts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class HotelService
{
    private const BASE_URL = 'https://engine.hotellook.com/api/v2/cache.json';

    public function searchHotels(string $city, int $limit = 8, string $currency = 'KES'): array
    {
        if (empty(trim($city))) {
            return [];
        }

        try {
            $response = Http::timeout(15)
                ->get(self::BASE_URL, [
                    'location' => trim($city),
                    'currency' => strtoupper($currency),
                    'limit'    => min($limit, 20),   // reasonable cap
                    'lang'     => 'en',
                ]);

            if (!$response->successful()) {
                Log::warning("Hotel API request failed", [
                    'city' => $city,
                    'status' => $response->status()
                ]);
                return [];
            }

            $data = $response->json();

            // Optional: Normalize structure here if needed
            return is_array($data) ? $data : [];

        } catch (Exception $e) {
            Log::error("HotelService search failed", [
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