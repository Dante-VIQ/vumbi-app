<?php

namespace App\Services;

use App\Models\City;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Services\ApiClients\OpenTripMapClient;
use Illuminate\Support\Facades\Log;
use Exception;

class PlacesService
{
    public function __construct(
        private readonly OpenTripMapClient $client
    ) {}

    public function build(City $city): void
    {
        if (empty($city->latitude) || empty($city->longitude)) {
            Log::warning("Cannot build places - missing coordinates", ['city' => $city->name]);
            return;
        }

        try {
            $places = $this->client->getPlaces(
                $city->latitude,
                $city->longitude,
                20 // You can make this configurable
            );

            foreach ($places as $place) {
                $this->createOrUpdatePlace($city, $place);
            }

            Log::info("Places imported successfully", [
                'city' => $city->name,
                'count' => count($places)
            ]);

        } catch (Exception $e) {
            Log::error("Failed to build places for city", [
                'city' => $city->name,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function createOrUpdatePlace(City $city, array $placeData): void
    {
        $externalId = $placeData['id'] ?? $placeData['properties']['xid'] ?? null;

        if (empty($externalId)) {
            return;
        }

        Place::updateOrCreate(
            ['external_id' => $externalId],   // Better than using google_place_id
            [
                'city_id'          => $city->id,
                'place_category_id'=> $this->mapCategory($placeData),
                'name'             => $placeData['properties']['name'] ?? 'Unnamed Place',
                'description'      => $placeData['properties']['descr'] ?? null,
                'address'          => $placeData['properties']['address'] ?? null,
                'latitude'         => $placeData['geometry']['coordinates'][1] ?? null,
                'longitude'        => $placeData['geometry']['coordinates'][0] ?? null,
                'rating'           => $placeData['properties']['rate'] ?? null,
                'image_url'        => $placeData['properties']['image'] ?? null,
                'source'           => 'opentripmap',
            ]
        );
    }

    private function mapCategory(array $place): int
    {
        // TODO: Improve this with proper mapping logic later
        $name = strtolower($place['properties']['name'] ?? '');

        // Basic keyword mapping
        if (str_contains($name, 'museum') || str_contains($name, 'gallery')) {
            return PlaceCategory::where('slug', 'culture')->first()?->id ?? 1;
        }

        if (str_contains($name, 'park') || str_contains($name, 'beach')) {
            return PlaceCategory::where('slug', 'nature')->first()?->id ?? 1;
        }

        // Fallback to first category
        return PlaceCategory::first()?->id ?? 1;
    }
}