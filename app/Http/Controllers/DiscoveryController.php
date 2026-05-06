<?php

namespace App\Http\Controllers;

use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use Illuminate\Http\Request;

class DiscoveryController extends Controller
{

    public function index()
    {
        $trendingCities = City::with('country')
            // ->latest()
            ->limit(6)
            ->get();

        return view('pages.discovery', compact('trendingCities'));
    }

}