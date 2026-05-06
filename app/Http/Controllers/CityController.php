<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function show(string $country, string $city)
    {
        $city = City::where('slug', $city)
            ->whereHas('country', function ($q) use ($country) {
                $q->where('name', $country);
            })
            ->with([
                'country',
                'region',
                'guide',
                'places.category',
                'places.images',
                'hotels.prices',
                'costs',
                'weather',
            ])
            ->firstOrFail();

        return view('discover.city', compact('city'));
    }
}