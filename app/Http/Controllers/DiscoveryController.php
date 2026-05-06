<?php

namespace App\Http\Controllers;

use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use App\Services\Search\SearchService;
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

    public function search(Request $request, SearchService $searchService)
    {
        try {

            // FIX: unify request key with frontend
            $request->validate([
                'q' => 'required|string|min:2|max:255'
            ]);

            $searchTerm = trim($request->input('q'));

            // 1️⃣ Check if city exists
            $city = City::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('slug', Str::slug($searchTerm))
                ->first();

            // 2️⃣ If already published city → return full SEO page data
            if ($city && $city->status === 'published') {

                return response()->json([
                    'status' => 'ready',
                    'results' => [
                        'city' => $city->name,
                        'description' => $city->description ?? "Discover {$city->name}",
                        'places' => [],
                        'hotels' => [],
                        'flights' => []
                    ]
                ]);
            }

            // 3️⃣ Use FULL discovery engine (THIS IS THE KEY FIX)
            $results = $searchService->search($searchTerm);

            // 4️⃣ If needs build → queue job
            if ($results['needs_build'] ?? false) {

                BuildCityDiscoveryPage::dispatch($searchTerm);

                return response()->json([
                    'status' => 'building',
                    'message' => "We're preparing a travel guide for {$searchTerm}",
                    'results' => null
                ]);
            }

            // 5️⃣ Return unified results
            return response()->json([
                'status' => 'ready',
                'results' => $results
            ]);

        } catch (\Throwable $e) {

            Log::error('Discovery Search Error: ' . $e->getMessage(), [
                'search' => $request->input('q'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Server error. Please try again later.'
            ], 500);
        }
    }
}