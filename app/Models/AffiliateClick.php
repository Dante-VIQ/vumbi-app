<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateClick extends Model
{
    protected $fillable = [
        'city_id',
        'type',
        'partner',
        'clicked_url',
        'user_ip'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}