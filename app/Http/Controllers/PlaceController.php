<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
            ->withQueryString();   // Important: preserves search term in pagination links

        return view('destinations.index', compact('cultures'));
    }
}
