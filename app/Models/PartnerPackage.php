<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPackage extends Model
{
    protected $fillable = [
        'location', 'title', 'description', 'price',
        'vehicle_type', 'image', 'type', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price'  => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForLocation($query, string $location)
    {
        return $query->whereRaw('LOWER(location) LIKE ?', ['%' . strtolower($location) . '%']);
    }
    
}