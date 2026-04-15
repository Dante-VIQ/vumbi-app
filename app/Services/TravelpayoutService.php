<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TravelpayoutsService
{
    protected $apiUrl;
    protected $token;
    protected $marker;
    protected $cacheTtl;

    public function __construct()
    {
        $this->apiUrl = config('services.travelpayouts.api_url');
        $this->token = config('services.travelpayouts.token');
        $this->marker = config('services.travelpayouts.marker');
        $this->cacheTtl = config('services.travelpayouts.cache_ttl', 3600);
    }

    public function searchHotels(string $location, ?string $checkIn = null, ?string $checkOut = null, int $adults = 2, int $limit = 5): array
    {
        if (empty($this->token)) return [];

        $cacheKey = 'travelpayouts_hotels_' . md5($location . $checkIn . $checkOut . $adults . $limit);
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($location, $checkIn, $checkOut, $adults, $limit) {
            $response = Http::withHeaders(['X-Access-Token' => $this->token])
                ->get($this->apiUrl . '/hotels/search', [
                    'query' => $location,
                    'check_in' => $checkIn ?? now()->addDay()->toDateString(),
                    'check_out' => $checkOut ?? now()->addDays(3)->toDateString(),
                    'adults' => $adults,
                    'currency' => 'usd',
                    'limit' => $limit,
                    'marker' => $this->marker,
                ]);
            if ($response->failed()) return [];
            $data = $response->json();
            if (empty($data['data'])) return [];
            return $this->formatResults($data['data']);
        });
    }

    protected function formatResults(array $results): array
    {
        $formatted = [];
        foreach ($results as $hotel) {
            $formatted[] = [
                'name' => $hotel['hotel_name'] ?? $hotel['name'] ?? 'Accommodation',
                'price' => isset($hotel['price']) ? '$' . number_format($hotel['price'], 2) : 'Best rates',
                'image' => $hotel['image'] ?? $hotel['photo'] ?? null,
                'url' => $this->buildAffiliateLink($hotel),
            ];
        }
        return $formatted;
    }

    protected function buildAffiliateLink(array $hotel): string
    {
        $hotelId = $hotel['hotel_id'] ?? $hotel['id'] ?? 0;
        $checkIn = $hotel['checkIn'] ?? now()->addDay()->toDateString();
        $checkOut = $hotel['checkOut'] ?? now()->addDays(3)->toDateString();
        return "https://www.travelpayouts.com/hotels/?marker={$this->marker}&hotel_id={$hotelId}&checkIn={$checkIn}&checkOut={$checkOut}&adults=2";
    }
}