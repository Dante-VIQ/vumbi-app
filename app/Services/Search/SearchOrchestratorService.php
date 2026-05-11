<?php

namespace App\Services\Search;

use App\DataTransferObjects\SearchResult;
use App\Services\AI\AIContentService;
use App\Services\ApiClients\OpenStreetMapClient;
use App\Services\ApiClients\OpenTripMapClient;
use App\Services\BonusArriveService;
use App\Services\FlightAggregatorService;
use App\Services\TravelPayouts\HotelService;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SearchOrchestratorService
{
    public function __construct(
        private readonly OpenStreetMapClient $osm,
        private readonly OpenTripMapClient $trip,
        private readonly HotelService $hotels,
        private readonly FlightAggregatorService $flights,
        private readonly BonusArriveService $bonusArrive,
        private readonly AIContentService $ai,
        private readonly QueryClassifierService $classifier,
        private readonly RouteDecisionService $router
    ) {}

    /**
     * Main entry point – sanitizes, caches, and orchestrates the search.
     */
    public function search(string $query): SearchResult
    {
        $query = $this->sanitizeQuery($query);

        if (empty($query)) {
            return $this->errorResult('Invalid search query');
        }

                // 1. Check database cache first (most persistent)
        $cached = $this->getFromDatabaseCache($query);
        if ($cached) {
            return $cached;
        }

        $cacheKey = 'travel_search_' . md5(strtolower($query));

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($query) {
            return $this->performSearch($query);
        });
    }

    /**
     * Execute the full search pipeline.
     */
    private function performSearch(string $query): SearchResult
    {
        try {
            // 1. Geocode
            $location = $this->osm->geocode($query);
            if (! $location) {
                return $this->errorResult('City not found. Please check the spelling.');
            }

            // 2. Fetch all external data (APIs + AI)
            $data = $this->fetchAllData($location, $query);

            // 3. Generate a short description (separate from the guided data)
            $description = $this->ai->describeCity($query);

            // 4. Classify / route the query (for analytics or future routing)
            $classification = $this->classifier->classify($query);
            $routing = $this->router->decide($classification);

            // 5. Assess overall data quality
            $dataQuality = $this->assessDataQuality($data, $description);

            // 6. Compose and return the final DTO
             $searchResult = new SearchResult(
                success: true,
                city: ucwords($query),
                location: $location,
                description: $description,
                places: $data['places'],
                hotels: $data['hotels'],
                flights: $data['flights'],
                affiliate_deals: $data['affiliate_deals'],
                cultural_info: $data['cultural_info'],
                educational_info: $data['educational_info'],
                best_time_to_visit: $data['best_time_to_visit'],
                visa_info: $data['visa_info'],
                nearby_destinations: $data['nearby_destinations'],
                weather: $data['weather'],
                dataQuality: $dataQuality,
                meta: [
                    'classification' => $classification,
                    'routing' => $routing,
                    'cached_at' => now()->toIso8601String(),
                ]
            );

            return $searchResult;

             $this->saveToDatabaseCache($query, $searchResult);

        } catch (Exception $e) {
            Log::error('SearchOrchestrator failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResult('Search failed. Please try again later.');
        }


    }

         private function getFromDatabaseCache(string $query): ?SearchResult
    {
        $hash = $this->queryHash($query);
        $row = DB::table('cached_searches')
            ->where('query_hash', $hash)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$row) {
            return null;
        }

        try {
            return $this->decompressResult($row->compressed_result);
        } catch (Exception $e) {
            // If decompression fails, remove the broken entry
            DB::table('cached_searches')->where('id', $row->id)->delete();
            return null;
        }
    }

        private function saveToDatabaseCache(string $query, SearchResult $result): void
    {
        $hash = $this->queryHash($query);
        $compressed = $this->compressResult($result);

        DB::table('cached_searches')->updateOrInsert(
            ['query_hash' => $hash],
            [
                'query' => $query,
                'compressed_result' => $compressed,
                'expires_at' => now()->addDays(30), // Keep for 30 days; adjust as needed
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    /**
 * Compress and serialize a SearchResult into a binary string.
 */
private function compressResult(SearchResult $result): string
{
    // serialize the DTO to a string, then compress with maximum level (9)
    return gzcompress(serialize($result), 9);
}

/**
 * Decompress and unserialize a binary string back into a SearchResult.
 */
private function decompressResult(string $compressed): SearchResult
{
    return unserialize(gzuncompress($compressed));
}
    private function queryHash(string $query): string
    {
        return hash('sha256', strtolower(trim($query)));
    }
    /**
     * Fetch all data sources in parallel-style calls, safely falling back on failure.
     */
    private function fetchAllData(array $location, string $query): array
    {
        $airportCode = $this->getDestinationAirport($query, $location);
        return [
            'location' => $location,

            // External APIs (use safeCall to return [])
            'places'=> $this->safeCall(
                fn() => $this->trip->getPlaces($location['lat'] ?? 0, $location['lon'] ?? 0, limit: 12)
            ),
'hotels' => $this->safeCall(
    fn() => $this->hotels->searchByCoordinates(
        $location['lat'], $location['lon'], 8
    )
),
'flights' => $this->safeCall(
    fn() => $this->flights->searchFlights($airportCode, 8)
),
'affiliate_deals' => $this->safeCall(
    fn() => $this->bonusArrive->searchDeals($query, 5)  // BonusArriveService
),
            // AI content – provide fallback shape ['content' => ''] to preserve structure
            'cultural_info' => $this->safeCall(
                fn() => $this->ai->generateCulturalInfo($query),
                ['content' => '']
            ),
            'educational_info' => $this->safeCall(
                fn() => $this->ai->generateEducationalInfo($query),
                ['content' => '']
            ),
            'best_time_to_visit' => $this->safeCall(
                fn() => $this->ai->generateBestTimeToVisit($query),
                ['content' => '']
            ),
            'visa_info' => $this->safeCall(
                fn() => $this->ai->generateVisaInfo($query),
                ['content' => '']
            ),

            // Nearby destinations and weather (now actually fetched)
            'nearby_destinations' => $this->safeCall(
                fn() => $this->getNearbyDestinations($location, $query)
            ),
            'weather' => $this->safeCall(
                fn() => $this->getWeatherData($location)
            ),
        ];
    }

    private function getDestinationAirport(string $city, array $location): string
{
    // Fallback: coordinate search
    $code = $this->flights->findNearestAirport($location['lat'], $location['lon']);
    if ($code) return $code;

    // Last resort – just return the city name (will likely fail, but we tried)
    return $city;
}
    /**
     * Execute a callable and return its result, or a default value on failure.
     */
    private function safeCall(callable $callable, mixed $default = []): mixed
    {
        try {
            $result = $callable();
            return $result ?? $default;
        } catch (Exception $e) {
            return $default;
        }
    }

    /**
     * Fetch nearby destinations using OpenTripMap radius search.
     */
    private function getNearbyDestinations(array $location, string $query): array
    {
        try {
            $apiKey = config('services.opentripmap.key') ?: env('OPENTRIPMAP_API_KEY', '');
            if (empty($apiKey)) {
                return [];
            }

            $response = Http::timeout(10)->get('https://api.opentripmap.com/0.1/en/places/radius', [
                'lat' => $location['lat'],
                'lon' => $location['lon'],
                'radius' => 80000,
                'limit' => 8,
                'apikey' => $apiKey,
            ]);

            if ($response->successful()) {
                $queryLower = trim(strtolower($query));
                return collect($response->json()['features'] ?? [])
                    ->filter(function ($item) use ($queryLower) {
                        $name = $item['properties']['name'] ?? '';
                        return strtolower(trim($name)) !== $queryLower;
                    })
                    ->take(6)
                    ->map(fn($item) => [
                        'name' => $item['properties']['name'] ?? 'Nearby City',
                        'distance_km' => round(($item['properties']['dist'] ?? 0) / 1000, 1),
                    ])
                    ->values()
                    ->all();
            }
        } catch (Exception $e) {
            Log::warning('Failed to fetch nearby destinations', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Fetch current weather from Open‑Meteo (free, no key required).
     */
    private function getWeatherData(array $location): array
    {
        try {
            $response = Http::timeout(8)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $location['lat'],
                'longitude' => $location['lon'],
                'current_weather' => true,
                'timezone' => 'auto',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'temperature' => $data['current_weather']['temperature'] ?? null,
                    'windspeed' => $data['current_weather']['windspeed'] ?? null,
                    'weathercode' => $data['current_weather']['weathercode'] ?? null,
                    'unit' => '°C',
                ];
            }
        } catch (Exception $e) {
            Log::warning('Weather fetch failed', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Compute a quality score and readiness indicators.
     */
    private function assessDataQuality(array $data, string $description): array
    {
        $hasPlaces      = count($data['places'] ?? []) >= 8;
        $hasHotels      = count($data['hotels'] ?? []) >= 5;
        $hasFlights     = count($data['flights'] ?? []) >= 4;
        $hasDeals       = count($data['affiliate_deals'] ?? []) >= 3;

        $hasCultural    = !empty($data['cultural_info']['content'] ?? '');
        $hasEducational = !empty($data['educational_info']['content'] ?? '');
        $hasBestTime    = !empty($data['best_time_to_visit']['content'] ?? '');
        $hasVisa        = !empty($data['visa_info']['content'] ?? '');
        $hasDescription = strlen(trim($description)) > 100;

        $score = 0;
        if ($hasPlaces)      $score += 22;
        if ($hasHotels)      $score += 18;
        if ($hasFlights)     $score += 15;
        if ($hasDeals)       $score += 12;
        if ($hasCultural)    $score += 10;
        if ($hasEducational) $score += 8;
        if ($hasBestTime)    $score += 8;
        if ($hasVisa)        $score += 7;
        if ($hasDescription) $score += 5;

        $canBuild = $hasPlaces && $hasHotels && $hasDescription;

        return [
            'can_build'               => $canBuild,
            'quality_score'           => min(100, $score),
            'places_count'            => count($data['places'] ?? []),
            'hotels_count'            => count($data['hotels'] ?? []),
            'flights_count'           => count($data['flights'] ?? []),
            'deals_count'             => count($data['affiliate_deals'] ?? []),
            'has_cultural'            => $hasCultural,
            'has_educational'         => $hasEducational,
            'has_best_time'           => $hasBestTime,
            'has_visa_info'           => $hasVisa,
            'has_description'         => $hasDescription,
            'overall_readiness'       => $score >= 78 ? 'high' : ($score >= 55 ? 'medium' : 'low'),
            'recommended_for_build'   => $canBuild && $score >= 65,
        ];
    }

    /**
     * Clean the search query.
     */
    private function sanitizeQuery(string $query): string
    {
        return trim(strip_tags($query));
    }

    /**
     * Build a consistent error SearchResult DTO.
     */
    private function errorResult(string $message): SearchResult
    {
        return new SearchResult(
            success: false,
            city: '',
            location: [],
            description: '',
            places: [],
            hotels: [],
            flights: [],
            affiliate_deals: [],
            cultural_info: ['content' => ''],
            educational_info: ['content' => ''],
            best_time_to_visit: ['content' => ''],
            visa_info: ['content' => ''],
            nearby_destinations: [],
            weather: [],
            dataQuality: [
                'overall_readiness' => 'low',
                'recommended_for_build' => false,
            ],
            meta: [],
            error: $message
        );
    }
}