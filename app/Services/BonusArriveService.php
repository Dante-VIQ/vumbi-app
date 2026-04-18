<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BonusArriveService
{
    protected $apiKey;
    protected $cacheTtl;

    public function __construct()
    {
        $this->apiKey   = config('services.bonusarrive.api_key');
        $this->cacheTtl = config('services.bonusarrive.cache_ttl', 14400); // 4 hours
    }

    /**
     * Search Flights / Deals using Bonus Arrive API
     */
    public function searchFlights(string $destination, int $limit = 6)
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $cacheKey = 'bonusarrive_flights_' . md5($destination);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($destination, $limit) {
            try {
                $response = Http::withHeaders([
                    'Content-Type'  => 'application/json;charset=utf-8',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])->post('https://www.bonusarrive.com/slapi/service/advertisers', [
                    'per_page' => $limit,
                    'page'     => 1,
                    'keyword'  => $destination,        // Using keyword for destination search
                    'm_id'     => config('bonusarrive.m_id', 11167), // Your merchant ID if needed
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Adjust based on actual response structure
                    $flights = $data['data'] ?? $data['results'] ?? $data ?? [];

                    return collect($flights)->take($limit)->map(function ($item) {
                        return [
                            'airline'     => $item['airline'] ?? $item['title'] ?? 'Bonus Arrive Deal',
                            'from'        => $item['departure'] ?? 'NBO',
                            'to'          => $item['arrival'] ?? 'Destination',
                            'price'       => isset($item['price']) ? '$' . number_format($item['price'], 2) : 'Best price',
                            'link'        => $item['url'] ?? $item['booking_url'] ?? '#',
                            'description' => $item['description'] ?? '',
                            'network'     => 'Bonus Arrive',
                            'type'        => 'flight',
                        ];
                    });
                } else {
                    Log::error('Bonus Arrive API Error: ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Bonus Arrive Exception: ' . $e->getMessage());
            }

            return [];
        });
    }
}