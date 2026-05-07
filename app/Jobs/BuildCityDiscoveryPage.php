<?php

namespace App\Jobs;

use App\Models\City;
use App\Services\AIContentService;
use App\Services\GeoService;
use App\Services\HotelService;
use App\Services\PlacesService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class BuildCityDiscoveryPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 420;     // 7 minutes (increased slightly)
    public int $backoff = 60;      // seconds before retry

    public function __construct(
        public readonly string $searchTerm
    ) {}

    public function handle(
        PlacesService $placesService,
        HotelService $hotelService,
        AIContentService $aiService,
        GeoService $geoService
    ): void {
        $searchTerm = trim($this->searchTerm);
        $slug = Str::slug($searchTerm);

        Log::info('Starting city discovery page build', [
            'search_term' => $searchTerm,
            'slug' => $slug
        ]);

        // Prevent duplicate builds running at the same time
        $city = $this->getOrCreateCity($slug, $searchTerm);

        // Skip if already freshly published
        if ($this->shouldSkip($city)) {
            Log::info('City page is up to date, skipping build', ['city' => $searchTerm]);
            return;
        }

        try {
            $this->markAsBuilding($city);

            // 1. Ensure Geo Data
            $this->ensureGeoData($city, $geoService);

            // 2. Build Places / Attractions
            $placesService->build($city);

            // 3. Build Hotels
            $hotelService->build($city);

            // 4. Generate AI Content
            $guide = $aiService->buildGuide($city);

            $city->guide()->updateOrCreate(
                ['city_id' => $city->id],
                ['intro_text' => $guide]
            );

            // 5. Mark as successfully published
            $city->update([
                'status'            => 'published',
                'is_published'      => true,
                'last_refreshed_at' => now(),
            ]);

            Log::info('City discovery page built successfully', [
                'city' => $searchTerm,
                'city_id' => $city->id
            ]);

        } catch (Throwable $e) {
            $this->handleFailure($city, $e);
            throw $e; // Let Laravel handle retry/fail
        }
    }

    private function getOrCreateCity(string $slug, string $name): City
    {
        return City::firstOrCreate(
            ['slug' => $slug],
            [
                'name'          => $name,
                'status'        => 'building',
                'is_published'  => false,
            ]
        );
    }

    private function shouldSkip(City $city): bool
    {
        return $city->status === 'published' 
            && $city->last_refreshed_at?->gt(now()->subHours(24));
    }

    private function markAsBuilding(City $city): void
    {
        $city->update([
            'status' => 'building',
            'is_published' => false,
        ]);
    }

    private function ensureGeoData(City $city, GeoService $geoService): void
    {
        if ($city->latitude && $city->longitude) {
            return;
        }

        $geo = $geoService->geocode($city->name);

        if ($geo) {
            $city->update([
                'latitude'  => $geo['lat'],
                'longitude' => $geo['lon'],
                'country'   => $geo['country'] ?? null,
                'country_code' => $geo['country_code'] ?? null,
            ]);
        }
    }

    private function handleFailure(City $city, Throwable $e): void
    {
        $city->update([
            'status' => 'failed',
            'last_refreshed_at' => now(),
        ]);

        Log::error('Failed to build city discovery page', [
            'city' => $city->name,
            'city_id' => $city->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}