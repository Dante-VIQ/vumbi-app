<?php

namespace App\Jobs;

use App\Models\City;
use App\Services\PlaceService;
use App\Services\HotelService;
use App\Services\AIContentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildCityDiscoveryPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $citySlug
    ) {}

    public function handle(
        PlaceService $placeService,
        HotelService $hotelService,
        AIContentService $aiService
    ): void {

        $city = City::where('slug', $this->citySlug)
            ->firstOrFail();

        // 1. Ensure city has coordinates
        $this->ensureGeoData($city);

        // 2. Build Places
        $placeService->build($city);

        // 3. Build Hotels
        $hotelService->build($city);

        // 4. Generate AI Guide
        $guide = $aiService->buildGuide($city);

        $city->guide()->updateOrCreate(
            ['city_id' => $city->id],
            ['intro_text' => $guide]
        );

        // 5. Mark as published
        $city->update([
            'status' => 'published',
            'last_refreshed_at' => now()
        ]);
    }

    private function ensureGeoData(City $city): void
    {
        if ($city->latitude && $city->longitude) {
            return;
        }

        // fallback minimal logic (you can later inject GeoService here)
        abort(500, "City missing coordinates: {$city->name}");
    }
}