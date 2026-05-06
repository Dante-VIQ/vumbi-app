<?php

namespace App\Http\Controllers;

use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use Illuminate\Http\Request;
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
                'search' => 'required|string|max:255'   // Changed to match frontend
            ]);

            $searchTerm = $request->input('search');

            // Check if city exists and is ready
            $city = City::where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('slug', $searchTerm)
                        ->first();

            if ($city && $city->status === 'published') {
                return response()->json([
                    'status' => 'ready',
                    'redirect' => route('discover.city', $city->slug),
                    'data' => [
                        'place_info' => [
                            'name' => $city->name,
                            'description' => $city->description ?? 'Beautiful destination in ' . ($city->country->name ?? 'Africa'),
                            'avg_cost' => '$' . rand(80, 250)
                        ],
                        'stories' => [],
                        'hotels' => [],
                        'attractions' => [],
                    ],
                    'meta' => [
                        'total' => 1,
                        'source_health' => []
                    ]
                ]);
            }

            // Not ready → Dispatch job
            BuildCityDiscoveryPage::dispatch($searchTerm);

            return response()->json([
                'status' => 'building',
                'message' => 'We are preparing detailed information about ' . $searchTerm . '...',
                'data' => [],
                'meta' => ['total' => 0]
            ]);

        } catch (\Throwable $e) {
            Log::error('Discovery Search Error: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}