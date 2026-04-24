<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TravelPayoutsService
{
    protected string $token;
    protected string $marker;
    protected string $baseUrl;

    public function __construct()
    {
        $this->token   = config('services.travelpayouts.token');
        $this->marker  = config('services.travelpayouts.marker');
        $this->baseUrl = config('services.travelpayouts.base_url');
    }

    /**
     * Search hotels by location name.
     */
    public function searchHotels(string $location, int $limit = 6): array
    {
        try {
            $response = Http::withToken($this->token)
                ->get("{$this->baseUrl}/v2/prices/latest", [
                    'currency'   => 'usd',
                    'limit'      => $limit,
                    'token'      => $this->token,
                    'marker'     => $this->marker,
                    'destination' => $this->resolveLocation($location),
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return $this->formatHotelResults($data['data'] ?? [], $limit);
            }

            Log::error('Travelpayouts Hotels API failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Travelpayouts Hotels exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return [];
        }
    }

    /**
     * Search tours/experiences.
     */
    public function searchTours(string $location, int $limit = 6): array
    {
        try {
            $response = Http::withToken($this->token)
                ->get("{$this->baseUrl}/v2/tours/search", [
                    'term'     => $location,
                    'limit'    => $limit,
                    'token'    => $this->token,
                    'marker'   => $this->marker,
                ]);

            if ($response->successful()) {
                return $this->formatTourResults($response->json(), $limit);
            }

            Log::error('Travelpayouts Tours API failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Travelpayouts Tours exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return [];
    }

    /**
     * Map a location string to a Travelpayouts destination code.
     * This is a simplified example; you might use a database table or external lookup.
     */
    protected function resolveLocation(string $location): string
    {
        // Commonly searched destinations for Kenya
        $map = [
            'maasai mara' => 'KE',
            'diani'       => 'KE',
            'nakuru'      => 'KE',
            'lamu'        => 'KE',
            'nairobi'     => 'NBO',
            'mombasa'     => 'MBA',
            'kenya'       => 'KE',
        ];

        $key = strtolower(trim($location));

        return $map[$key] ?? 'KE'; // default to Kenya
    }

    /**
     * Format raw hotel data into clean array for the front-end.
     */
    private function formatHotelResults(array $hotels, int $limit): array
    {
        $results = [];

        foreach (array_slice($hotels, 0, $limit) as $hotel) {
            $results[] = [
                'name'   => $hotel['hotel_name'] ?? 'Hotel',
                'image'  => $hotel['hotel_image'] ?? null,
                'price'  => isset($hotel['price']) ? '$' . $hotel['price'] : 'N/A',
                'url'    => $hotel['link'] ?? '#',
                'rating' => $hotel['stars'] ?? null,
            ];
        }

        return $results;
    }

    /**
     * Format raw tour data.
     */
    private function formatTourResults(array $data, int $limit): array
    {
        $results = [];

        foreach (array_slice($data['data'] ?? [], 0, $limit) as $tour) {
            $results[] = [
                'name'     => $tour['title'] ?? 'Tour',
                'price'    => isset($tour['price']) ? '$' . $tour['price'] : 'N/A',
                'duration' => $tour['duration'] ?? '',
                'url'      => $tour['url'] ?? '#',
            ];
        }

        return $results;
    }
}