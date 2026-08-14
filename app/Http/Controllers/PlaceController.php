<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    public function index()
    {
        $search = request('search');

        $destinations = Destination::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('detail', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('destinations.give', compact('destinations'));
    }

    public function show(Destination $destination)
    {
        // Eager load relationships
        $destination->load('tours');

        // Get related destinations
        $relatedDestinations = Destination::where('id', '!=', $destination->id)
            ->where(function ($query) use ($destination) {
                $query->where('location', 'like', "%{$destination->location}%")
                      ->orWhere('name', 'like', "%{$destination->location}%");
            })
            ->latest()
            ->limit(3)
            ->get();

        return view('pages.destination', [
            'destination'          => $destination,
            'relatedDestinations'  => $relatedDestinations,
            'seo_title'            => $destination->name . ' — Destination Story | Vumbi Ventures',
            'seo_description'      => Str::limit(strip_tags($destination->detail ?? ''), 155),
            'seo_canonical'        => url()->current(),
        ]);
    }
}