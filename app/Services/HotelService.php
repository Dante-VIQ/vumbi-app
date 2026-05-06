<?php

namespace App\Services;

use App\Models\City;
use App\Models\Hotel;
use App\Services\ApiClients\TravelpayoutsClient;

class HotelService
{
    public function __construct(
        private TravelpayoutsClient $client
    ) {}

    public function build(City $city): void
    {
        $hotels = $this->client->getHotels($city->name);

        foreach ($hotels as $hotel) {
            Hotel::updateOrCreate(
                ['external_id' => $hotel['id']],
                [
                    'city_id' => $city->id,
                    'name' => $hotel['name'],
                    'affiliate_url' => $hotel['bookingUrl'] ?? null,
                    'rating' => $hotel['stars'] ?? null,
                ]
            );
        }
    }
}