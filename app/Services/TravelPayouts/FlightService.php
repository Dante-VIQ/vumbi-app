<?php

namespace App\Services\TravelPayouts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FlightService
{
    private const BASE_URL = 'https://api.travelpayouts.com/aviasales/v3/prices_for_dates';

    /**
     * Popular international origins for tourists flying to Nairobi.
     * Ordered by search volume and conversion potential.
     */
    private const DEFAULT_ORIGINS = [
        // USA
        'JFK', 'IAD', 'ORD', 'LAX', 'SFO', 'DFW', 'BOS', 'ATL', 'SEA',
        // UK
        'LHR', 'LGW', 'MAN',
        // Europe
        'AMS', 'CDG', 'FRA',
        // Middle East
        'DXB', 'DOH', 'IST',
        // Asia
        'DEL', 'BOM', 'SIN',
        // Australia
        'SYD', 'MEL', 'BNE', 'PER',
    ];

    /**
     * Search flights TO Nairobi from popular international origins.
     *
     * @param string $destination  Ignored — kept for backward compatibility. Always uses NBO.
     * @param int    $limit        Max results per origin
     * @param array  $origins      Optional custom origin list
     * @return array
     */
    public function searchFlights(string $destination = 'NBO', int $limit = 5, array $origins = []): array
    {
        $token = config('services.travelpayouts.token') ?? env('TRAVELPAYOUTS_TOKEN');
        if (empty($token)) {
            Log::error("TravelPayouts API Token is missing");
            return [];
        }

        // Always search TO Nairobi (NBO)
        $destinationCode = 'NBO';

        // Use provided origins or defaults
        $originsToSearch = !empty($origins) ? $origins : self::DEFAULT_ORIGINS;

        // Default departure: 30 days from now
        $departDate = now()->addDays(30)->format('Y-m-d');

        $allFlights = [];

        foreach ($originsToSearch as $origin) {
            $cacheKey = "flights_to_nbo_{$origin}_{$departDate}_{$limit}";

            // Cache for 6 hours to avoid rate limits
            $flights = Cache::remember($cacheKey, now()->addHours(6), function () use ($origin, $destinationCode, $limit, $token, $departDate) {
                try {
                    $response = Http::timeout(12)->get(self::BASE_URL, [
                        'origin'      => strtoupper($origin),
                        'destination' => $destinationCode,
                        'currency'    => 'USD',
                        'limit'       => min($limit, 10),
                        'token'       => $token,
                        'depart_date' => $departDate,   // ← REQUIRED for meaningful results
                        'one_way'     => 'true',
                    ]);

                    if (!$response->successful()) {
                        Log::warning("Flight search failed", [
                            'origin'      => $origin,
                            'destination' => $destinationCode,
                            'status'      => $response->status(),
                        ]);
                        return [];
                    }

                    $data = $response->json();
                    return $data['data'] ?? [];

                } catch (\Exception $e) {
                    Log::error("FlightService exception", [
                        'origin' => $origin,
                        'error'  => $e->getMessage(),
                    ]);
                    return [];
                }
            });

            // Normalize each result
            foreach ($flights as $flight) {
                $flight['origin'] = $origin;
                $allFlights[] = $flight;
            }
        }

        // Sort by price ascending
        usort($allFlights, fn($a, $b) => ($a['price'] ?? 999999) <=> ($b['price'] ?? 999999));

        return $allFlights;
    }

    /**
     * Search flights from one specific origin to Nairobi.
     * Use this for targeted content like "Flights from London to Nairobi".
     */
    public function searchFromOrigin(string $origin, int $limit = 5): array
    {
        return $this->searchFlights('NBO', $limit, [$origin]);
    }

    /**
     * Get the cheapest flight from each origin.
     * Useful for the brief generator.
     */
    public function getCheapestFromEachOrigin(int $perOrigin = 1): array
    {
        $flights = $this->searchFlights('NBO', $perOrigin);
        
        $cheapest = [];
        foreach ($flights as $flight) {
            $origin = $flight['origin'] ?? 'unknown';
            if (!isset($cheapest[$origin]) || $flight['price'] < $cheapest[$origin]['price']) {
                $cheapest[$origin] = $flight;
            }
        }
        
        return array_values($cheapest);
    }
}