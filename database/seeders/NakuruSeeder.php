<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Place;
use App\Models\PlaceIdentity;
use App\Models\PlaceHistory;
use App\Models\PlaceCulture;
use App\Models\PlaceCost;
use App\Models\PlaceExperience;


class NakuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run(): void
{
    $place = Place::create([
        'name' => 'Nakuru',
        'slug' => 'nakuru',
        'region' => 'Rift Valley',
        'type' => 'city',
        'summary' => 'A fast-growing lakeside city known for wildlife, farming, and tourism.',
        'vibe_tags' => json_encode(['nature', 'budget', 'wildlife', 'weekend']),
        'latitude' => -0.3031,
        'longitude' => 36.0800,
    ]);

    PlaceIdentity::create([
        'place_id' => $place->id,
        'description' => 'Nakuru is a major Kenyan city located in the Rift Valley, known for Lake Nakuru National Park and its growing urban economy.',
        'significance' => 'A key tourism and agricultural hub in Kenya.',
        'key_facts' => json_encode([
            'Home to Lake Nakuru National Park',
            'One of fastest growing cities in Kenya',
            'Strong agricultural economy'
        ])
    ]);

    PlaceHistory::create([
        'place_id' => $place->id,
        'content' => 'Nakuru developed from a colonial railway stop into a major urban center in Kenya...',
        'timeline' => json_encode([
            '1900s - Railway settlement',
            '1960s - Agricultural expansion',
            '2000s - Urban growth surge'
        ])
    ]);

    PlaceCulture::create([
        'place_id' => $place->id,
        'lifestyle' => 'Mixed urban and rural lifestyle with strong agricultural influence.',
        'social_behavior' => 'Friendly, trading culture, strong matatu transport influence.',
        'languages' => json_encode(['Swahili', 'English', 'Kalenjin', 'Kikuyu'])
    ]);

    PlaceCost::create([
        'place_id' => $place->id,
        'daily_budget_low' => 1500,
        'daily_budget_mid' => 4000,
        'daily_budget_high' => 10000,
        'food_cost_min' => 200,
        'food_cost_max' => 1500,
        'transport_cost_min' => 50,
        'transport_cost_max' => 500,
        'accommodation_min' => 1200,
        'accommodation_max' => 8000,
    ]);

    PlaceExperience::create([
        'place_id' => $place->id,
        'title' => 'Lake Nakuru Safari Experience',
        'category' => 'nature',
        'description' => 'Explore flamingos, rhinos, and wildlife in Lake Nakuru National Park.',
        'price_estimate' => 3000,
        'popularity_score' => 95
    ]);
}
}
