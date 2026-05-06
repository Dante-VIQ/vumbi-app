<?php

namespace App\Services;

use App\Models\City;
use App\Models\CityImage;
use Illuminate\Support\Facades\Http;

class ImageService
{
    private string $endpoint = "https://api.unsplash.com/search/photos";

    public function fetchCityImages(City $city): void
    {
        $response = Http::get($this->endpoint, [
            'query' => $city->name . ' city skyline',
            'per_page' => 8,
            'client_id' => config('services.unsplash.access_key')
        ]);

        if (!$response->successful()) return;

        foreach ($response['results'] as $image) {
            CityImage::create([
                'city_id' => $city->id,
                'image_url' => $image['urls']['regular'],
                'photographer_name' => $image['user']['name'],
                'source' => 'unsplash'
            ]);
        }
    }
}