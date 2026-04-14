<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'media_path',
        'media_type',
        'location',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active slides
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->orderBy('sort_order')
                     ->orderBy('created_at', 'desc');
    }
}