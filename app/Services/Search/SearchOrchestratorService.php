<?php

namespace App\Services\Search;

use App\DataTransferObjects\SearchResult;
use App\Services\AI\AIContentService;
use App\Services\ApiClients\OpenStreetMapClient;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SearchOrchestratorService
{
    public function __construct(
        private readonly OpenStreetMapClient $osm,
        private readonly AIContentService $ai,
        private readonly QueryClassifierService $classifier,
        private readonly RouteDecisionService $router
    ) {}

    public function search(string $query): SearchResult
    {
        $query = $this->sanitizeQuery($query);

        if (empty($query)) {
            return $this->errorResult('Invalid search query');
        }

        $cacheKey = 'travel_search_' . md5(strtolower($query));

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($query) {
            return $this->performSearch($query);
        });
    }

    private function performSearch(string $query): SearchResult
    {
        try {
            // 1. Geocode (still sequential – but fast & necessary for places call)
            $location = $this->osm->geocode($query);
            if (!$location) {
                return $this->errorResult('City not found');
            }

            // 2. Parallel data fetching – the real power is here
            $results = $this->fetchDataInParallel($location, $query);

            // 3. AI description (cheap & fast – can be parallel, but keeping simple)
            $description = $this->ai->describeCity($query);

            // 4. Classification & routing
            $classification = $this->classifier->classify($query);
            $routing = $this->router->decide($classification);

            // 5. Quality assessment
            $dataQuality = $this->assessDataQuality($results, $description);

            return new SearchResult(
                success: true,
                city: ucwords($query),
                location: $location,
                description: $description,
                places: $results['places'],
                hotels: $results['hotels'],
                flights: $results['flights'],
                dataQuality: $dataQuality,
                meta: [
                    'classification' => $classification,
                    'routing' => $routing,
                    'cached_at' => now()->toIso8601String(),
                ]
            );

        } catch (Exception $e) {
            Log::error('SearchOrchestrator failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            return $this->errorResult('Search failed. Please try again.');
        }
    }

    /**
     * Fire all external HTTP calls at the same time.
     */
    private function fetchDataInParallel(array $location, string $query): array
    {
        // All calls are dispatched simultaneously via Http::pool
        $responses = Http::pool(fn (Pool $pool) => [
            // 1. Places (OpenTripMap)
            $pool->as('places')->get('https://api.opentripmap.com/0.1/en/places/radius', [
                'lat'    => $location['lat'],
                'lon'    => $location['lon'],
                'radius' => 5000,
                'limit'  => 15,
                'rate'   => 2,
                'apikey' => config('services.opentripmap.key'),
            ]),

            // 2. Hotels (HotelLook)
            $pool->as('hotels')->get('https://engine.hotellook.com/api/v2/cache.json', [
                'location' => $query,
                'currency' => 'KES',
                'limit'    => 10,
                'lang'     => 'en',
            ]),

            // 3. Flights – TravelPayouts
            $pool->as('flights_tp')->get('https://api.travelpayouts.com/aviasales/v3/prices_for_dates', [
                'origin'      => 'NBO',
                'destination' => strtoupper(substr($query, 0, 3)),
                'currency'    => 'KES',
                'limit'       => 10,
            ]),

            // 4. Flights – BonusArrive
            $pool->as('flights_ba')
                ->withHeaders([
                    'Content-Type'  => 'application/json;charset=utf-8',
                    'Authorization' => 'Bearer ' . config('services.bonusarrive.api_key'),
                ])
                ->post('https://www.bonusarrive.com/slapi/service/advertisers', [
                    'per_page' => 10,
                    'page'     => 1,
                    'keyword'  => $query,
                    'm_id'     => config('services.bonusarrive.m_id', 11167),
                ]),
        ]);

        // --- Process results with graceful fallbacks ---

        // Places
        $places = [];
        if ($responses['places']->ok()) {
            $places = $responses['places']->json()['features'] ?? [];
        }

        // Hotels
        $hotels = [];
        if ($responses['hotels']->ok()) {
            $hotels = $responses['hotels']->json() ?? [];
        }

        // Flights – combined from both sources
        $flights = [];
        if ($responses['flights_tp']->ok()) {
            $flights = array_merge($flights, $this->normalizeFlightsTravelPayouts(
                $responses['flights_tp']->json() ?? []
            ));
        }
        if ($responses['flights_ba']->ok()) {
            $flights = array_merge($flights, $this->normalizeFlightsBonusArrive(
                $responses['flights_ba']->json() ?? []
            ));
        }

        // Sort flights by price (cheapest first)
        usort($flights, function ($a, $b) {
            return ($a['price_raw'] ?? 999999) <=> ($b['price_raw'] ?? 999999);
        });

        return [
            'places'  => $places,
            'hotels'  => $hotels,
            'flights' => $flights,
        ];
    }

    // --- Lightweight flight normalisers (moved here for performance) ---

    private function normalizeFlightsTravelPayouts(array $flights): array
    {
        return array_map(function ($f) {
            return [
                'airline'   => $f['airline'] ?? 'Unknown',
                'from'      => $f['origin'] ?? 'NBO',
                'to'        => $f['destination'] ?? 'Destination',
                'price'     => isset($f['price']) ? 'KES ' . number_format($f['price']) : 'Best Price',
                'price_raw' => (float) ($f['price'] ?? 0),
                'link'      => $f['booking_url'] ?? '#',
                'source'    => 'TravelPayouts',
            ];
        }, $flights);
    }

    private function normalizeFlightsBonusArrive(array $flights): array
    {
        return array_map(function ($f) {
            $price = (float) ($f['price'] ?? 0);
            return [
                'airline'   => $f['airline'] ?? $f['title'] ?? 'Bonus Arrive Deal',
                'from'      => $f['departure'] ?? 'NBO',
                'to'        => $f['arrival'] ?? 'Destination',
                'price'     => $price > 0 ? 'USD ' . number_format($price, 2) : 'Best Price',
                'price_raw' => $price,
                'link'      => $f['url'] ?? $f['booking_url'] ?? '#',
                'source'    => 'Bonus Arrive',
            ];
        }, $flights);
    }

    // Quality assessment remains the same
    private function assessDataQuality(array $results, string $description): array
    {
        $hasPlaces = count($results['places'] ?? []) >= 6;
        $hasHotels = count($results['hotels'] ?? []) >= 4;
        $hasFlights = count($results['flights'] ?? []) >= 3;
        $hasDescription = strlen($description) > 80;

        return [
            'can_build'     => $hasPlaces && $hasHotels && $hasDescription,
            'places_count'  => count($results['places'] ?? []),
            'hotels_count'  => count($results['hotels'] ?? []),
            'flights_count' => count($results['flights'] ?? []),
            'has_description' => $hasDescription,
            'quality_score' => ($hasPlaces ? 40 : 0) + ($hasHotels ? 35 : 0) + ($hasDescription ? 25 : 0),
        ];
    }

    private function sanitizeQuery(string $query): string
    {
        return trim(strip_tags($query));
    }

    private function errorResult(string $message): SearchResult
    {
        return new SearchResult(
            success: false,
            city: '',
            error: $message
        );
    }
}