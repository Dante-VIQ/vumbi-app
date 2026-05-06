<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Discovery\DiscoveryService;

class DiscoveryController extends Controller
{
    public function show(string $city, DiscoveryService $service)
    {
        return response()->json(
            $service->getCityData($city)
        );
    }
}