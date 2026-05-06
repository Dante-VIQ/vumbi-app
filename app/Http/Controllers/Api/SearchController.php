<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Search\QueryClassifierService;
use App\Services\Search\SearchService;
use App\Services\Search\RouteDecisionService;
use App\Jobs\BuildCityDiscoveryPage;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(
        Request $request,
        SearchService $searchService,
        QueryClassifierService $classifier,
        RouteDecisionService $router
    ) {
        $query = $request->q;

        $classification = $classifier->classify($query);
        $decision = $router->decide($classification);
        $results = $searchService->search($query);

        // 🔥 AUTO BUILD LOGIC
        if ($results['needs_build']) {

            BuildCityDiscoveryPage::dispatch($query);

            return response()->json([
                'status' => 'building',
                'message' => "We are preparing a travel guide for {$query}",
                'retry' => true,
                'query' => $query
            ]);
        }

        return response()->json([
            'status' => 'ready',
            'query' => $query,
            'classification' => $classification,
            'routing' => $decision,
            'results' => $results
        ]);
    }
}