<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use function Illuminate\Support\Concurrency\run;   // For Laravel 10.27+

class DiscoveryService
{
    public function __construct(
        private TravelPayoutsService   $travelPayoutsService,
        private BonusArriveService     $bonusArriveService,
        private AwinService            $awinService,
        private PlaceDiscoveryService  $placeDiscoveryService,
        private CuratedContentService  $curatedContentService
    ) {}

    public function search(string $search, ?string $interest = null): array
    {
        // If no interest provided, try to detect from search text
        $interest = $interest ?? $this->detectInterest($search);

        $cacheKey = 'discovery:' . md5(strtolower($search) . '|' . ($interest ?? 'all'));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $interest) {
            // Run all fetches concurrently (if concurrency is available)
            // Fallback to sequential if concurrency helper is not present.
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
                $stories      = $this->fetchStories($search, $interest);
                $hotels       = $this->fetchWithFallback('hotel', $search, fn($s) => $this->travelPayoutsService->searchHotels($s, 6), $interest);
                $tours        = $this->fetchWithFallback('tour', $search, fn($s) => $this->travelPayoutsService->searchTours($s, 6), $interest);
                $flights      = $this->fetchWithFallback('flight', $search, fn($s) => $this->bonusArriveService->searchFlights($s, 5), $interest);
                $awinOffers   = $this->fetchWithFallback('offer', $search, fn($s) => $this->awinService->searchOffers($s, 6), $interest);
                $placeInfo    = $this->fetchPlaceInfo($search);
                $attractions  = $this->fetchAttractions($search);
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
            Log::warning("{$type} API failed.", ['error' => $e->getMessage(), 'search' => $search]);
        }

        // Fallback – now passes interest to the curated service
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
        $query = Blog::query()
            ->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest();

        // If interest is set, assume you have a `tags` JSON column on blogs.
        // Adjust to your actual column (maybe `category` or `interest`).
        if ($interest) {
            $query->whereJsonContains('tags', $interest);
        }

        $stories = $query->limit(6)->get();

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
            return ['items' => null, 'health' => 'empty'];
        }
    }

    private function fetchAttractions(string $search): array
    {
        try {
            $attractions = $this->placeDiscoveryService->getAttractions($search, 6);
            return [
                'items'  => $attractions,
                'health' => $attractions ? 'api' : 'empty',
            ];
        } catch (\Exception $e) {
            return ['items' => [], 'health' => 'empty'];
        }
    }

    /**
     * Auto-detect interest from search text.
     */
    private function detectInterest(string $search): ?string
    {
        $search = strtolower($search);
        $map = [
            'food'        => 'cuisine',
            'street food' => 'cuisine',
            'cuisine'     => 'cuisine',
            'art'         => 'art',
            'history'     => 'art',
            'archaeology' => 'art',
            'safari'      => 'safari',
            'wildlife'    => 'safari',
            'beach'       => 'coastal',
            'coast'       => 'coastal',
        ];

        foreach ($map as $keyword => $interest) {
            if (str_contains($search, $keyword)) {
                return $interest;
            }
        }

        return null;   // or 'all'
    }
}