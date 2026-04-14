<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'network', 'program_name', 'program_id', 'type', 
        'keywords', 'description', 'affiliate_link', 
        'widget_code', 'priority', 'active'
    ];

    protected $casts = [
        'keywords' => 'array',
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}