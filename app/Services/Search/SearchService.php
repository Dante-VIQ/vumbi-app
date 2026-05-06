<?php

namespace App\Services\Search;

use App\Models\City;
use App\Models\Place;
use App\Models\CitySearch;

class SearchService
{
 public function search(string $query): array
    {
        $query = trim($query);

        $cities = City::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        return [
            'cities' => $cities,
            'places' => [],
            'suggestions' => $this->suggest($query),

            // NEW FLAG
            'needs_build' => $cities->isEmpty(),
        ];
    }

    private function suggest(string $query): array
    {
        return [
            "Things to do in {$query}",
            "Hotels in {$query}",
            "Travel guide to {$query}",
        ];
    }

    private function searchCities(string $query)
    {
        return City::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn ($city) => [
                'type' => 'city',
                'name' => $city->name,
                'country' => $city->country->name,
                'slug' => $city->slug,
            ]);
    }

    private function searchPlaces(string $query)
    {
        return Place::where('name', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn ($place) => [
                'type' => 'place',
                'name' => $place->name,
                'city' => $place->city->name,
                'category' => $place->category->name,
            ]);
    }

    private function suggestQueries(string $query): array
    {
        return [
            "Things to do in {$query}",
            "Best hotels in {$query}",
            "Travel guide to {$query}",
            "Budget in {$query}",
        ];
    }
}