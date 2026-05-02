<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class DiscoveryService
{
    public function __construct(
        private TravelPayoutsService   $travelPayoutsService,
        private BonusArriveService     $bonusArriveService,
        private AwinService            $awinService,
        private PlaceDiscoveryService  $placeDiscoveryService,
        private CuratedContentService  $curatedContentService
    ) {}

    public function search(string $search): array
    {
        return [
            'stories'      => $this->fetchStories($search),
            'hotels'       => $this->fetchWithFallback('hotel', $search, fn($s) => $this->travelPayoutsService->searchHotels($s, 6)),
            'tours'        => $this->fetchWithFallback('tour', $search, fn($s) => $this->travelPayoutsService->searchTours($s, 6)),
            'flights'      => $this->fetchWithFallback('flight', $search, fn($s) => $this->bonusArriveService->searchFlights($s, 5)),
            'awin_offers'  => $this->fetchWithFallback('offer', $search, fn($s) => $this->awinService->searchOffers($s, 6)),
            'place_info'   => $this->fetchPlaceInfo($search),
            'attractions'  => $this->fetchAttractions($search),
        ];
    }

    private function fetchWithFallback(string $type, string $search, callable $apiCall): array
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

        $fallback = match ($type) {
            'hotel'  => $this->curatedContentService->getHotels($search, 6),
            'tour'   => $this->curatedContentService->getTours($search, 6),
            'flight' => $this->curatedContentService->getFlights($search, 5),
            'offer'  => $this->curatedContentService->getOffers($search, 6),
            default  => collect([]),
        };

        return [
            'items'  => $fallback->toArray(),
            'health' => $fallback->isNotEmpty() ? 'curated' : 'empty',
        ];
    }

    private function fetchStories(string $search): array
    {
        $stories = Blog::where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->latest()->limit(6)->get();

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
}