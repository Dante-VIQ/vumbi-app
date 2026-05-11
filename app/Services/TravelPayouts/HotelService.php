<?php

namespace App\Services\TravelPayouts;

use App\Models\City;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelService
{
    private const BASE_URL = 'https://engine.hotellook.com/api/v2/cache.json';

    /**
     * Original text search (keeps backward compatibility).
     */
    public function searchHotels(string $city, int $limit = 8): array
    {
        try {
            $token = config('services.travelpayouts.api_token');

            $response = Http::timeout(10)->get(self::BASE_URL, [
                'location' => $city,
                'currency' => 'USD',
                'limit'    => min($limit, 12),
                'lang'     => 'en',
                'token'    => $token,           // ← add token
            ]);

            Log::info('Hotel API Response', [
                'city'   => $city,
                'status' => $response->status(),
                'body'   => $response->body(),   // helpful for debugging
            ]);

            return $response->successful() ? $response->json()['hotels'] ?? [] : [];
        } catch (Exception $e) {
            Log::error('HotelService failed', ['city' => $city, 'error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Search hotels by geographic coordinates (latitude / longitude).
     * This is the recommended method for accurate results.
     */
    public function searchByCoordinates(float $lat, float $lon, int $limit = 8): array
    {
        try {
            $token = config('services.travelpayouts.api_token');

            $response = Http::timeout(15)->get(self::BASE_URL, [
                'latitude'  => $lat,
                'longitude' => $lon,
                'currency'  => 'USD',
                'limit'     => $limit,
                'lang'      => 'en',
                'token'     => $token,           // ← must be included
            ]);

            Log::info('Hotel API (coordinates) Response', [
                'lat'    => $lat,
                'lon'    => $lon,
                'status' => $response->status(),
            ]);

            if ($response->successful()) {
                return $response->json()['hotels'] ?? [];
            }

            Log::warning('Hotel coordinates search failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [];
        } catch (Exception $e) {
            Log::error('HotelService coordinate error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Used by the Build Job
     */
    public function build(City $city): void
    {
        // You might want to use coordinates here as well
        $hotels = $this->searchByCoordinates(
            $city->latitude ?? 0,   // Make sure City model has lat/lon
            $city->longitude ?? 0,
            12
        );

        Log::info('Found hotels for city', [
            'city'  => $city->name,
            'count' => count($hotels),
        ]);
    }
}