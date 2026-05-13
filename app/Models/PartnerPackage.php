<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PartnerPackage extends Model
{
    protected $fillable = [
            'location', 'title', 'slug', 'description', 'price',
    'vehicle_type', 'image', 'type', 'active',
    'duration_days', 'duration_nights',
    'itinerary', 'included', 'excluded',
    'difficulty', 'group_size_min', 'group_size_max',
    ];

    protected $casts = [
      'active'        => 'boolean',
    'price'         => 'decimal:2',
    'itinerary'     => 'array',
    'included'      => 'array',
    'excluded'      => 'array',
    ];


protected static function booted(): void
{
    static::creating(function (PartnerPackage $package) {
        if (empty($package->slug)) {
            $package->slug = Str::slug($package->title);
        }
    });

    static::updating(function (PartnerPackage $package) {
        if (empty($package->slug)) {
            $package->slug = Str::slug($package->title);
        }
    });
}
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForLocation($query, string $location)
    {
        return $query->whereRaw('LOWER(location) LIKE ?', ['%' . strtolower($location) . '%']);
    }
    
}