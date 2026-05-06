<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Search\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request, SearchService $searchService)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        return response()->json(
            $searchService->search($request->q)
        );
    }
}