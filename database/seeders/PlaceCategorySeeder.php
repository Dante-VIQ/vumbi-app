<?php

namespace Database\Seeders;

use App\Models\PlaceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// database/seeders/PlaceCategorySeeder.php
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlaceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Restaurant',
            'Cafe',
            'Bar',
            'Tourist Attraction',
            'Museum',
            'Park',
            'Shopping Mall',
            'Nightlife'
        ];

        foreach ($categories as $cat) {
            PlaceCategory::create([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);
        }
    }
}