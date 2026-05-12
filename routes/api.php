<?php

use App\Http\Controllers\Api\DiscoveryController;
use App\Http\Controllers\Api\SearchController;
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