<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitySearch extends Model
{
    protected $fillable = [
        'search_query',
        'city_id'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
