<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class BonusArriveService
{
    protected string $apiKey;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->apiKey   = config('services.bonusarrive.api_key', '');
        $this->cacheTtl = config('services.bonusarrive.cache_ttl', 14400); // 4 hours
    }

    /**
     * Search flights / travel deals using Bonus Arrive API
     */
    public function searchFlights(string $destination, int $limit = 8): array
    {
        $destination = trim($destination);
        
        if (empty($destination) || empty($this->apiKey)) {
            return [];
        }

        $cacheKey = 'bonusarrive_flights_' . md5(strtolower($destination));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($destination, $limit) {
            return $this->fetchFlightsFromApi($destination, $limit);
        });
    }

private function fetchFlightsFromApi(string $destination, int $limit): array
{
    try {
        $response = Http::timeout(15)
            ->withHeaders([
                'Content-Type'  => 'application/json;charset=utf-8',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])
            ->post('https://www.bonusarrive.com/slapi/service/advertisers', [
                'per_page' => min($limit, 20),
                'page'     => 1,
                'keyword'  => $destination,
                'm_id'     => config('services.bonusarrive.m_id', 11167),
            ]);

        if (!$response->successful()) {
            Log::warning('Bonus Arrive API returned error', [
                'destination' => $destination,
                'status'      => $response->status(),
                'body'        => $response->body()
            ]);
            return [];
        }

        $data = $response->json();
        
        $items = $data['data']
            ?? $data['results']
            ?? $data
            ?? [];

        // $items may be an array, but individual entries could be scalars
        if (!is_array($items)) {
            $items = [];
        }

        // Normalize only valid entries, ignore integers/strings
        $flights = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $flights[] = $this->normalizeFlightData($item);
            }
        }

        return collect($flights)
            ->take($limit)
            ->values()
            ->all();

    } catch (Exception $e) {
        Log::error('BonusArriveService::fetchFlightsFromApi failed', [
            'destination' => $destination,
            'error'       => $e->getMessage()
        ]);
        return [];
    }
}

    private function normalizeFlightData(array $item): array
    {
        return [
            'airline'      => $item['airline'] ?? $item['title'] ?? 'Bonus Arrive Deal',
            'from'         => $item['departure'] ?? 'NBO',
            'to'           => $item['arrival'] ?? $item['destination'] ?? 'Destination',
            'price'        => $this->formatPrice($item['price'] ?? null),
            'link'         => $item['url'] ?? $item['booking_url'] ?? '#',
            'description'  => $item['description'] ?? $item['short_desc'] ?? '',
            'image'        => $item['image'] ?? $item['thumbnail'] ?? null,
            'network'      => 'Bonus Arrive',
            'type'         => 'flight',
            'raw'          => $item, // Keep original data for debugging
        ];
    }

    private function formatPrice($price): string
    {
        if (empty($price)) {
            return 'Best Price';
        }

        return is_numeric($price) 
            ? '$' . number_format((float)$price, 2) 
            : (string) $price;
    }
}