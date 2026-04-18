<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TravelpayoutsService
{
    protected $token;
    protected $marker;

    public function __construct()
    {
        $this->token = config('services.travelpayouts.token');
        $this->marker = config('services.travelpayouts.marker');
    }

    /**
     * Search Hotels - Main Method
     */
    public function searchHotels(string $location, int $limit = 6)
    {
        if (empty($this->token) || empty($this->marker)) {
            Log::warning('Travelpayouts: Missing token or marker');
            return [];
        }

        $cacheKey = 'tp_hotels_' . md5($location) . "_{$limit}";

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($location, $limit) {
            try {
                $response = Http::timeout(15)->get('https://engine.hotellook.com/api/v2/cache.json', [
                    'location'     => $location,
                    'checkIn'      => now()->addDays(30)->format('Y-m-d'),
                    'checkOut'     => now()->addDays(37)->format('Y-m-d'),
                    'adultsCount'  => 2,
                    'currency'     => 'USD',
                    'limit'        => $limit,
                    'marker'       => $this->marker,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $this->formatHotels($data);
                } else {
                    Log::error('Travelpayouts Hotel API Error: ' . $response->status() . ' - ' . $response->body());
                    return [];
                }
            } catch (\Exception $e) {
                Log::error('Travelpayouts Exception: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Search Tours / Activities
     */
    public function searchTours(string $location, int $limit = 6)
    {
        // For now, we'll return empty or you can implement later
        // Bonus Arrive is better for flights, this can be expanded
        return [];
    }

    private function formatHotels(array $results): array
    {
        $formatted = [];

        foreach ($results as $hotel) {
            $formatted[] = [
                'name'  => $hotel['name'] ?? 'Hotel',
                'price' => isset($hotel['priceFrom']) ? '$' . number_format($hotel['priceFrom'], 2) : 'Best rates',
                'image' => $hotel['photo'] ?? $hotel['thumbnail'] ?? null,
                'url'   => $hotel['url'] ?? '#',
                'rating'=> $hotel['rating'] ?? null,
            ];
        }

        return $formatted;
    }
}