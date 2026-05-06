<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityGuide extends Model
{
    protected $fillable = [
        'city_id',
        'intro_text',
        'history',
        'culture',
        'food_culture',
        'travel_tips',
        'ideal_traveler',
        'generated_at'
    ];

    protected $dates = ['generated_at'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
