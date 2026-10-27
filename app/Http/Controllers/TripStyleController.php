<?php

namespace App\Http\Controllers;

use App\Models\PartnerPackage;
use App\Support\Persona;
use App\Support\TripStyles;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;

class TripStyleController extends Controller
{
    public function show(string $style)
    {
        $config = TripStyles::find($style);
        abort_unless($config, 404);

        // Visiting a trip-style page is a clear signal of intent: remember it.
        Cookie::queue(Persona::COOKIE, $config['persona'], 60 * 24 * 365);

        $tours = TripStyles::tours($style, 3);
        $isFallback = $tours->isEmpty();

        if ($isFallback) {
            // Nothing tagged yet for this style: show the newest trips, labelled honestly.
            $tours = PartnerPackage::active()->latest()->take(3)->get();
        }

        return view('trips.show', [
            'slug' => $style,
            'style' => $config,
            'persona' => $config['persona'],
            'tours' => $tours,
            'isFallback' => $isFallback,
            'priceRange' => TripStyles::priceRange($style),
            'posts' => TripStyles::relatedPosts($style, 3),
            'others' => collect(TripStyles::all())->except($style),
        ]);
    }
}
