<?php

namespace App\Services\TravelPayouts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlightService
{
    private const BASE_URL = 'https://api.travelpayouts.com/aviasales/v3/prices_for_dates';

    /**
     * Search cheapest flights from origin to destination.
     *
     * @param string $destination City name or airport code
     * @param string $origin      Departure airport code (default NBO)
     * @param int    $limit       Max number of results
     * @return array
     */
    public function searchFlights(string $destination, string $origin = 'NBO', int $limit = 10): array
    {
        $destination = trim($destination);
        if (empty($destination)) {
            return [];
        }

        $token = config('services.travelpayouts.token') ?? env('TRAVELPAYOUTS_TOKEN');
        if (empty($token)) {
            Log::error("TravelPayouts API Token is missing");
            return [];
        }

        // Convert city name → IATA code
        $destinationCode = $this->getAirportCode($destination);
        if (empty($destinationCode)) {
            Log::warning("No airport code found for destination", ['destination' => $destination]);
            return [];
        }

        try {
            $response = Http::timeout(12)->get(self::BASE_URL, [
                'origin'      => strtoupper($origin),
                'destination' => $destinationCode,
                'currency'    => 'KES',
                'limit'       => min($limit, 15),
                'token'       => $token,
            ]);

            if (!$response->successful()) {
                Log::warning("TravelPayouts Flight API failed", [
                    'destination' => $destination,
                    'code'        => $destinationCode,
                    'status'      => $response->status(),
                    'body'        => $response->body(),
                ]);
                return [];
            }

            $data = $response->json();
            return $data['data'] ?? $data ?? [];

        } catch (\Exception $e) {
            Log::error("FlightService exception", [
                'destination' => $destination,
                'error'       => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Resolve a city name to its primary IATA airport code.
     * Uses a static map for Kenyan destinations, with a fallback
     * to coordinate‑based airport search (if available).
     */
    private function getAirportCode(string $cityOrCode): ?string
    {
        $normalized = strtolower(trim($cityOrCode));

        // Static mapping for Kenyan cities (extend as needed)
        $map = [
            'nairobi'       => 'NBO',
            'mombasa'       => 'MBA',
            'maasai mara'   => 'MRE',   // Mara Serena airstrip, typically used
            'kisumu'        => 'KIS',
            'eldoret'       => 'EDL',
            'malindi'       => 'MYD',
            'lamu'          => 'LAU',
            'diani'         => 'UKA',   // Ukunda airstrip (Diani Beach)
            'watamu'        => 'MYD',   // Malindi is closest
        ];

        if (isset($map[$normalized])) {
            return $map[$normalized];
        }

        // If already a 3-letter uppercase code, use it directly
        if (strlen($cityOrCode) === 3 && ctype_upper($cityOrCode)) {
            return $cityOrCode;
        }

        // Fallback: try coordinate‑based airport lookup (requires geocoding)
        // This will be called from the orchestrator with coordinates if available.
        // For now, return null to avoid sending a bad code.
        return null;
    }

    /**
     * Coordinate‑based airport search (optional, call from orchestrator if needed).
     * This uses TravelPayouts' nearest_airports endpoint.
     */
    public function findNearestAirport(float $lat, float $lon): ?string
    {
        $token = config('services.travelpayouts.token') ?? env('TRAVELPAYOUTS_TOKEN');
        if (!$token) return null;

        try {
            $response = Http::timeout(10)->get('https://api.travelpayouts.com/aviasales_direct/api/search/nearest', [
                'lat'   => $lat,
                'lng'   => $lon,
                'token' => $token,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Return the code of the nearest airport
                return $data['data'][0]['code'] ?? null;
            }
        } catch (\Exception $e) {
            Log::warning('Failed to find nearest airport', ['error' => $e->getMessage()]);
        }

        return null;
    }
}