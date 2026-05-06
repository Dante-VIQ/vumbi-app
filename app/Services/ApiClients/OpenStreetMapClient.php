<?php

namespace App\Services\ApiClients;

use Illuminate\Support\Facades\Http;

class OpenStreetMapClient
{
    public function geocode(string $query): array
    {
        $response = Http::get('https://nominatim.openstreetmap.org/search', [
            'q' => $query,
            'format' => 'json',
            'limit' => 1
        ]);

        return $response->successful()
            ? $response->json()[0] ?? []
            : [];
    }
}