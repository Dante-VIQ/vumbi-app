<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'city_id',
        'name',
        'external_id',
        'rating',
        'address',
        'latitude',
        'longitude',
        'affiliate_url'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(HotelPrice::class);
    }
}