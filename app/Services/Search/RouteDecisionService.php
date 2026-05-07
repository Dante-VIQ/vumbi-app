<?php

namespace App\Services\Search;

class RouteDecisionService
{
    /**
     * Decide the best route/action based on query classification
     */
    public function decide(array $classification): array
    {
        $intent   = $classification['intent'] ?? 'discovery';
        $type     = $classification['type'] ?? 'general';
        $location = $classification['location'] ?? null;
        $confidence = $classification['confidence'] ?? 60;

        $route  = $this->resolveRoute($intent, $type, $confidence);
        $action = $this->resolveAction($intent, $type);

        return [
            'route'        => $route,
            'action'       => $action,
            'target'       => $location,
            'intent'       => $intent,
            'type'         => $type,
            'confidence'   => $confidence,
            'is_fallback'  => $route === 'discovery',
        ];
    }

    private function resolveRoute(string $intent, string $type, int $confidence): string
    {
        // High confidence + specific intent takes priority
        if ($confidence < 50) {
            return 'discovery'; // fallback
        }

        return match (true) {
            $intent === 'accommodation'                  => 'hotels',
            $intent === 'itinerary'                      => 'itinerary',
            $intent === 'explore' || $type === 'safari'  => 'explore',
            $intent === 'budget'                         => 'explore',     // budget usually filters explore/hotels
            $intent === 'flight'                         => 'flights',

            default                                      => 'discovery'
        };
    }

    private function resolveAction(string $intent, string $type): string
    {
        return match (true) {
            $intent === 'accommodation'                  => 'show_hotels',
            $intent === 'itinerary'                      => 'generate_trip_plan',
            $intent === 'budget'                         => 'show_budget_options',
            $intent === 'explore' || $type === 'safari'  => 'show_places',
            $intent === 'flight'                         => 'show_flights',

            default                                      => 'show_city_overview'
        };
    }
}