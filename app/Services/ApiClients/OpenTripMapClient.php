<?php

namespace App\Services\ApiClients;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenTripMapClient
{
    private const BASE_URL = "https://api.opentripmap.com/0.1/en/places";

    /**
     * Get places within radius
     */
public function getPlaces(float $lat, float $lon, int $radius = 15000, int $limit = 20): array
{
    try {
        $response = Http::timeout(12)
            ->get("https://api.opentripmap.com/0.1/en/places/radius", [
                'lat'    => $lat,
                'lon'    => $lon,
                'radius' => $radius,
                'limit'  => $limit,
                'rate'   => 2,
                'apikey' => config('services.opentripmap.key'),
            ]);

        if ($response->status() === 401) {
            Log::error("OpenTripMap: Invalid or missing API key");
        }

        return $response->successful() 
            ? $response->json()['features'] ?? [] 
            : [];
    } catch (Exception $e) {
        Log::error("OpenTripMapClient failed", ['error' => $e->getMessage()]);
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