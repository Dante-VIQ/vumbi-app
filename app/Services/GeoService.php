<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class GeoService
{
    private const ENDPOINT = "https://wft-geo-db.p.rapidapi.com/v1/geo/cities";

    // inside GeoService.php

// app/Services/GeoService.php

public function geocode(string $cityName): ?array
{
    try {
        $response = Http::timeout(10)
            ->withHeaders([
                'X-RapidAPI-Key'  => config('services.rapidapi.key'),
                'X-RapidAPI-Host' => 'wft-geo-db.p.rapidapi.com',
            ])
            ->get(self::ENDPOINT, [
                'namePrefix' => $cityName,
                'limit'      => 1,
            ]);

        if ($response->successful() && !empty($response['data'])) {
            $data = $response['data'][0];
            return [
                'lat'          => $data['latitude'],
                'lon'          => $data['longitude'],
                'country'      => $data['country'],
                'country_code' => $data['countryCode'] ?? null,
            ];
        }
    } catch (Exception $e) {
        Log::warning("GeoService geocode failed", ['city' => $cityName]);
    }

    return null;
}

    public function createCityIfNotExists(string $cityName): City
    {
        $cityName = trim($cityName);
        
        $existing = City::where('name', $cityName)
                        ->orWhere('slug', Str::slug($cityName))
                        ->first();

        if ($existing) {
            return $existing;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-RapidAPI-Key'  => config('services.rapidapi.key'),
                    'X-RapidAPI-Host' => 'wft-geo-db.p.rapidapi.com',
                ])
                ->get(self::ENDPOINT, [
                    'namePrefix' => $cityName,
                    'limit'      => 1,
                    'minPopulation' => 50000, // avoid very small places
                ]);

            if (!$response->successful() || empty($response['data'])) {
                throw new Exception("City not found in GeoDB: " . $cityName);
            }

            $data = $response['data'][0];

            return $this->createCityFromGeoData($data);

        } catch (Exception $e) {
            Log::error("GeoService failed", [
                'city' => $cityName,
                'error' => $e->getMessage()
            ]);

            // Fallback: Create minimal city record
            return City::firstOrCreate(
                ['slug' => Str::slug($cityName)],
                ['name' => $cityName]
            );
        }
    }

    private function createCityFromGeoData(array $data): City
    {
        $country = $this->storeCountry($data);
        $region  = $this->storeRegion($data, $country);

        return City::create([
            'country_id' => $country->id,
            'region_id'  => $region?->id,
            'name'       => $data['city'],
            'slug'       => Str::slug($data['city']),
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'timezone'   => $data['timezone'] ?? null,
            'population' => $data['population'] ?? null,
        ]);
    }

    private function storeCountry(array $data): Country
    {
        return Country::firstOrCreate(
            ['name' => $data['country']],
            ['iso_code' => $data['countryCode'] ?? null]
        );
    }

    private function storeRegion(array $data, Country $country): ?Region
    {
        if (empty($data['region'])) {
            return null;
        }

        return Region::firstOrCreate([
            'country_id' => $country->id,
            'name'       => $data['region']
        ]);
    }
}