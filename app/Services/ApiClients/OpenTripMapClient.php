<?php

namespace App\Services\ApiClients;

use Illuminate\Support\Facades\Http;

class OpenTripMapClient
{
    private string $baseUrl = "https://api.opentripmap.com/0.1/en/places";

    public function getPlaces(float $lat, float $lon, int $radius = 5000): array
    {
        $response = Http::get("{$this->baseUrl}/radius", [
            'radius' => $radius,
            'lon' => $lon,
            'lat' => $lat,
            'apikey' => config('services.opentripmap.key'),
        ]);

        return $response->successful()
            ? $response->json()['features'] ?? []
            : [];
    }

    public function getPlaceDetails(string $xid): array
    {
        $response = Http::get("{$this->baseUrl}/xid/{$xid}", [
            'apikey' => config('services.opentripmap.key'),
        ]);

        return $response->successful()
            ? $response->json()
            : [];
    }
}