<?php

namespace App\Services;

use App\Models\CuratedItem;
use Illuminate\Support\Collection;

class CuratedContentService
{
    public function getHotels(string $search, int $limit = 6): Collection
    {
        return $this->getByType('hotel', $search, $limit);
    }

    public function getTours(string $search, int $limit = 6): Collection
    {
        return $this->getByType('tour', $search, $limit);
    }

    public function getFlights(string $search, int $limit = 5): Collection
    {
        return $this->getByType('flight', $search, $limit);
    }

    public function getOffers(string $search, int $limit = 6): Collection
    {
        return $this->getByType('offer', $search, $limit);
    }

    private function getByType(string $type, string $search, int $limit): Collection
    {
        return CuratedItem::ofType($type)
            ->forDestination($search)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (CuratedItem $item) => [
                'name'        => $item->title,
                'description' => $item->description,
                'price'       => $item->price,
                'image'       => $item->image_url,
                'url'         => $item->link_url,
                'source'      => 'curated',        // for frontend differentiation
            ]);
    }
}