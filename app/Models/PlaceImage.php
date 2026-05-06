<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceImage extends Model
{
    protected $fillable = [
        'place_id',
        'image_url',
        'source'
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}