<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AwinService
{
    protected $apiKey;
    protected $publisherId;
    protected $cacheTtl;

    public function __construct()
    {
        $this->apiKey      = config('services.awin.api_key');
        $this->publisherId = config('services.awin.publisher_id');
        $this->cacheTtl    = config('services.awin.cache_ttl', 21600); // 6 hours
    }

    /**
     * Search relevant offers from Awin
     */
    public function searchOffers(string $keyword, int $limit = 6)
    {
        if (empty($this->apiKey) || empty($this->publisherId)) {
            return [];
        }

        $cacheKey = 'awin_offers_' . md5($keyword);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($keyword, $limit) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                ])->get("https://api.awin.com/publishers/{$this->publisherId}/reports", [
                    'keyword' => $keyword,
                    'limit'   => $limit,
                ]);

                if ($response->successful()) {
                    return collect($response->json())->take($limit)->map(function ($item) {
                        return [
                            'name'        => $item['advertiserName'] ?? 'Awin Partner',
                            'title'       => $item['campaignName'] ?? 'Special Offer',
                            'description' => $item['description'] ?? '',
                            'link'        => $item['trackingLink'] ?? '#',
                            'network'     => 'Awin',
                            'type'        => 'offer',
                        ];
                    });
                }
            } catch (\Exception $e) {
                \Log::error('Awin API Error: ' . $e->getMessage());
            }

            return [];
        });
    }
}