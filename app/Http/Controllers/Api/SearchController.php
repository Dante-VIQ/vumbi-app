<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\BuildCityDiscoveryPage;
use App\Services\BonusArriveService;
use App\Services\Search\QueryClassifierService;
use App\Services\Search\RouteDecisionService;
use App\Services\Search\SearchOrchestratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function __invoke(
        Request $request,
        SearchOrchestratorService $orchestrator,
        QueryClassifierService $classifier,
        RouteDecisionService $router
    ): JsonResponse {
        $query = $request->get('q');

        // Validation
        if (empty($query) || !is_string($query)) {
            return $this->errorResponse('Search query is required.', Response::HTTP_BAD_REQUEST);
        }

        $query = trim($query);
        if (strlen($query) < 2 || strlen($query) > 100) {
            return $this->errorResponse('Query must be between 2 and 100 characters.', Response::HTTP_BAD_REQUEST);
        }

        // Rate Limiting
        $key = $request->user()?->id ?? $request->ip();
        if (RateLimiter::tooManyAttempts('search:' . $key, 12)) {
            return $this->errorResponse('Too many requests. Please slow down.', Response::HTTP_TOO_MANY_REQUESTS);
        }
        RateLimiter::hit('search:' . $key, 60);

        try {
            $result = $orchestrator->search($query);

            // Auto Build Logic
            if ($result->needsBuild()) {
                BuildCityDiscoveryPage::dispatch($query)
                    ->onQueue('city-build');

                Log::info('City page build job dispatched', ['city' => $query]);

                return response()->json([
                    'status'   => 'building',
                    'message'  => "We're creating a detailed travel guide for " . ucwords($query),
                    'query'    => $query,
                    'retry_in' => 10,
                    'result'   => $result->toArray()
                ], Response::HTTP_ACCEPTED);
            }

            // Ready
            return response()->json([
                'status' => 'ready',
                'query'  => ucwords($query),
                'result' => $result->toArray(),
            ]);

        } catch (\Exception $e) {
            Log::error('SearchController error', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('An error occurred while processing your request.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function errorResponse(string $message, int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
        ], $status);
    }
}