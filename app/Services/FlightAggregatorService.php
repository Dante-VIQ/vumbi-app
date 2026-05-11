<?php

namespace App\Services;

use App\Services\TravelPayouts\FlightService;
use App\Services\BonusArriveService;
use Illuminate\Support\Facades\Log;

class FlightAggregatorService
{
    public function __construct(
        private readonly FlightService $travelPayoutsFlight,
        private readonly BonusArriveService $bonusArrive
    ) {}

    /**
     * Aggregate flights from multiple providers
     */
    public function searchFlights(string $destination, int $limit = 10): array
    {
        $destination = trim($destination);
        if (empty($destination)) {
            return [];
        }

        $allFlights = [];

        // Run both providers (can be made parallel later)
        $flights1 = $this->travelPayoutsFlight->searchFlights($destination, limit: $limit);
        $flights2 = $this->bonusArrive->searchFlights($destination, limit: $limit);

        // Normalize and merge
        $allFlights = array_merge(
            $this->normalizeFlights($flights1, 'TravelPayouts'),
            $this->normalizeFlights($flights2, 'Bonus Arrive')
        );

        // Sort by price if possible
        usort($allFlights, function ($a, $b) {
            $priceA = $this->extractPrice($a['price'] ?? '');
            $priceB = $this->extractPrice($b['price'] ?? '');
            return $priceA <=> $priceB;
        });

        return collect($allFlights)->take($limit)->values()->all();
    }

    /**
     * Normalize flight data from different providers
     */
    private function normalizeFlights(array $flights, string $source): array
    {
        return collect($flights)->map(function ($flight) use ($source) {
            return [
                'id'          => $flight['id'] ?? uniqid(),
                'airline'     => $flight['airline'] ?? $flight['title'] ?? 'Unknown Airline',
                'flight_number' => $flight['flight_number'] ?? null,
                'from'        => $flight['from'] ?? $flight['departure'] ?? 'NBO',
                'to'          => $flight['to'] ?? $flight['arrival'] ?? 'Destination',
                'price'       => $flight['price'] ?? 'Best Price',
                'price_raw'   => $this->extractPrice($flight['price'] ?? ''),
                'link'        => $flight['link'] ?? $flight['url'] ?? '#',
                'departure_date' => $flight['departure_date'] ?? null,
                'description' => $flight['description'] ?? '',
                'image'       => $flight['image'] ?? null,
                'source'      => $source,
                'type'        => 'flight',
            ];
        })->all();
    }

    private function extractPrice($price): float
    {
        if (empty($price)) {
            return 999999;
        }

        // Remove currency symbols and commas
        $numeric = preg_replace('/[^0-9.]/', '', (string)$price);
        return is_numeric($numeric) ? (float)$numeric : 999999;
    }

    /**
     * Optional: Get only the cheapest flight
     */
    public function getCheapestFlight(string $destination): ?array
    {
        $flights = $this->searchFlights($destination, 5);
        return $flights[0] ?? null;
    }

    /**
 * Find the nearest IATA airport code for given coordinates.
 * Uses TravelPayouts' nearest airport endpoint if a token is available.
 */
public function findNearestAirport(float $lat, float $lon): ?string
{
    $token = config('services.travelpayouts.token') ?? env('TRAVELPAYOUTS_TOKEN');
    if (!$token) {
        return null; // fallback later
    }

    try {
        $response = Http::timeout(10)->get('https://api.travelpayouts.com/aviasales_direct/api/search/nearest', [
            'lat'   => $lat,
            'lng'   => $lon,
            'token' => $token,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // Return the code of the first airport
            return $data['data'][0]['code'] ?? null;
        }
    } catch (\Exception $e) {
        Log::warning("findNearestAirport failed: " . $e->getMessage());
    }

    return null;
}
}