<?php

use App\Http\Controllers\Api\DiscoveryController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\InternalAffiliateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/search', SearchController::class);
// Route::get('/discover/{city}', [DiscoveryController::class, 'show']);

// routes/api.php
Route::get('/packages/search', function (Request $request) {
    $q = $request->input('q');
    if (!$q) return response()->json([]);
    $service = new \App\Services\LocalPartnerService();
    return response()->json($service->getPackages($q, 10));
});


Route::prefix('internal/affiliate')
    ->middleware(['internal.api'])
    ->group(function () {
        Route::get('/flights',      [InternalAffiliateController::class, 'searchFlights']);
        Route::get('/hotels',       [InternalAffiliateController::class, 'searchHotels']);
        Route::get('/link',         [InternalAffiliateController::class, 'buildLink']);
        Route::get('/performance',  [InternalAffiliateController::class, 'performance']);
    });