<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BonusArriveService
{
    protected $apiKey;
    protected $affiliateId;

    public function __construct()
    {
        $this->apiKey = config('services.bonusarrive.api_key');
        $this->affiliateId = config('services.bonusarrive.affiliate_id');
    }

    /**
     * Search Flights from Bonus Arrive
     */
    public function searchFlights(string $destination, int $limit = 5)
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $cacheKey = "bonusarrive_flights_" . strtolower($destination);

        return Cache::remember($cacheKey, now()->addHours(4), function () use ($destination, $limit) {
            try {
                $response = Http::get('https://api.bonusarrive.com/flights/search', [
                    'destination' => $destination,
                    'affiliate_id' => $this->affiliateId,
                    'limit' => $limit,
                ]);

                if ($response->successful()) {
                    return collect($response->json('flights'))->take($limit)->map(function ($flight) {
                        return [
                            'airline' => $flight['airline'] ?? 'Major Airline',
                            'from' => $flight['departure'] ?? 'NBO',
                            'to' => $flight['arrival'] ?? $destination,
                            'price' => $flight['price'] ?? 'Best price',
                            'link' => $flight['booking_url'] ?? '#',
                            'type' => 'flight',
                            'network' => 'Bonus Arrive',
                        ];
                    });
                }
            } catch (\Exception $e) {
                Log::error('Bonus Arrive API Error: ' . $e->getMessage());
            }

            return [];
        });
    }
}