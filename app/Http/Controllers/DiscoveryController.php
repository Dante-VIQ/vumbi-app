<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscoverySearchRequest;
use App\Services\DiscoveryService;
use Illuminate\Http\JsonResponse;

class DiscoveryController extends Controller
{
    public function __construct(
        private DiscoveryService $discoveryService
    ) {}


public function __invoke(DiscoverySearchRequest $request): JsonResponse
{
    $searchTerm = $request->searchTerm();
    $interest   = $request->interest();
    $results    = $this->discoveryService->search($searchTerm, $interest);

    $health = [];
    $data   = [];
    foreach ($results as $key => $value) {
        $health[$key] = $value['health'];
        $data[$key]   = $value['items'];
    }

    return response()->json([
        'data' => $data,
        'meta' => [
            'place_name'    => $searchTerm,
            'source_health' => $health,
            'total'         => array_sum(array_map(fn($s) => is_array($s) || $s instanceof \Countable ? count($s) : ($s ? 1 : 0), $data)),
        ],
    ]);
}
}