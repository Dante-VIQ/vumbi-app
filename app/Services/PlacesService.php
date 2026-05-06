<?php

namespace App\Services;

use App\Models\City;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Services\ApiClients\OpenTripMapClient;

class PlaceService
{
    public function __construct(
        private OpenTripMapClient $client
    ) {}

    public function build(City $city): void
    {
        $places = $this->client->getPlaces(
            $city->latitude,
            $city->longitude
        );

        foreach ($places as $place) {

            Place::updateOrCreate(
                ['google_place_id' => $place['id']],
                [
                    'city_id' => $city->id,
                    'place_category_id' => $this->mapCategory($place),
                    'name' => $place['properties']['name'] ?? 'Unknown',
                    'rating' => null,
                ]
            );
        }
    }

    private function mapCategory($place)
    {
        return PlaceCategory::first()->id; // simplify for MVP
    }
}