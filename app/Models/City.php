<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'region_id',
        'name',
        'slug',
        'latitude',
        'longitude',
        'timezone',
        'population',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function guide(): HasMany
    {
        return $this->hasMany(CityGuide::class);
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    public function costs(): HasMany
    {
        return $this->hasMany(CityCost::class);
    }

    public function weather(): HasMany
    {
        return $this->hasMany(CityWeather::class);
    }
}
