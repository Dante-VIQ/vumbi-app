<?php

namespace App\Services\Discovery;

use App\Models\City;

class DiscoveryService
{
    
    public function getCityData(string $slug): array
    {
        $city = City::where('slug', $slug)
            ->with([
                'country',
                'region',
                'guide',
                'places.category',
                'hotels',
                'costs',
                'weather',
                'images'
            ])
            ->firstOrFail();

        return [
            'city' => [
                'name' => $city->name,
                'country' => $city->country->name,
                'region' => $city->region?->name,
            ],

            'guide' => $city->guide,
            'places' => $city->places,
            'hotels' => $city->hotels,
            'costs' => $city->costs,
            'weather' => $city->weather,
            'images' => $city->images,
        ];
    }
}