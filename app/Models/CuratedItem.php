<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuratedItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'destinations' => 'array',
        'tags'         => 'array',
        'is_active'    => 'boolean',
    ];

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type)->where('is_active', true);
    }

    public function scopeForDestination($query, string $search)
    {
        // Match if search term appears in any destination name
        return $query->where(function ($q) use ($search) {
            $q->where('destinations', 'like', "%{$search}%")
              ->orWhere('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}