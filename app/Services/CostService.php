<?php

namespace App\Services;

use App\Models\City;
use App\Models\CityCost;

class CostService
{
    public function generateMockCostData(City $city): void
    {
        // In production replace with Numbeo API
        CityCost::updateOrCreate(
            ['city_id' => $city->id],
            [
                'meal_price' => rand(2, 10),
                'transport_ticket' => rand(1, 3),
                'taxi_start' => rand(2, 5),
                'coffee_price' => rand(1, 4),
                'beer_price' => rand(2, 6),

                'budget_daily_low' => rand(10, 20),
                'budget_daily_mid' => rand(25, 60),
                'budget_daily_high' => rand(80, 150),

                'currency' => 'USD',
            ]
        );
    }
}