<?php

return [
    'intents' => [
        'flights' => [
            'keywords' => ['flight', 'airport', 'airline', 'fly', 'getting there'],
            'network_priority' => ['bonus_arrive', 'travelpayouts'],
        ],

        'hotels' => [
            'keywords' => ['hotel', 'stay', 'lodge', 'resort', 'accommodation'],
            'network_priority' => ['travelpayouts'],
        ],

        'tours' => [
            'keywords' => ['tour', 'safari', 'things to do', 'activities', 'excursions'],
            'network_priority' => ['awin', 'travelpayouts'],
        ],

        'insurance' => [
            'keywords' => ['insurance', 'visa', 'safety', 'travel tips', 'planning'],
            'network_priority' => ['awin'],
        ],

        'gear' => [
            'keywords' => ['packing', 'gear', 'camera', 'backpack'],
            'network_priority' => ['awin'],
        ],
    ],

];