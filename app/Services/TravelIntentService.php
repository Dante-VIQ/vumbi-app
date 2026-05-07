<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TravelIntentService
{
    /**
     * Detect travel intents from user query using config-driven keywords
     */
    public function detect(string $text): array
    {
        $text = strtolower(trim($text));
        if (empty($text)) {
            return [];
        }

        $intents = config('travel-affiliates.intents', []);

        if (empty($intents)) {
            Log::warning("Travel intents configuration is missing or empty");
            return [];
        }

        $scores = [];

        foreach ($intents as $intent => $config) {
            $score = $this->calculateIntentScore($text, $config['keywords'] ?? []);

            if ($score > 0) {
                $scores[$intent] = $score;
            }
        }

        arsort($scores); // Highest score first

        return $scores;
    }

    private function calculateIntentScore(string $text, array $keywords): int
    {
        $score = 0;

        foreach ($keywords as $keyword) {
            if (str_contains($text, strtolower($keyword))) {
                $score++;
            }
        }

        return $score;
    }
}