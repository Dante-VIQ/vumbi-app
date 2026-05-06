<?php

namespace App\Services\Search;

use App\Services\AI\TravelAIService;
use App\Services\ApiClients\OpenStreetMapClient;
use App\Services\ApiClients\OpenTripMapClient;
use App\Services\TravelPayouts\FlightService;
use App\Services\TravelPayouts\HotelService;
use Illuminate\Support\Facades\Log;

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

    public function search(string $query): array
    {
        try {
            // 1️⃣ GEOCODE CITY
            $location = $this->osm->geocode($query);

            if (!$location) {
                return [
                    'needs_build' => false,
                    'error' => 'City not found'
                ];
            }

            // 2️⃣ ATTRACTIONS
            $places = $this->trip->getPlaces(
                $location['lat'],
                $location['lon']
            );

            // 3️⃣ HOTELS
            $hotels = $this->hotels->searchHotels($query);

            // 4️⃣ FLIGHTS
            $flights = $this->flights->searchFlights($query);

            // 5️⃣ AI CITY INTRO
            $description = $this->ai->describeCity($query);

            // 6️⃣ DECIDE IF PAGE SHOULD AUTO BUILD
            $needsBuild = empty($places) || empty($hotels);

            return [
                'needs_build' => $needsBuild,

                'city' => $query,
                'description' => $description,

                'location' => $location,
                'places' => $places,
                'hotels' => $hotels,
                'flights' => $flights
            ];

        } catch (\Exception $e) {

            Log::error('Global search failed: '.$e->getMessage());

            return [
                'needs_build' => false,
                'error' => 'Search failed'
            ];
        }
    }
}