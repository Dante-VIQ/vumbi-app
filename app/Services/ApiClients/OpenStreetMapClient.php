<?php

namespace App\Services\ApiClients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OpenStreetMapClient
{
    private const BASE_URL = 'https://nominatim.openstreetmap.org/search';

    /**
     * Geocode a city or location
     */
    public function geocode(string $query): ?array
    {
        $query = trim($query);
        if (empty($query)) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => config('app.name') . '/1.0 (contact@yourdomain.com)'
                ])
                ->get(self::BASE_URL, [
                    'q'        => $query,
                    'format'   => 'json',
                    'limit'    => 1,
                    'addressdetails' => 1,
                ]);

            if (!$response->successful()) {
                Log::warning("Nominatim API failed", [
                    'query' => $query,
                    'status' => $response->status()
                ]);
                return null;
            }

            $data = $response->json();

            return $data[0] ?? null;

        } catch (Exception $e) {
            Log::error("OpenStreetMapClient geocode failed", [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}