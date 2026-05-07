<?php

namespace App\Services\TravelPayouts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FlightService
{
    private const BASE_URL = 'https://api.travelpayouts.com/aviasales/v3/prices_for_dates';

    public function searchFlights(string $destination, string $origin = 'NBO', int $limit = 10): array
    {
        $destination = trim($destination);
        if (empty($destination)) {
            return [];
        }

        try {
            $response = Http::timeout(12)
                ->get(self::BASE_URL, [
                    'origin'      => strtoupper($origin),
                    'destination' => $this->extractAirportCode($destination),
                    'currency'    => 'KES',
                    'limit'       => min($limit, 15),
                ]);

            if (!$response->successful()) {
                Log::warning("TravelPayouts Flight API failed", [
                    'destination' => $destination,
                    'status' => $response->status()
                ]);
                return [];
            }

            return $response->json() ?? [];

        } catch (Exception $e) {
            Log::error("FlightService::searchFlights failed", [
                'destination' => $destination,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    private function extractAirportCode(string $destination): string
    {
        // If user passed IATA code, use it. Otherwise take first 3 letters (basic fallback)
        if (strlen($destination) === 3 && ctype_upper($destination)) {
            return $destination;
        }

        return strtoupper(substr($destination, 0, 3));
    }
}