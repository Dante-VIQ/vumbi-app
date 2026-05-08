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

    public function search(string $query): SearchResult
    {
        $query = $this->sanitizeQuery($query);

        if (empty($query)) {
            return $this->errorResult('Invalid search query');
        }

        $cacheKey = 'travel_search_'.md5(strtolower($query));

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($query) {
            return $this->performSearch($query);
        });
    }

    private function performSearch(string $query): SearchResult
    {
        try {
            $location = $this->osm->geocode($query);
            if (! $location) {
                return $this->errorResult('City not found. Please check the spelling.');
            }

            $data = $this->fetchAllData($location, $query);

            $description = $this->ai->describeCity($query);

            $classification = $this->classifier->classify($query);
            $routing = $this->router->decide($classification);

            $dataQuality = $this->assessDataQuality($data, $description);

            return new SearchResult(
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
                nearby_destinations: $data['nearby_destinations'],
                weather: $data['weather'],
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
                'error' => $e->getMessage(),
            ]);

            return $this->errorResult('Search failed. Please try again later.');
        }
    }

    private function fetchAllData(array $location, string $query): array
    {
        $data = [
            'location' => $location,
            'places' => [],
            'hotels' => [],
            'flights' => [],
            'affiliate_deals' => [],
            'cultural_info' => ['content' => ''],
            'educational_info' => ['content' => ''],
            'best_time_to_visit' => ['content' => ''],
            'visa_info' => ['content' => ''],
            'nearby_destinations' => [],
            'weather' => [],
        ];

        try {
            // Places (OpenTripMap)
            $data['places'] = $this->trip->getPlaces(
                $location['lat'] ?? 0,
                $location['lon'] ?? 0,
                limit: 15
            );
        } catch (Exception $e) {
            Log::warning('Places API failed', ['error' => $e->getMessage()]);
        }

        try {
            // Hotels
            $data['hotels'] = $this->hotels->searchHotels($query, limit: 8);
        } catch (Exception $e) {
            Log::warning('Hotel API failed', ['query' => $query, 'error' => $e->getMessage()]);
        }

        try {
            // Flights
            $data['flights'] = $this->flights->searchFlights($query, limit: 8);
        } catch (Exception $e) {
            Log::warning('Flight Aggregator failed', ['error' => $e->getMessage()]);
        }

        try {
            // Affiliate Deals
            $data['affiliate_deals'] = $this->bonusArrive->searchFlights($query, limit: 5);
        } catch (Exception $e) {
            Log::warning('BonusArrive API failed', ['error' => $e->getMessage()]);
        }

        // AI Content (most critical for user experience)
        try {
            $data['cultural_info'] = $this->ai->generateCulturalInfo($query);
            $data['educational_info'] = $this->ai->generateEducationalInfo($query);
            $data['best_time_to_visit'] = $this->ai->generateBestTimeToVisit($query);
            $data['visa_info'] = $this->ai->generateVisaInfo($query);
        } catch (Exception $e) {
            Log::warning('AI Content generation failed', ['error' => $e->getMessage()]);
        }

        try {
            $data['nearby_destinations'] = $this->getNearbyDestinations($location, $query);
            $data['weather'] = $this->getWeatherData($location);
        } catch (Exception $e) {
            Log::warning('Secondary data failed', ['error' => $e->getMessage()]);
        }

        return $data;
    }

    private function getNearbyDestinations(array $location, string $query): array
    {
        try {
            // Use OpenTripMap or OSM to find nearby cities
            $response = Http::timeout(10)->get(config('services.opentripmap.base_url').'/radius', [
                'lat' => $location['lat'],
                'lon' => $location['lon'],
                'radius' => 80000,           // 80km radius
                'limit' => 8,
                'apikey' => config('services.opentripmap.key'),
            ]);

            if ($response->successful()) {
                return collect($response->json()['features'] ?? [])
                    ->filter(fn ($item) => ($item['properties']['name'] ?? '') !== $query)
                    ->take(6)
                    ->map(fn ($item) => [
                        'name' => $item['properties']['name'] ?? 'Nearby City',
                        'distance_km' => round(($item['properties']['dist'] ?? 0) / 1000, 1),
                    ])
                    ->all();
            }
        } catch (Exception $e) {
            Log::warning('Failed to fetch nearby destinations', ['error' => $e->getMessage()]);
        }

        return [];
    }

    private function getWeatherData(array $location): array
    {
        // TODO: Replace with real weather API (OpenWeatherMap, WeatherAPI, etc.)
        try {
            // Placeholder using a free/public API or your configured service
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

    private function assessDataQuality(array $data, string $description): array
    {
        $hasPlaces = count($data['places'] ?? []) >= 8;
        $hasHotels = count($data['hotels'] ?? []) >= 5;
        $hasFlights = count($data['flights'] ?? []) >= 4;
        $hasDeals = count($data['affiliate_deals'] ?? []) >= 3;

        $hasCultural = ! empty($data['cultural_info']['content'] ?? '');
        $hasEducational = ! empty($data['educational_info']['content'] ?? '');
        $hasBestTime = ! empty($data['best_time_to_visit']['content'] ?? '');
        $hasVisaInfo = ! empty($data['visa_info']['content'] ?? '');
        $hasDescription = strlen(trim($description)) > 100;

        // Calculate quality score
        $score = 0;
        if ($hasPlaces) {
            $score += 22;
        }
        if ($hasHotels) {
            $score += 18;
        }
        if ($hasFlights) {
            $score += 15;
        }
        if ($hasDeals) {
            $score += 12;
        }
        if ($hasCultural) {
            $score += 10;
        }
        if ($hasEducational) {
            $score += 8;
        }
        if ($hasBestTime) {
            $score += 8;
        }
        if ($hasVisaInfo) {
            $score += 7;
        }
        if ($hasDescription) {
            $score += 5;
        }

        $canBuild = $hasPlaces && $hasHotels && $hasDescription;

        return [
            'can_build' => $canBuild,
            'quality_score' => min(100, $score),
            'places_count' => count($data['places'] ?? []),
            'hotels_count' => count($data['hotels'] ?? []),
            'flights_count' => count($data['flights'] ?? []),
            'deals_count' => count($data['affiliate_deals'] ?? []),

            'has_cultural' => $hasCultural,
            'has_educational' => $hasEducational,
            'has_best_time' => $hasBestTime,
            'has_visa_info' => $hasVisaInfo,
            'has_description' => $hasDescription,

            'overall_readiness' => $score >= 78 ? 'high'
                                        : ($score >= 55 ? 'medium' : 'low'),

            'recommended_for_build' => $canBuild && $score >= 65,
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
