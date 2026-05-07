<?php

namespace App\Services\Search;

use App\Services\AI\TravelAIService;
use App\Services\ApiClients\OpenStreetMapClient;
use App\Services\ApiClients\OpenTripMapClient;
use App\Services\TravelPayouts\FlightService;
use App\Services\TravelPayouts\HotelService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class SearchService
{
    protected OpenStreetMapClient $osm;
    protected OpenTripMapClient $trip;
    protected HotelService $hotels;
    protected FlightService $flights;
    protected TravelAIService $ai;

    public function __construct(
        OpenStreetMapClient $osm,
        OpenTripMapClient $trip,
        HotelService $hotels,
        FlightService $flights,
        TravelAIService $ai
    ) {
        $this->osm = $osm;
        $this->trip = $trip;
        $this->hotels = $hotels;
        $this->flights = $flights;
        $this->ai = $ai;
    }

    /**
     * Main search method - Improved version
     */
    public function search(string $query): array
    {
        $query = $this->sanitizeQuery($query);

        if (empty($query)) {
            return $this->errorResponse('Invalid or empty search query');
        }

        // Cache the entire search result (very important for performance)
        $cacheKey = 'travel_search_' . md5(strtolower($query));

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($query) {
            return $this->performSearch($query);
        });
    }

    /**
     * Core search logic (executed inside cache)
     */
    private function performSearch(string $query): array
    {
        try {
            // 1. Geocode City (Most critical)
            $location = $this->getLocation($query);

            if (!$location) {
                return $this->errorResponse('City not found. Please try a different destination.');
            }

            // 2. Fetch all data (we'll improve this with parallelism later)
            $places = $this->getPlaces($location);
            $hotels = $this->getHotels($query);
            $flights = $this->getFlights($query);
            $description = $this->getCityDescription($query);

            // 3. Determine if we have enough data to auto-build the page
            $dataQuality = $this->assessDataQuality($places, $hotels, $flights, $description);

            return [
                'success'       => true,
                'needs_build'   => $dataQuality['can_build'],
                'city'          => ucwords($query),
                'description'   => $description,
                'location'      => $location,
                'places'        => $places,
                'hotels'        => $hotels,
                'flights'       => $flights,
                'data_quality'  => $dataQuality,
                'cached_at'     => now()->toIso8601String(),
            ];

        } catch (Exception $e) {
            Log::error('Travel Search Failed', [
                'query' => $query,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('An unexpected error occurred while searching.');
        }
    }

    private function sanitizeQuery(string $query): string
    {
        $query = trim($query);
        $query = strip_tags($query);
        
        // Remove common unwanted words
        $query = preg_replace('/\b(search|find|book|travel to)\b/i', '', $query);
        
        return trim($query);
    }

    private function getLocation(string $query): ?array
    {
        try {
            return $this->osm->geocode($query);
        } catch (Exception $e) {
            Log::warning("Geocoding failed for: {$query}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function getPlaces(array $location): array
    {
        try {
            return $this->trip->getPlaces($location['lat'], $location['lon']);
        } catch (Exception $e) {
            Log::warning("Failed to fetch places", ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function getHotels(string $query): array
    {
        try {
            return $this->hotels->searchHotels($query);
        } catch (Exception $e) {
            Log::warning("Failed to fetch hotels", ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function getFlights(string $query): array
    {
        try {
            return $this->flights->searchFlights($query);
        } catch (Exception $e) {
            Log::warning("Failed to fetch flights", ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function getCityDescription(string $query): string
    {
        try {
            return $this->ai->describeCity($query) ?? '';
        } catch (Exception $e) {
            Log::warning("AI description failed", ['error' => $e->getMessage()]);
            return '';
        }
    }

    private function assessDataQuality(array $places, array $hotels, array $flights, string $description): array
    {
        $hasPlaces = count($places) >= 6;        // At least 6 attractions recommended
        $hasHotels = count($hotels) >= 3;
        $hasDescription = strlen($description) > 50;

        return [
            'can_build'     => $hasPlaces && $hasHotels && $hasDescription,
            'has_places'    => $hasPlaces,
            'places_count'  => count($places),
            'has_hotels'    => $hasHotels,
            'has_flights'   => !empty($flights),
            'has_description' => $hasDescription,
            'quality_score' => $this->calculateQualityScore($hasPlaces, $hasHotels, $hasDescription),
        ];
    }

    private function calculateQualityScore(bool $hasPlaces, bool $hasHotels, bool $hasDescription): int
    {
        $score = 0;
        if ($hasPlaces) $score += 45;
        if ($hasHotels) $score += 35;
        if ($hasDescription) $score += 20;
        return $score;
    }

    private function errorResponse(string $message): array
    {
        return [
            'success'     => false,
            'needs_build' => false,
            'error'       => $message,
        ];
    }
}