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
use Illuminate\Support\Str;

class BuildCityDiscoveryPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300; // 5 minutes

    public function __construct(
        public string $searchTerm   // Changed name for clarity
    ) {}

    public function handle(
        PlacesService $placesService,
        HotelService $hotelService,
        AIContentService $aiService
    ): void {
        $searchTerm = trim($this->searchTerm);

        // Find existing city or create a new one
        $city = City::firstOrCreate(
            ['slug' => Str::slug($searchTerm)],
            [
                'name' => $searchTerm,
                'status' => 'building',
                'is_published' => false,
                // Add other default fields as needed
            ]
        );

        // If already published and recent, skip heavy work
        if ($city->status === 'published' && $city->last_refreshed_at?->gt(now()->subHours(24))) {
            return;
        }

        try {
            // 1. Ensure Geo Data
            $this->ensureGeoData($city);

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

            // 5. Mark as complete
            $city->update([
                'status' => 'published',
                'is_published' => true,
                'last_refreshed_at' => now()
            ]);

        } catch (\Throwable $e) {
            $city->update(['status' => 'failed']);
            throw $e; // Let Laravel retry or log
        }
    }

    private function ensureGeoData(City $city): void
    {
        if ($city->latitude && $city->longitude) {
            return;
        }

        // TODO: Integrate proper geocoding service (e.g. Google Maps, OpenStreetMap, etc.)
        // For now, you can set dummy coordinates or throw a proper exception
        // Example:
        // $geo = app(GeoService::class)->geocode($city->name);
        // $city->update([...]);
    }
}