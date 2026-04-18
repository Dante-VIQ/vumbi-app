<?php

namespace App\Services;

use App\Models\Blog;
use App\Services\TravelIntentService;

class AffiliateMatcher
{
    public function buildPlan(Blog $blog): array
    {
        $intentService = app(TravelIntentService::class);

        $text = implode(' ', [
            $blog->title,
            $blog->excerpt,
            $blog->description,
            implode(' ', $blog->tags ?? [])
        ]);

        $intents = $intentService->detect($text);

        $plan = [];

        foreach ($intents as $intent => $score) {
            $networks = config("services.travel-affiliates.intents.$intent.network_priority") ?? [];

            $plan[$intent] = [
                'score' => $score,
                'networks' => $networks,
            ];
        }

        return $plan;
    }
}
