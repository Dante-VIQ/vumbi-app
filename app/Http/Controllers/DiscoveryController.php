<?php

namespace App\Http\Controllers;

use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class DiscoveryController extends Controller
{
    public function index()
    {
        $trendingCities = City::where('is_published', true)
            ->orderBy('is_published', 'desc')
            ->take(10)
            ->get();
            
        return view('pages.discovery', compact('trendingCities'));
    }

    public function search(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|max:255'
            ]);

            $query = $request->input('query');

            // Check if city already exists
            $city = City::where('name', $query)->first();

            if ($city && $city->status === 'published') {
                return response()->json([
                    'status' => 'ready',
                    'redirect' => route('discover.city', $city->slug)
                ]);
            }

            // If not, dispatch job to build it
            BuildCityDiscoveryPage::dispatch($query);

            return response()->json([
                'status' => 'building',
                'message' => 'We are preparing your destination...'
            ]);

        } catch (\Throwable $e) {

            Log::error($e);

            return response()->json([
                'error' => true,
                'message' => 'Server error'
            ], 500);
        }
    }
}