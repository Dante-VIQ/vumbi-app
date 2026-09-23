<?php

namespace App\Http\Controllers;

use App\Services\TravelPayouts\FlightService;
use App\Services\TravelPayouts\HotelService;
use App\Services\BonusArriveService;
use App\Services\AwinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InternalAffiliateController extends Controller
{
    public function __construct(
        private readonly FlightService $flights,
        private readonly HotelService $hotels,
    ) {}

public function searchFlights(Request $request)
{
    $validated = $request->validate([
        'destination' => 'nullable|string|max:100',  // kept for compatibility
        'origin'      => 'nullable|string|size:3',
        'limit'       => 'nullable|integer|min:1|max:15',
    ]);

    try {
        $flights = $this->flights->searchFlights(
            'NBO',                                      // always to Nairobi
            $validated['limit'] ?? 8,
            isset($validated['origin']) ? [$validated['origin']] : []
        );

        return response()->json([
            'success'     => true,
            'destination' => 'Nairobi (NBO)',
            'count'       => count($flights),
            'flights'     => $this->normalizeFlights($flights),
        ]);
    } catch (\Throwable $e) {
        Log::error('Internal affiliate: flight search failed', [
            'error' => $e->getMessage(),
        ]);
        return response()->json([
            'success' => false,
            'error'   => 'Flight search failed',
        ], 500);
    }
}
   /**
     * GET /internal/affiliate/hotels
     */
    public function searchHotels(Request $request)
    {
        $validated = $request->validate([
            'location'  => 'nullable|string|max:100',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'limit'     => 'nullable|integer|min:1|max:20',
        ]);

        try {
            if (isset($validated['latitude'], $validated['longitude'])) {
                $hotels = $this->hotels->searchByCoordinates(
                    (float) $validated['latitude'],
                    (float) $validated['longitude'],
                    $validated['limit'] ?? 8
                );
            } elseif (!empty($validated['location'])) {
                $hotels = $this->hotels->searchHotels(
                    $validated['location'],
                    $validated['limit'] ?? 8
                );
            } else {
                return response()->json([
                    'success' => false,
                    'error'   => 'Provide location OR lat/lon',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'source'  => 'travelpayouts',
                'location' => $validated['location'] ?? null,
                'count'   => count($hotels),
                'hotels'  => $this->normalizeHotels($hotels, $validated['location'] ?? null),
            ]);
        } catch (\Throwable $e) {
            Log::error('Internal affiliate: hotel search failed', [
                'location' => $validated['location'] ?? null,
                'error'    => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'error'   => 'Hotel search failed',
            ], 500);
        }
    }

    /**
     * GET /internal/affiliate/link
     * Builds a tracked affiliate link for the destination.
     */
    public function buildLink(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:100',
            'type'        => 'required|in:flight,hotel,tour',
        ]);

        $baseUrl = match ($validated['type']) {
            'flight' => 'https://aviasales.tp.st',
            'hotel'  => 'https://hotellook.tp.st',
            'tour'   => 'https://awin1.com',
        };

        // Build a search URL the user can click through to
        $query = match ($validated['type']) {
            'flight' => '?destination=' . urlencode($validated['destination']),
            'hotel'  => '?city=' . urlencode($validated['destination']),
            'tour'   => '?q=' . urlencode($validated['destination']),
        };

        return response()->json([
            'success'     => true,
            'destination' => $validated['destination'],
            'type'        => $validated['type'],
            'affiliate_url' => $baseUrl . $query,
        ]);
    }

    /**
     * GET /internal/affiliate/performance
     * Returns affiliate revenue stats (from wherever you track them).
     */
    public function performance(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|integer',
            'days'     => 'nullable|integer|min:1|max:365',
        ]);

        // Adjust this to your actual commissions table
        $days = $validated['days'] ?? 30;

        // Example shape — replace with real queries when you have commission data
        return response()->json([
            'success'         => true,
            'brand_id'        => $validated['brand_id'],
            'period_days'     => $days,
            'total_commission' => 0,
            'total_bookings'  => 0,
            'top_destinations' => [],
            'message'         => 'Commission tracking not yet connected.',
        ]);
    }

    // --- Normalizers ---
private function normalizeFlights(array $flights): array
{
    $marker = config('services.travelpayouts.marker') ?? env('TRAVELPAYOUTS_MARKER', '');

    return collect($flights)->map(function ($f) use ($marker) {
        // Build full URL from relative path
        $rawLink = $f['link'] ?? '';
        $fullUrl = null;
        
        if (!empty($rawLink)) {
            // If it's already absolute, use it. Otherwise prefix with aviasales.com
            $fullUrl = str_starts_with($rawLink, 'http')
                ? $rawLink
                : 'https://www.aviasales.com' . $rawLink;

            // Append marker for affiliate tracking
            if ($marker) {
                $separator = str_contains($fullUrl, '?') ? '&' : '?';
                $fullUrl .= $separator . 'marker=' . $marker;
            }
        }

        return [
            'origin'      => $f['origin'] ?? null,
            'destination' => 'NBO',
            'airline'     => $f['airline'] ?? 'Unknown',
            'price'       => $f['price'] ?? null,
            'currency'    => 'USD',
            'departure'   => $f['departure_at'] ?? null,
            'transfers'   => $f['transfers'] ?? 0,
            'link'        => $fullUrl,
            'gate'        => $f['gate'] ?? null,
        ];
    })->values()->all();
}

    private function normalizeHotels(array $hotels, ?string $location): array
    {
        return collect($hotels)->map(function ($h) use ($location) {
            return [
                'name'     => $h['name'] ?? $h['hotelName'] ?? 'Unknown',
                'stars'    => $h['stars'] ?? null,
                'price'    => $h['priceFrom'] ?? $h['price'] ?? null,
                'currency' => $h['currency'] ?? 'USD',
                'link'     => $h['link'] ?? $h['url'] ?? null,
                'location' => $location,
            ];
        })->values()->all();
    }
}
