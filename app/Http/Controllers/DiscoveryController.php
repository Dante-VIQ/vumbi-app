<?php

namespace App\Http\Controllers;

use App\Jobs\BuildCityDiscoveryPage;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

public function search(Request $request)
{
    $request->validate(['search' => 'required|string|max:255']);

    $searchTerm = $request->input('search');

    $city = City::where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('slug', Str::slug($searchTerm))
                ->first();

    if ($city && $city->status === 'published') {
        return response()->json([
            'status' => 'ready',
            'redirect' => route('discover.city', $city->slug),
            'data' => [ /* ... */ ],
            'meta' => ['total' => 1]
        ]);
    }

    // Dispatch job (even if city is building)
    BuildCityDiscoveryPage::dispatch($searchTerm);

    return response()->json([
        'status' => 'building',
        'message' => "We're gathering rich information about <strong>{$searchTerm}</strong>...",
        'data' => [],
        'meta' => ['total' => 0]
    ]);
}


}