<?php

namespace App\Http\Controllers;

use App\Models\PartnerPackage;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = PartnerPackage::active();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($difficulty = $request->input('difficulty')) {
            $query->where('difficulty', $difficulty);
        }
        if ($min = $request->input('min_price')) {
            $query->where('price', '>=', (float)$min);
        }
        if ($max = $request->input('max_price')) {
            $query->where('price', '<=', (float)$max);
        }
        if ($location = $request->input('location')) {
            $query->where('location', 'like', "%{$location}%");
        }

        // Sort
        match ($request->input('sort')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest'     => $query->latest(),
            default      => $query->latest(),
        };

        $packages = $query->paginate(12);

        // Filter options
        $types       = PartnerPackage::active()->distinct()->pluck('type');
        $difficulties = PartnerPackage::active()->whereNotNull('difficulty')->distinct()->pluck('difficulty');
        $locations    = PartnerPackage::active()->distinct()->pluck('location');

        return view('tours.index', compact('packages', 'types', 'difficulties', 'locations'));
    }

    public function show(PartnerPackage $package)
    {
        abort_unless($package->active, 404);

        $similar = PartnerPackage::active()
                    ->where('id', '!=', $package->id)
                    ->where('type', $package->type)
                    ->take(4)
                    ->get();

        return view('tours.show', compact('package', 'similar'));
    }
}