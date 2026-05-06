<?php

namespace App\Services\Search;

class QueryClassifierService
{
    public function classify(string $query): array
    {
        $query = strtolower(trim($query));

        return [
            'intent' => $this->detectIntent($query),
            'location' => $this->extractLocation($query),
            'type' => $this->detectType($query),
        ];
    }

    private function detectIntent(string $query): string
    {
        return match (true) {

            str_contains($query, 'hotel') => 'accommodation',
            str_contains($query, 'stay') => 'accommodation',

            str_contains($query, 'things to do') => 'explore',
            str_contains($query, 'visit') => 'explore',
            str_contains($query, 'safari') => 'explore',

            str_contains($query, 'cheap') => 'budget',
            str_contains($query, 'budget') => 'budget',

            str_contains($query, 'trip') => 'itinerary',

            default => 'discovery'
        };
    }

    private function extractLocation(string $query): ?string
    {
        // simple MVP extraction (upgrade later with AI)
        $words = explode(' ', $query);

        $commonStops = ['cheap', 'best', 'hotel', 'trip', 'to', 'in', 'near'];

        $filtered = array_values(array_filter($words, function ($word) use ($commonStops) {
            return !in_array($word, $commonStops);
        }));

        return $filtered[0] ?? null;
    }

    private function detectType(string $query): string
    {
        return match (true) {

            str_contains($query, 'beach') => 'beach',
            str_contains($query, 'safari') => 'safari',
            str_contains($query, 'city') => 'city',
            str_contains($query, 'trip') => 'itinerary',

            default => 'general'
        };
    }
}