<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AwinService
{
    protected $apiKey;
    protected $publisherId;

    public function __construct()
    {
        $this->apiKey = config('services.awin.api_key');
        $this->publisherId = config('services.awin.publisher_id');
    }

    /**
     * Search offers from Awin
     */
    public function searchOffers(string $keyword, int $limit = 6)
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $cacheKey = "awin_offers_" . strtolower($keyword);

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($keyword, $limit) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])->get('https://api.awin.com/publishers/' . $this->publisherId . '/advertiser-campaigns', [
                    'search' => $keyword,
                    'limit' => $limit,
                ]);

                if ($response->successful()) {
                    return collect($response->json())->take($limit)->map(function ($offer) {
                        return [
                            'id' => $offer['id'] ?? null,
                            'name' => $offer['advertiserName'] ?? 'Awin Partner',
                            'title' => $offer['campaignName'] ?? 'Special Offer',
                            'description' => $offer['description'] ?? '',
                            'link' => $offer['trackingLink'] ?? '#',
                            'type' => 'awin',
                            'network' => 'Awin',
                        ];
                    });
                }
            } catch (\Exception $e) {
                Log::error('Awin API Error: ' . $e->getMessage());
            }

            return [];
        });
    }
}