<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityImage extends Model
{
    protected $fillable = [
        'city_id',
        'image_url',
        'photographer_name',
        'source'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}