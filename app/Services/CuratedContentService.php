<?php

namespace App\Services;

use App\Models\CuratedItem;
use Illuminate\Support\Collection;

class CuratedContentService
{
// app/Services/CuratedContentService.php
public function getHotels(string $search, int $limit = 6, ?string $interest = null): Collection
{
    return $this->getByType('hotel', $search, $limit, $interest);
}

public function getTours(string $search, int $limit = 6, ?string $interest = null): Collection
{
    return $this->getByType('tour', $search, $limit, $interest);
}

// ... similarly for flights and offers ...

private function getByType(string $type, string $search, int $limit, ?string $interest = null): Collection
{
    return CuratedItem::ofType($type)
        ->forDestination($search)
        ->when($interest, fn($q) => $q->whereJsonContains('tags', $interest))
        ->latest()
        ->limit($limit)
        ->get()
        ->map(fn (CuratedItem $item) => [
            'name'        => $item->title,
            'description' => $item->description,
            'price'       => $item->price,
            'image'       => $item->image_url,
            'url'         => $item->link_url,
            'source'      => 'curated',
        ]);
}

    public function getFlights(string $search, int $limit = 5, ?string $interest = null): Collection
    {
        return $this->getByType('flight', $search, $limit, $interest);
    }

    public function getOffers(string $search, int $limit = 6): Collection
    {
        return $this->getByType('offer', $search, $limit);
    }
}