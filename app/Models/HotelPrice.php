<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelPrice extends Model
{
    protected $fillable = [
        'hotel_id',
        'price_per_night',
        'currency',
        'last_updated'
    ];

    protected $dates = ['last_updated'];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
