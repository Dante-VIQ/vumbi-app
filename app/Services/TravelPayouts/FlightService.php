<?php

namespace App\Services\TravelPayouts;

use Illuminate\Support\Facades\Http;

class FlightService
{
    public function searchFlights(string $city): array
    {
        $response = Http::get('https://api.travelpayouts.com/aviasales/v3/prices_for_dates', [
            'origin' => 'NBO', // default base airport
            'destination' => strtoupper(substr($city,0,3)),
            'currency' => 'KES'
        ]);

        if (!$response->successful()) {
            return [];
        }

        return $response->json();
    }
}