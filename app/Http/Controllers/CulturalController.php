<?php

namespace App\Http\Controllers;

use App\Models\Culture;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

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

        return view('cultures.give', compact('cultures'));
    }

        public function show(Culture $culture)
    {
        return view('pages.culture', [
            'culture' => $culture,
            'seo_title' => $culture->name . ' — Cultural Story | Vumbi Ventures',
            'seo_description' => Str::limit(strip_tags($culture->detail), 155),
            'seo_canonical' => url()->current(),
        ]);
    }
}
