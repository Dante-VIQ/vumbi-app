<?php

namespace App\Http\Controllers;

use App\Models\Culture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CulturalController extends Controller
{
    
    public function index()
    {
        $search = request('search');

        $cultures = Culture::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('detail', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();   // Important: preserves search term in pagination links

        return view('cultures.index', compact('cultures'));
    }

}
