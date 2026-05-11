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
        $this->cacheTtl = config('services.bonusarrive.cache_ttl', 14400);
    }

    /**
     * Fetch travel deals (flights, hotels, packages) from Bonus Arrive.
     */
    public function searchDeals(string $destination, int $limit = 8): array
    {
        $destination = trim($destination);
        if (empty($destination) || empty($this->apiKey)) {
            return [];
        }

        $cacheKey = 'bonusarrive_deals_' . md5(strtolower($destination));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($destination, $limit) {
            return $this->fetchDealsFromApi($destination, $limit);
        });
    }

    /**
     * Legacy alias for backward compatibility.
     */
    public function searchFlights(string $destination, int $limit = 8): array
    {
        return $this->searchDeals($destination, $limit);
    }

    private function fetchDealsFromApi(string $destination, int $limit): array
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
                    'body'        => $response->body(),
                ]);
                return [];
            }

            $data = $response->json();

            // Extract list – adjust path based on your actual response
            $items = $data['data'] ?? $data['results'] ?? $data ?? [];

            if (!is_array($items)) {
                return [];
            }

            $deals = [];
            foreach ($items as $item) {
                if (is_array($item)) {
                    $deals[] = $this->normalizeDeal($item);
                }
            }

            return collect($deals)
                ->take($limit)
                ->values()
                ->all();

        } catch (Exception $e) {
            Log::error('BonusArriveService::fetchDealsFromApi failed', [
                'destination' => $destination,
                'error'       => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Normalize a deal/advert into a standard structure.
     */
    private function normalizeDeal(array $item): array
    {
        return [
            'title'       => $item['title'] ?? 'Travel Deal',
            'description' => $item['short_desc'] ?? $item['description'] ?? '',
            'price'       => $this->formatPrice($item['price'] ?? null, $item['currency'] ?? 'USD'),
            'currency'    => $item['currency'] ?? 'USD',
            'image'       => $item['image'] ?? $item['thumbnail'] ?? null,
            'link'        => $item['url'] ?? $item['booking_url'] ?? '#',
            'advertiser'  => $item['advertiser_name'] ?? 'Bonus Arrive',
            'type'        => $item['type'] ?? 'deal',   // 'flight', 'hotel', 'package'
            'raw'         => $item,                     // for debugging
        ];
    }

    /**
     * Format price into a human-readable string.
     */
    private function formatPrice($price, string $currency = 'USD'): string
    {
        if (empty($price) || !is_numeric($price)) {
            return 'Best Price';
        }

        // If currency is KES, format differently
        if (strtoupper($currency) === 'KES') {
            return 'KSh ' . number_format((float)$price);
        }

        return '$' . number_format((float)$price, 2);
    }
}