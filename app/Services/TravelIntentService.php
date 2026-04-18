<?php

namespace App\Services;

class TravelIntentService
{
    public function detect(string $text): array
    {
        $text = strtolower($text);
        $intents = config('travel-affiliates.intents');

        $scores = [];

        foreach ($intents as $intent => $data) {
            $score = 0;

            foreach ($data['keywords'] as $keyword) {
                if (str_contains($text, $keyword)) {
                    $score++;
                }
            }

            if ($score > 0) {
                $scores[$intent] = $score;
            }
        }

        arsort($scores);

        return $scores; // ['hotels'=>3,'flights'=>2]
    }
}
