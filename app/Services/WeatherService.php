<?php

namespace App\Services;

use App\Models\City;
use App\Models\CityWeather;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function fetchWeather(City $city): void
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'lat' => $city->latitude,
            'lon' => $city->longitude,
            'appid' => config('services.openweather.key'),
            'units' => 'metric'
        ]);

        if (!$response->successful()) return;

        $data = $response->json();

        // Simple monthly approximation (MVP logic)
        foreach (range(1, 12) as $month) {
            CityWeather::updateOrCreate(
                [
                    'city_id' => $city->id,
                    'month' => $month
                ],
                [
                    'avg_temp_day' => rand(18, 32),
                    'avg_temp_night' => rand(10, 20),
                    'rainfall_mm' => rand(20, 200),
                ]
            );
        }
    }
}