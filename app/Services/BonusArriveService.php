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

    public function searchFlights(string $destination, int $limit = 5)
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $cacheKey = 'bonusarrive_flights_' . md5($destination);

        return Cache::remember($cacheKey, now()->addHours(4), function () use ($destination, $limit) {
            try {
                $response = Http::get('https://api.bonusarrive.com/v1/flights', [
                    'destination'  => $destination,
                    'affiliate_id' => $this->affiliateId,
                    'limit'        => $limit,
                ]);

                if ($response->successful()) {
                    return collect($response->json('data'))->take($limit)->map(function ($flight) {
                        return [
                            'airline' => $flight['airline'] ?? 'Major Airline',
                            'from'    => $flight['departure_city'] ?? 'NBO',
                            'to'      => $flight['arrival_city'] ?? $destination,
                            'price'   => isset($flight['price']) ? '$' . number_format($flight['price'], 2) : 'Best price',
                            'link'    => $flight['booking_url'] ?? '#',
                            'network' => 'Bonus Arrive',
                            'type'    => 'flight',
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