<?php

namespace App\Http\Controllers;

use App\Services\Search\SearchOrchestratorService;
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
            ->orderBy('last_refreshed_at', 'desc')
            ->take(12)
            ->get();

                // Get featured packages (latest active packages, limit 6)
    $featuredPackages = \App\Models\PartnerPackage::active()
                            ->latest()
                            ->take(6)
                            ->get();
        return view('pages.discovery', compact('trendingCities', 'featuredPackages'));
    }

    /**
     * Main search endpoint (called by Alpine.js)
     */
    // public function search(Request $request, SearchOrchestratorService $orchestrator)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'q' => 'required|string|min:2|max:100',
    //         ]);

    //         $searchTerm = trim($validated['q']);

    //         // 1. Check if we already have a fully built city page
    //         $existingCity = $this->findExistingCity($searchTerm);

    //         if ($existingCity && $existingCity->status === 'published') {
    //             return $this->readyResponse($existingCity);
    //         }

    //         // 2. Perform fresh search via orchestrator
    //         $searchResult = $orchestrator->search($searchTerm);

    //         // 3. Dispatch background build job if quality is insufficient
    //         if ($searchResult->needsBuild()) {
    //             BuildCityDiscoveryPage::dispatch($searchTerm)
    //                 ->onQueue('city-build')
    //                 ->delay(now()->addSeconds(3)); // small delay to avoid race conditions
    //         }

    //         return response()->json([
    //             'status' => $searchResult->needsBuild() ? 'building' : 'ready',
    //             'result' => $searchResult->toArray(),
    //         ]);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Please enter a valid destination name.',
    //         ], 422);

    //     } catch (\Throwable $e) {
    //         Log::error('Discovery Search Failed', [
    //             'query' => $request->input('q'),
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => 'Unable to process your search at the moment. Please try again.',
    //             'result'  => null
    //         ], 500);
    //     }
    // }

    public function search(Request $request, SearchOrchestratorService $orchestrator)
{
    try {
        $searchTerm = trim($request->input('q'));
        Log::info('Search initiated', ['query' => $searchTerm]);

        $searchResult = $orchestrator->search($searchTerm);

        Log::info('Search completed successfully', [
            'query' => $searchTerm,
            'has_places' => count($searchResult->toArray()['places'] ?? []),
            'status' => $searchResult->needsBuild() ? 'building' : 'ready'
        ]);

        if ($searchResult->needsBuild()) {
            BuildCityDiscoveryPage::dispatch($searchTerm)->onQueue('city-build');
        }

        return response()->json([
            'status' => $searchResult->needsBuild() ? 'building' : 'ready',
            'result' => $searchResult->toArray(),
        ]);

    } catch (\Throwable $e) {
        Log::error('DiscoveryController Search Failed', [
            'query' => $request->input('q'),
            'error' => $e->getMessage(),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Search failed: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Find existing published city
     */
    private function findExistingCity(string $searchTerm): ?City
    {
        $slug = Str::slug($searchTerm);

        return City::where('slug', $slug)
            ->orWhere('name', 'LIKE', "%{$searchTerm}%")
            ->first();
    }

    /**
     * Standardized response when we have a ready city page
     */
    private function readyResponse(City $city): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'ready',
            'result' => [
                'city'                => $city->name,
                'description'         => $city->guide?->intro_text ?? "Discover the beauty of {$city->name}",
                'places'              => [],
                'hotels'              => [],
                'flights'             => [],
                'affiliate_deals'     => [],
                'cultural_info'       => ['content' => $city->cultural_info ?? ''],
                'educational_info'    => ['content' => $city->educational_info ?? ''],
                'best_time_to_visit'  => ['content' => ''],
                'visa_info'           => ['content' => ''],
                'nearby_destinations' => [],
                'weather'             => [],
                'data_quality'        => ['can_build' => true, 'quality_score' => 85],
                'meta'                => [
                    'source' => 'existing_city',
                    'cached_at' => now()->toIso8601String()
                ]
            ]
        ]);
    }
}