<?php

// app/Http/Controllers/RedirectController.php
namespace App\Http\Controllers;

use App\Models\AffiliateClick;
use App\Models\Tour;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function affiliate(Request $request, string $source, int $tourId)
    {
        $tour = Tour::findOrFail($tourId);

        AffiliateClick::create([
            'source' => $source,
            'tour_id' => $tour->id,
            'referring_url' => $request->headers->get('referer'),
            'ip_hash' => hash('sha256', $request->ip()),
            'clicked_at' => now(),
        ]);

        $utm = http_build_query([
            'utm_source' => 'vumbiventures',
            'utm_medium' => 'referral',
            'utm_campaign' => $source,
        ]);

        $separator = str_contains($tour->affiliate_url, '?') ? '&' : '?';

        return redirect()->away($tour->affiliate_url . $separator . $utm);
    }
}