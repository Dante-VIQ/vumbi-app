<?php

namespace App\Http\Controllers;

use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                'search' => 'required|string|min:2|max:255'
            ]);

            $searchTerm = trim($request->input('search'));

            // Look for existing city
            $city = City::where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('slug', Str::slug($searchTerm))
                        ->first();

            if ($city && $city->status === 'published') {
                return response()->json([
                    'status' => 'ready',
                    'redirect' => route('discover.city', $city->slug),
                    'data' => [
                        'place_info' => [
                            'name' => $city->name,
                            'description' => $city->description ?? "Discover the beauty of {$city->name}",
                            'avg_cost' => '$120 - $250'
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

            // Dispatch job to build the page
            BuildCityDiscoveryPage::dispatch($searchTerm);

            return response()->json([
                'status' => 'building',
                'message' => "We're preparing detailed information about {$searchTerm}...",
                'data' => [],
                'meta' => ['total' => 0]
            ]);

        } catch (\Throwable $e) {
            Log::error('Discovery Search Error: ' . $e->getMessage(), [
                'search' => $request->input('search'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Server error. Please try again later.'
            ], 500);
        }
    }
}