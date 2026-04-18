<?php

namespace App\Services;

use App\Services\BonusArriveService;
use App\Services\TravelPayoutsService;
use App\Services\AwinService;


class AffiliateExecutionService
{
    public function execute(array $plan, ?string $location): array
    {
        $results = [];

        foreach ($plan as $intent => $data) {

            foreach ($data['networks'] as $network) {

                if ($intent === 'hotels' && $network === 'travelpayouts') {
                    $results['hotels'] = app(TravelPayoutsService::class)
                        ->searchHotels($location, 4);
                }

                if ($intent === 'flights' && $network === 'bonus_arrive') {
                    $results['flights'] = app(BonusArriveService::class)
                        ->searchFlights($location);
                }

                if ($intent === 'tours' && $network === 'awin') {
                    $results['tours'] = app(AwinService::class)
                        ->searchTours($location);
                }

            }
        }

        return $results;
    }
}
