<?php

namespace App\Services;

use App\Models\PartnerPackage;

class LocalPartnerService
{
    /**
     * Get active partner packages matching a location (fuzzy search).
     *
     * @param string $location  e.g. "Mombasa", "Maasai Mara", "Diani Beach"
     * @param int    $limit     maximum number of packages to return
     * @return array
     */
    public function getPackages(string $location, int $limit = 6): array
    {
        return PartnerPackage::active()
            ->forLocation($location)
            ->take($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get a single package by ID.
     */
    public function findPackage(int $id): ?PartnerPackage
    {
        return PartnerPackage::active()->find($id);
    }
}