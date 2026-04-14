<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TravelpayoutsService
{
    protected $token;
    protected $marker;

    public function __construct()
    {
        $this->token = config('travelpayouts.token');
        $this->marker = config('travelpayouts.marker');
    }

    /**
     * Search Hotels using Hotellook API
     */
    public function searchHotels(string $location, int $limit = 5)
    {
        return $this->callApi('hotels', $location, $limit);
    }

    /**
     * Search Tours, Safaris & Activities
     */
    public function searchTours(string $location, int $limit = 6)
    {
        return $this->callApi('tours', $location, $limit);
    }

    /**
     * Core API call method
     */
    private function callApi(string $type, string $location, int $limit)
    {
        if (empty($this->token) || empty($this->marker)) {
            return [];
        }

        $cacheKey = "tp_{$type}_" . strtolower(str_replace([' ', ',', '.'], '_', $location)) . "_{$limit}";

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($type, $location, $limit) {
            try {
                if ($type === 'hotels') {
                    // Hotellook API for hotels
                    $response = Http::timeout(10)->get('https://engine.hotellook.com/api/v2/cache.json', [
                        'location'     => $location,
                        'checkIn'      => now()->addDays(30)->format('Y-m-d'),
                        'checkOut'     => now()->addDays(37)->format('Y-m-d'),
                        'adultsCount'  => 2,
                        'currency'     => 'USD',
                        'limit'        => $limit,
                        'marker'       => $this->marker,
                    ]);
                } else {
                    // Tours / Activities (Travelpayouts)
                    $response = Http::timeout(10)->get('https://api.travelpayouts.com/v2/tours.json', [
                        'location' => $location,
                        'marker'   => $this->marker,
                        'limit'    => $limit,
                        'currency' => 'USD',
                    ]);
                }

                return $response->successful() ? $response->json() : [];
            } catch (\Exception $e) {
                Log::error("Travelpayouts API Error ({$type}): " . $e->getMessage());
                return [];
            }
        });
    }
}