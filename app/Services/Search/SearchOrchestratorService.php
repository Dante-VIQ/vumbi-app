<?php

namespace App\Services\Search;

use App\DataTransferObjects\SearchResult;
use App\Services\AI\AIContentService;
use App\Services\ApiClients\OpenStreetMapClient;
use App\Services\ApiClients\OpenTripMapClient;
use App\Services\TravelPayouts\FlightService;
use App\Services\TravelPayouts\HotelService;
use App\Services\BonusArriveService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class SearchOrchestratorService
{
    public function __construct(
        private readonly OpenStreetMapClient $osm,
        private readonly OpenTripMapClient $trip,
        private readonly HotelService $hotels,
        private readonly FlightService $flights,
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

        $cacheKey = 'travel_search_' . md5(strtolower($query));

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($query) {
            return $this->performSearch($query);
        });
    }

    private function performSearch(string $query): SearchResult
    {
        try {
            // 1. Geocode
            $location = $this->osm->geocode($query);
            if (!$location) {
                return $this->errorResult('City not found');
            }

            // 2. Parallel Data Fetching (Major Performance Win)
            $results = $this->fetchDataInParallel($location, $query);

            // 3. Generate AI Description
            $description = $this->ai->describeCity($query);

            // 4. Classification & Routing
            $classification = $this->classifier->classify($query);
            $routing = $this->router->decide($classification);

            // 5. Assess Quality
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

    private function fetchDataInParallel(array $location, string $query): array
    {
        // TODO: We'll use Http::pool() in the next iteration for true parallelism
        // For now, sequential with better structure

        return [
            'places'  => $this->trip->getPlaces($location['lat'], $location['lon'], limit: 15),
            'hotels'  => $this->hotels->searchHotels($query),
            'flights' => array_merge(
                $this->flights->searchFlights($query),
                $this->bonusArrive->searchFlights($query)
            ),
        ];
    }

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