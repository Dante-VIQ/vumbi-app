<?php

// app/Models/CityCost.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityCost extends Model
{
    protected $fillable = [
        'city_id',
        'meal_price',
        'transport_ticket',
        'taxi_start',
        'coffee_price',
        'beer_price',
        'budget_daily_low',
        'budget_daily_mid',
        'budget_daily_high',
        'currency'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
