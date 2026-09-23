<?php

namespace App\Services;

use App\Models\TourPackage;
use App\Models\ContentDraft;
use Illuminate\Support\Collection;

class ContentTourMatcher
{
    public function findMatches(ContentDraft $draft): Collection
    {
        $tours = TourPackage::where('brand_id', $draft->brand_id)
            ->where('status', 'active')
            ->get();

        if ($tours->isEmpty()) {
            return collect();
        }

        $content = strtolower($draft->title . ' ' . $draft->content . ' ' . $draft->meta_description);

        return $tours->map(function ($tour) use ($content) {
            $score = $this->calculateMatchScore($content, $tour);
            return [
                'tour' => $tour,
                'score' => $score,
            ];
        })
        ->filter(fn($item) => $item['score'] > 0)
        ->sortByDesc('score')
        ->values();
    }

    private function calculateMatchScore(string $content, TourPackage $tour): float
    {
        $score = 0;
        $keywords = $this->extractKeywords($tour);

        foreach ($keywords as $keyword) {
            if (str_contains($content, $keyword)) {
                $score += 1.0;
            }
        }
        
        // Boost score for destination match
        if ($tour->destination && str_contains($content, strtolower($tour->destination))) {
            $score += 3.0;
        }

        // Boost for duration match if article mentions duration
        if ($tour->duration_days && preg_match('/(\d+)\s*days?/', $content, $matches)) {
            if ((int)$matches[1] === $tour->duration_days) {
                $score += 2.0;
            }
        }

        return $score;
    }

    private function extractKeywords(TourPackage $tour): array
    {
        $text = strtolower($tour->name . ' ' . $tour->description . ' ' . $tour->destination);
        
        // Remove common words
        $stopWords = ['the', 'and', 'for', 'with', 'from', 'this', 'that', 'tour', 'package', 'trip'];
        $words = str_word_count($text, 1);
        
        return array_unique(array_diff($words, $stopWords));
    }
}