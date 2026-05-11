<?php

namespace App\Services\TravelPayouts;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlightService
{
    private const BASE_URL = 'https://api.travelpayouts.com/aviasales/v3/prices_for_dates';

    public function searchFlights(string $destination, string $origin = 'NBO', int $limit = 10): array
    {
        $destination = trim($destination);
        if (empty($destination)) {
            return [];
        }

        $token = config('services.travelpayouts.token') ?? env('TRAVELPAYOUTS_TOKEN');

        if (empty($token)) {
            Log::error("TravelPayouts API Token is missing");
            return [];
        }

        try {
            $response = Http::timeout(12)
                ->get(self::BASE_URL, [
                    'origin'      => strtoupper($origin),
                    'destination' => $this->getAirportCode($destination),
                    'currency'    => 'KES',
                    'limit'       => min($limit, 15),
                    'token'       => $token,                    // ← Required
                ]);

            if (!$response->successful()) {
                Log::warning("TravelPayouts Flight API failed", [
                    'destination' => $destination,
                    'status'      => $response->status(),
                    'body'        => $response->body()
                ]);
                return [];
            }

            $data = $response->json();
            return $data['data'] ?? $data ?? [];

        } catch (\Exception $e) {
            Log::error("FlightService exception", [
                'destination' => $destination,
                'error'       => $e->getMessage()
            ]);
            return [];
        }
    }

    private function getAirportCode(string $destination): string
    {
        if (strlen($destination) === 3 && ctype_upper($destination)) {
            return $destination;
        }
        return strtoupper(substr($destination, 0, 3));
    }
}