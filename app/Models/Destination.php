<?php

namespace App\Models;

use App\Models\PartnerPackage;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Destination extends Model
{
    use HasFactory, HasSlug;

protected $fillable = [
    'name', 'slug', 'location', 'detail', 'media_path',
    'price_range', 'best_time_to_visit', 'featured', 'legacy_doctor_id'
];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            if (empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });

        static::updating(function ($destination) {
            if ($destination->isDirty('name') && empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return $this->media_path ? Storage::url($this->media_path) : null;
    }

       /**
     * Get all tours/packages associated with this destination.
     */
    public function tours(): HasMany
    {
        return $this->hasMany(PartnerPackage::class, 'destination_id');
    }
}