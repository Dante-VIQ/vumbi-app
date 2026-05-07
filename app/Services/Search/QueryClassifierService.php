<?php

namespace App\Services\Search;

class QueryClassifierService
{
    /**
     * Classify user search query
     */
    public function classify(string $query): array
    {
        $originalQuery = trim($query);
        $normalized = strtolower($originalQuery);

        return [
            'intent'       => $this->detectIntent($normalized),
            'location'     => $this->extractLocation($originalQuery, $normalized),
            'type'         => $this->detectType($normalized),
            'confidence'   => $this->calculateConfidence($normalized),
            'raw_query'    => $originalQuery,
        ];
    }

    private function detectIntent(string $query): string
    {
        $keywords = [
            'accommodation' => ['hotel', 'stay', 'room', 'lodge', 'hostel', 'resort'],
            'explore'       => ['things to do', 'visit', 'safari', 'attraction', 'tour', 'activity'],
            'budget'        => ['cheap', 'budget', 'affordable', 'low cost'],
            'itinerary'     => ['trip', 'itinerary', 'plan', 'schedule', 'route'],
            'flight'        => ['flight', 'fly', 'plane', 'ticket'],
        ];

        foreach ($keywords as $intent => $terms) {
            foreach ($terms as $term) {
                if (str_contains($query, $term)) {
                    return $intent;
                }
            }
        }

        return 'discovery'; // default
    }

    private function extractLocation(string $originalQuery, string $normalized): ?string
    {
        // Remove common noise words
        $noiseWords = [
            'cheap', 'best', 'good', 'top', 'hotel', 'hotels', 'stay', 
            'trip', 'to', 'in', 'near', 'from', 'for', 'the', 'a', 'an'
        ];

        $words = explode(' ', $normalized);
        $filtered = array_filter($words, function ($word) use ($noiseWords) {
            return !in_array($word, $noiseWords) && strlen($word) > 2;
        });

        $filtered = array_values($filtered);

        // Take first 2-3 words as potential location (handles "New York", "Cape Town", etc.)
        $locationParts = array_slice($filtered, 0, 3);
        
        $potentialLocation = implode(' ', $locationParts);

        return !empty($potentialLocation) ? ucwords($potentialLocation) : null;
    }

    private function detectType(string $query): string
    {
        $typeKeywords = [
            'beach'     => ['beach', 'coastal', 'seaside'],
            'safari'    => ['safari', 'wildlife', 'game reserve'],
            'city'      => ['city', 'urban', 'metropolitan'],
            'mountain'  => ['mountain', 'hill', 'peak'],
            'island'    => ['island', 'islands'],
        ];

        foreach ($typeKeywords as $type => $terms) {
            foreach ($terms as $term) {
                if (str_contains($query, $term)) {
                    return $type;
                }
            }
        }

        return 'general';
    }

    private function calculateConfidence(string $query): int
    {
        $score = 60; // base confidence

        // Increase confidence if query is reasonably long
        if (strlen($query) > 8) $score += 15;
        if (strlen($query) > 15) $score += 10;

        // Decrease if too short
        if (strlen($query) < 4) $score -= 20;

        return min(95, max(30, $score));
    }
}