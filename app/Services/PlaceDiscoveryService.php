<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PlaceDiscoveryService
{
    public function getPlaceSummary(string $place)
    {
        $url = 'https://en.wikipedia.org/api/rest_v1/page/summary/'.urlencode($place);

        $response = Http::get($url);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        return [
            'title' => $data['title'] ?? $place,
            'description' => $data['extract'] ?? null,
            'image' => $data['thumbnail']['source'] ?? null,
            'source' => $data['content_urls']['desktop']['page'] ?? null,
        ];
    }

    public function getAttractions(string $place, int $limit = 6)
    {
        $apiKey = config('services.opentripmap.key');

        // Step 1: geocode place → get coordinates
        $geo = Http::get('https://api.opentripmap.com/0.1/en/places/geoname', [
            'name' => $place,
            'apikey' => $apiKey,
        ])->json();

        if (! isset($geo['lat'])) {
            return [];
        }

        // Step 2: fetch attractions nearby
        $places = Http::get('https://api.opentripmap.com/0.1/en/places/radius', [
            'radius' => 20000,
            'lon' => $geo['lon'],
            'lat' => $geo['lat'],
            'rate' => 2,
            'format' => 'json',
            'limit' => $limit,
            'apikey' => $apiKey,
        ])->json();

        return collect($places)->map(function ($p) {
            return [
                'name' => $p['name'] ?? 'Attraction',
                'kind' => $p['kinds'] ?? '',
            ];
        })->toArray();
    }
}
