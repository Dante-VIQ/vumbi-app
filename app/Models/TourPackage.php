<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    protected $fillable = [
        'brand_id', 'name', 'slug', 'description', 'destination',
        'duration_days', 'price', 'itinerary', 'inclusions',
        'affiliate_url', 'status'
    ];

    protected $casts = [
        'itinerary' => 'array',
        'inclusions' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}