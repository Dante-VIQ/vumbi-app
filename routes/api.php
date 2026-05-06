<?php

use App\Http\Controllers\Api\DiscoveryController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;



Route::get('/search', SearchController::class);
// Route::get('/discover/{city}', [DiscoveryController::class, 'show']);