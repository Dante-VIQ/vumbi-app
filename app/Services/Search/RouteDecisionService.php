<?php

namespace App\Services\Search;

class RouteDecisionService
{
    public function decide(array $classification): array
    {
        $intent = $classification['intent'] ?? 'discovery';
        $type = $classification['type'] ?? 'general';
        $location = $classification['location'] ?? null;

        return [
            'route' => $this->resolveRoute($intent, $type),
            'target' => $location,
            'action' => $this->resolveAction($intent)
        ];
    }

    private function resolveRoute(string $intent, string $type): string
    {
        return match (true) {

            $intent === 'accommodation' => 'hotels',

            $intent === 'itinerary' => 'itinerary',

            $intent === 'budget' && $type === 'safari' => 'explore',

            $intent === 'explore' => 'explore',

            default => 'discovery'
        };
    }

    private function resolveAction(string $intent): string
    {
        return match ($intent) {

            'accommodation' => 'show_hotels',
            'itinerary' => 'generate_trip_plan',
            'budget' => 'highlight_budget_options',
            'explore' => 'show_places',

            default => 'show_city_overview'
        };
    }
}