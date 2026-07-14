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

    public function setItineraryAttribute($value)
{
    // If it's a string (from a textarea), parse it into structured data
    if (is_string($value)) {
        // Split by "Day X:" pattern
        $days = preg_split('/(?=Day\s+\d+:)/i', trim($value));
        $days = array_filter(array_map('trim', $days)); // Remove empty and trim
        
        $structured = [];
        foreach ($days as $day) {
            $lines = explode("\n", $day);
            $title = array_shift($lines);
            $description = implode("\n", $lines);
            
            $structured[] = [
                'title' => trim($title),
                'description' => trim($description)
            ];
        }
        
        $this->attributes['itinerary'] = json_encode($structured);
    } else {
        // If it's already an array or null, store as-is
        $this->attributes['itinerary'] = is_array($value) 
            ? json_encode($value) 
            : $value;
    }
}
    
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function isAffiliate(): bool
    {
        return $this->booking_type === 'affiliate';
    }

    public function isManual(): bool
    {
        return $this->booking_type === 'manual';
    }
}