<?php

// app/Models/Place.php
namespace App\Models;

use App\Models\City;
use App\Models\PlaceCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    protected $fillable = [
        'city_id',
        'place_category_id',
        'name',
        'google_place_id',
        'address',
        'latitude',
        'longitude',
        'rating',
        'price_level',
        'website',
        'description'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class,'place_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PlaceImage::class);
    }
}