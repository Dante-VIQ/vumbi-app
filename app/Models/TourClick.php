<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourClick extends Model
{
    protected $fillable = [
        'partner_package_id',
        'source',
        'destination_url',
        'referring_url',
        'ip_hash',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(PartnerPackage::class, 'partner_package_id');
    }
}
