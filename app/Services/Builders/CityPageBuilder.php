<?php

namespace App\Services\Builders;

use App\Models\City;
use App\Services\AI\AIContentService;
use App\Services\FlightAggregatorService;
use App\Services\TravelPayouts\HotelService;
use App\Services\ApiClients\OpenTripMapClient;
use Illuminate\Support\Facades\Log;

class CityPageBuilder
{
    public function __construct(
        private readonly OpenTripMapClient $trip,
        private readonly HotelService $hotelService,
        private readonly FlightAggregatorService $flightService,
        private readonly AIContentService $aiService
    ) {}

    public function build(City $city): void
    {
        Log::info('Building city page', ['city' => $city->name]);

        $this->markAsBuilding($city);

        try {
            // 1. Ensure Geo Data
            $this->ensureGeoData($city);

            // 2. Import Places
            $this->importPlaces($city);

            // 3. Import Hotels
            $this->importHotels($city);

            // 4. Generate AI Content
            $this->generateContent($city);

            // 5. Mark as Published
            $city->update([
                'status'            => 'published',
                'is_published'      => true,
                'last_refreshed_at' => now(),
            ]);

            Log::info('City page built successfully', ['city' => $city->name]);

        } catch (\Exception $e) {
            $this->handleFailure($city, $e);
        }
    }

    private function markAsBuilding(City $city): void
    {
        $city->update(['status' => 'building', 'is_published' => false]);
    }

    private function ensureGeoData(City $city): void
    {
        // You can call GeoService here if needed
    }

    private function importPlaces(City $city): void
    {
        $places = $this->trip->getPlaces($city->latitude, $city->longitude, limit: 20);
        // Call your PlacesService logic here or move it inside
        Log::info("Imported places", ['count' => count($places)]);
    }

    private function importHotels(City $city): void
    {
        $hotels = $this->hotelService->searchHotels($city->name, limit: 10);
        Log::info("Imported hotels", ['count' => count($hotels)]);
        // Save to DB logic can be added here
    }

    private function generateContent(City $city): void
    {
        $guide = $this->aiService->buildGuide($city);
        
        $city->guide()->updateOrCreate(
            ['city_id' => $city->id],
            ['intro_text' => $guide]
        );
    }

    private function handleFailure(City $city, \Exception $e): void
    {
        $city->update(['status' => 'failed']);
        Log::error('City page build failed', [
            'city' => $city->name,
            'error' => $e->getMessage()
        ]);
    }
}