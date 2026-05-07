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

            $request->validate([
                'q' => 'required|string|min:2|max:255'
            ]);

            $searchTerm = trim($request->input('q'));

            /*
            |------------------------------------------------------
            | 1. CHECK EXISTING CITY
            |------------------------------------------------------
            */

            $city = City::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('slug', Str::slug($searchTerm))
                ->first();

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

            /*
            |------------------------------------------------------
            | 2. MAIN DISCOVERY ENGINE (ONLY SOURCE OF TRUTH)
            |------------------------------------------------------
            */

            $results = $searchService->search($searchTerm);

            /*
            |------------------------------------------------------
            | 3. BUILD JOB IF REQUIRED
            |------------------------------------------------------
            */

            if ($results['needs_build'] ?? false) {

                BuildCityDiscoveryPage::dispatch($searchTerm);

                return response()->json([
                    'status' => 'building',
                    'message' => "We're preparing a travel guide for {$searchTerm}",
                    'results' => null
                ]);
            }

            /*
            |------------------------------------------------------
            | 4. RETURN FINAL RESULTS
            |------------------------------------------------------
            */

            return response()->json([
                'status' => 'ready',
                'results' => $results
            ]);

        } catch (\Throwable $e) {

            Log::error('Discovery Search Error', [
                'message' => $e->getMessage(),
                'search' => $request->input('q'),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Server error. Please try again later.'
            ], 500);
        }
    }
}