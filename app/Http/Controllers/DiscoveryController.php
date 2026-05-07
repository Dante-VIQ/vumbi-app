<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\SearchResult;
use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use App\Services\Search\SearchOrchestratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DiscoveryController extends Controller
{
    public function index()
    {
        $trendingCities = City::where('is_published', true)
            ->orderBy('last_refreshed_at', 'desc')
            ->take(10)
            ->get();

        return view('pages.discovery', compact('trendingCities'));
    }

    public function search(Request $request, SearchOrchestratorService $orchestrator)
    {
        try {
            $validated = $request->validate([
                'q' => 'required|string|min:2|max:255',
            ]);

            $searchTerm = trim($validated['q']);

            // 1. Check already published city
            $city = City::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('slug', Str::slug($searchTerm))
                ->first();

            if ($city && $city->status === 'published') {
                return response()->json([
                    'status' => 'ready',
                    'result' => [
                        'city'         => $city->name,
                        'description'  => $city->description ?? "Discover {$city->name}",
                        'places'       => [],
                        'hotels'       => [],
                        'flights'      => [],
                        'data_quality' => [],
                        'meta'         => [],
                        'error'        => null,
                    ],
                ]);
            }

            // 2. New search via orchestrator
            $searchResult = $orchestrator->search($searchTerm);

            // 3. Dispatch build job if needed – but still return the partial result
            if ($searchResult->needsBuild()) {
                BuildCityDiscoveryPage::dispatch($searchTerm)->onQueue('city-build');
            }

            return response()->json([
                'status' => $searchResult->needsBuild() ? 'building' : 'ready',
                'result' => $searchResult->toArray(),   // <-- always an object, never null
            ]);

        } catch (\Throwable $e) {
            Log::error('Discovery Search Error', [
                'search' => $request->input('q'),
                'error'  => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Server error. Please try again later.',
                'result'  => [],    // still an array, not null
            ], 500);
        }
    }
}