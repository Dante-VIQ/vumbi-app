<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeoService
{
    private string $endpoint = "https://wft-geo-db.p.rapidapi.com/v1/geo/cities";

    public function createCityIfNotExists(string $cityName): City
    {
        $existing = City::where('name', $cityName)->first();
        if ($existing) {
            return $existing;
        }

        $response = Http::withHeaders([
            'X-RapidAPI-Key' => config('services.rapidapi.key'),
            'X-RapidAPI-Host' => 'wft-geo-db.p.rapidapi.com'
        ])->get($this->endpoint, [
            'namePrefix' => $cityName,
            'limit' => 1
        ]);

        if (!$response->successful() || empty($response['data'])) {
            throw new \Exception("City not found via GeoDB");
        }

        $data = $response['data'][0];

        $country = $this->storeCountry($data);
        $region  = $this->storeRegion($data, $country);

        return City::create([
            'country_id' => $country->id,
            'region_id' => $region?->id,
            'name' => $data['city'],
            'slug' => Str::slug($data['city']),
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'timezone' => $data['timezone'],
            'population' => $data['population'] ?? null,
        ]);
    }

    private function storeCountry(array $data): Country
    {
        return Country::firstOrCreate(
            ['name' => $data['country']],
            ['iso_code' => $data['countryCode']]
        );
    }

    private function storeRegion(array $data, Country $country): ?Region
    {
        if (!$data['region']) return null;

        return Region::firstOrCreate([
            'country_id' => $country->id,
            'name' => $data['region']
        ]);
    }
}