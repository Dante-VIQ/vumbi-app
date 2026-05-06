<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityWeather extends Model
{
    protected $table = 'city_weather_monthly';

    protected $fillable = [
        'city_id',
        'month',
        'avg_temp_day',
        'avg_temp_night',
        'rainfall_mm'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
}
