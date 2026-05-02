<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Culture;
use App\Models\Destination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\Concurrency\run;

class DiscoveryService
{
    public function __construct(
        private readonly TravelPayoutsService   $travelPayoutsService,
        private readonly BonusArriveService     $bonusArriveService,
        private readonly AwinService            $awinService,
        private readonly PlaceDiscoveryService  $placeDiscoveryService,
        private readonly CuratedContentService  $curatedContentService
    ) {}

    public function search(string $search, ?string $interest = null): array
    {
        $search = trim($search);
        $interest = $interest ?? $this->detectInterest($search);

        $cacheKey = 'discovery:' . md5(strtolower($search) . '|' . ($interest ?? 'all'));

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($search, $interest) {
            try {
                if (function_exists('Illuminate\Support\Concurrency\run')) {
                    [$stories, $hotels, $tours, $flights, $awinOffers, $placeInfo, $attractions] = run([
                        fn() => $this->fetchStories($search, $interest),
                        fn() => $this->fetchWithFallback('hotel', $search, fn($s) => $this->travelPayoutsService->searchHotels($s, 6), $interest),
                        fn() => $this->fetchWithFallback('tour', $search, fn($s) => $this->travelPayoutsService->searchTours($s, 6), $interest),
                        fn() => $this->fetchWithFallback('flight', $search, fn($s) => $this->bonusArriveService->searchFlights($s, 5), $interest),
                        fn() => $this->fetchWithFallback('offer', $search, fn($s) => $this->awinService->searchOffers($s, 6), $interest),
                        fn() => $this->fetchPlaceInfo($search),
                        fn() => $this->fetchAttractions($search),
                    ]);
                } else {
                    // Sequential fallback
                    $stories     = $this->fetchStories($search, $interest);
                    $hotels      = $this->fetchWithFallback('hotel', $search, fn($s) => $this->travelPayoutsService->searchHotels($s, 6), $interest);
                    $tours       = $this->fetchWithFallback('tour', $search, fn($s) => $this->travelPayoutsService->searchTours($s, 6), $interest);
                    $flights     = $this->fetchWithFallback('flight', $search, fn($s) => $this->bonusArriveService->searchFlights($s, 5), $interest);
                    $awinOffers  = $this->fetchWithFallback('offer', $search, fn($s) => $this->awinService->searchOffers($s, 6), $interest);
                    $placeInfo   = $this->fetchPlaceInfo($search);
                    $attractions = $this->fetchAttractions($search);
                }

                return [
                    'stories'      => $stories,
                    'hotels'       => $hotels,
                    'tours'        => $tours,
                    'flights'      => $flights,
                    'awin_offers'  => $awinOffers,
                    'place_info'   => $placeInfo,
                    'attractions'  => $attractions,
                ];

            } catch (\Exception $e) {
                Log::error('Discovery search failed', [
                    'search' => $search,
                    'interest' => $interest,
                    'error' => $e->getMessage()
                ]);

                return [
                    'stories' => ['items' => [], 'health' => 'empty'],
                    'hotels' => ['items' => [], 'health' => 'empty'],
                    'tours' => ['items' => [], 'health' => 'empty'],
                    'flights' => ['items' => [], 'health' => 'empty'],
                    'awin_offers' => ['items' => [], 'health' => 'empty'],
                    'place_info' => ['items' => null, 'health' => 'empty'],
                    'attractions' => ['items' => [], 'health' => 'empty'],
                ];
            }
        });
    }

    private function fetchWithFallback(string $type, string $search, callable $apiCall, ?string $interest = null): array
    {
        try {
            $results = $apiCall($search);

            if (!empty($results)) {
                return [
                    'items'  => $results,
                    'health' => 'api',
                ];
            }
        } catch (\Exception $e) {
            Log::warning("{$type} API failed, falling back to curated content", [
                'error' => $e->getMessage(),
                'search' => $search
            ]);
        }

        // Fallback to curated content
        $fallback = match ($type) {
            'hotel'  => $this->curatedContentService->getHotels($search, 6, $interest),
            'tour'   => $this->curatedContentService->getTours($search, 6, $interest),
            'flight' => $this->curatedContentService->getFlights($search, 5, $interest),
            'offer'  => $this->curatedContentService->getOffers($search, 6, $interest),
            default  => collect([]),
        };

        return [
            'items'  => $fallback->toArray(),
            'health' => $fallback->isNotEmpty() ? 'curated' : 'empty',
        ];
    }

    private function fetchStories(string $search, ?string $interest = null): array
    {
        $searchTerm = "%{$search}%";

        $query = Blog::query()
            ->select('id', 'title', 'description', 'created_at', 'slug', DB::raw("'blog' as type"))
            ->where('title', 'like', $searchTerm)
            ->orWhere('description', 'like', $searchTerm);

        $query->union(
            Destination::query()
                ->select('id', 'title', 'description', 'created_at', 'slug', DB::raw("'destination' as type"))
                ->where('title', 'like', $searchTerm)
                ->orWhere('description', 'like', $searchTerm)
        );

        $query->union(
            Culture::query()
                ->select('id', 'title', 'description', 'created_at', 'slug', DB::raw("'culture' as type"))
                ->where('title', 'like', $searchTerm)
                ->orWhere('description', 'like', $searchTerm)
        );

        if ($interest) {
            $query->where(function ($q) use ($interest) {
                $q->whereJsonContains('tags', $interest)  // Recommended: use JSON column
                  ->orWhere('category', $interest);
            });
        }

        $stories = $query->orderBy('created_at', 'desc')
                         ->limit(6)
                         ->get();

        return [
            'items'  => $stories,
            'health' => $stories->isNotEmpty() ? 'internal' : 'empty',
        ];
    }

    private function fetchPlaceInfo(string $search): array
    {
        try {
            $info = $this->placeDiscoveryService->getPlaceSummary($search);

            return [
                'items'  => $info,
                'health' => $info ? 'api' : 'empty',
            ];
        } catch (\Exception $e) {
            Log::warning("Place info failed", ['search' => $search, 'error' => $e->getMessage()]);
            return ['items' => null, 'health' => 'empty'];
        }
    }

    private function fetchAttractions(string $search): array
    {
        try {
            $attractions = $this->placeDiscoveryService->getAttractions($search, 6);

            return [
                'items'  => $attractions,
                'health' => !empty($attractions) ? 'api' : 'empty',
            ];
        } catch (\Exception $e) {
            Log::warning("Attractions fetch failed", ['search' => $search, 'error' => $e->getMessage()]);
            return ['items' => [], 'health' => 'empty'];
        }
    }

    /**
     * Auto-detect interest from search text
     */
    private function detectInterest(string $search): ?string
    {
        $search = strtolower(trim($search));

        $map = [
            'food'         => 'cuisine',
            'street food'  => 'cuisine',
            'cuisine'      => 'cuisine',
            'restaurant'   => 'cuisine',
            'art'          => 'art',
            'museum'       => 'art',
            'history'      => 'history',
            'heritage'     => 'history',
            'archaeology'  => 'history',
            'safari'       => 'safari',
            'wildlife'     => 'safari',
            'animal'       => 'safari',
            'beach'        => 'coastal',
            'coast'        => 'coastal',
            'island'       => 'coastal',
            'surf'         => 'coastal',
        ];

        foreach ($map as $keyword => $interest) {
            if (str_contains($search, $keyword)) {
                return $interest;
            }
        }

        return null;
    }
}