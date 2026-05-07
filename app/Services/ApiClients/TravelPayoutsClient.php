<?php

namespace App\Services\ApiClients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TravelPayoutsClient
{
    private const HOTEL_BASE_URL = 'https://engine.hotellook.com/api/v2/cache.json';

    /**
     * Search hotels using HotelLook (TravelPayouts)
     */
    public function getHotels(string $location, int $limit = 12, string $currency = 'KES'): array
    {
        $location = trim($location);
        if (empty($location)) {
            return [];
        }

        try {
            $response = Http::timeout(12)
                ->get(self::HOTEL_BASE_URL, [
                    'location' => $location,
                    'currency' => strtoupper($currency),
                    'limit'    => min($limit, 30),
                    'lang'     => 'en',
                ]);

            if (!$response->successful()) {
                Log::warning("TravelPayouts Hotel API failed", [
                    'location' => $location,
                    'status' => $response->status()
                ]);
                return [];
            }

            return $response->json() ?? [];

        } catch (Exception $e) {
            Log::error("TravelPayoutsClient::getHotels failed", [
                'location' => $location,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}