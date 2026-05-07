<?php

namespace App\Services\Builders;

use App\Models\City;
use App\Services\AI\AIContentService;
use App\Services\PlacesService;
use App\Services\TravelPayouts\HotelService;
use App\Services\TravelPayouts\City as TravelPayoutsCity;
use App\Services\GeoService;
use Illuminate\Support\Facades\Log;
use Throwable;

class CityPageBuilder
{
    public function __construct(
        private readonly PlacesService $placesService,
        private readonly HotelService $hotelService,
        private readonly AIContentService $aiService,
        private readonly GeoService $geoService
    ) {}

    /**
     * Build a complete city discovery page.
     */
    public function build(City $city): void
    {
        if ($city->status === 'published' && $city->last_refreshed_at?->gt(now()->subHours(24))) {
            Log::info('City page is already fresh, skipping build', ['city' => $city->name]);
            return;
        }

        $this->markAsBuilding($city);

        try {
            // 1. Ensure geo data (lat/lon/country)
            $this->ensureGeoData($city);

            // 2. Import places / attractions
            $this->placesService->build($city);

            // 3. Import hotels (or search results)
            $this->hotelService->build($city);

            // 4. Generate AI travel guide
            $guide = $this->aiService->buildGuide($city);

            $city->guide()->updateOrCreate(
                ['city_id' => $city->id],
                ['intro_text' => $guide]
            );

            // 5. Success
            $city->update([
                'status'            => 'published',
                'is_published'      => true,
                'last_refreshed_at' => now(),
            ]);

            Log::info('City page built successfully', ['city' => $city->name]);

        } catch (Throwable $e) {
            $this->markAsFailed($city, $e);
            throw $e; // Let the queue handle retry/failure
        }
    }

    private function ensureGeoData(City $city): void
    {
        if ($city->latitude && $city->longitude) {
            return;
        }

        $geo = $this->geoService->geocode($city->name); // assuming GeoService has geocode method
        if ($geo) {
            $city->update([
                'latitude'  => $geo['lat'],
                'longitude' => $geo['lon'],
                'country'   => $geo['country'] ?? null,
                'country_code' => $geo['country_code'] ?? null,
            ]);
        }
    }

    private function markAsBuilding(City $city): void
    {
        $city->update([
            'status'       => 'building',
            'is_published' => false,
        ]);
    }

    private function markAsFailed(City $city, Throwable $e): void
    {
        $city->update(['status' => 'failed']);
        Log::error('City page build failed', [
            'city'  => $city->name,
            'error' => $e->getMessage(),
        ]);
    }
}