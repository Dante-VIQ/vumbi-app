<?php

namespace App\Services\ApiClients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OpenTripMapClient
{
    private const BASE_URL = "https://api.opentripmap.com/0.1/en/places";

    /**
     * Get places within radius
     */
    public function getPlaces(float $lat, float $lon, int $radius = 5000, int $limit = 20): array
    {
        if ($radius > 50000) {
            $radius = 50000; // safety limit
        }

        try {
            $response = Http::timeout(15)
                ->get(self::BASE_URL . "/radius", [
                    'lat'      => $lat,
                    'lon'      => $lon,
                    'radius'   => $radius,
                    'limit'    => $limit,
                    'rate'     => 2,                    // minimum rating
                    'apikey'   => config('services.opentripmap.key'),
                ]);

            if (!$response->successful()) {
                Log::warning("OpenTripMap API failed", [
                    'status' => $response->status(),
                    'lat' => $lat,
                    'lon' => $lon
                ]);
                return [];
            }

            return $response->json()['features'] ?? [];

        } catch (Exception $e) {
            Log::error("OpenTripMapClient::getPlaces failed", [
                'lat' => $lat,
                'lon' => $lon,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get detailed information about a specific place
     */
    public function getPlaceDetails(string $xid): array
    {
        try {
            $response = Http::timeout(10)
                ->get(self::BASE_URL . "/xid/{$xid}", [
                    'apikey' => config('services.opentripmap.key'),
                ]);

            return $response->successful()
                ? $response->json()
                : [];
        } catch (Exception $e) {
            Log::error("OpenTripMapClient::getPlaceDetails failed", [
                'xid' => $xid,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}