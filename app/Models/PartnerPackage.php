<?php

namespace App\Models;

use App\Models\Destination;
use App\Models\Lead;
use App\Models\PartnerPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PartnerPackage extends Model
{
    protected $fillable = [
    'location', 'title', 'slug', 'description', 'price',
    'vehicle_type', 'image', 'type', 'active',
    'duration_days', 'duration_nights',
    'itinerary', 'included', 'excluded',
    'difficulty', 'group_size_min', 'group_size_max',
    'booking_type', 'affiliate_source', 'affiliate_url',
    'region', 'destination_id',
    'audiences', 'interests', 'budget_band', 'accommodation_style',
    ];
    protected $casts = [
      'active'        => 'boolean',
    'price'         => 'decimal:2',
    'itinerary'     => 'array',
    'included'      => 'array',
    'excluded'      => 'array',
    'audiences'     => 'array',
    'interests'     => 'array',
    ];

protected static function booted(): void
{
    static::creating(function (PartnerPackage $package) {
        if (empty($package->slug)) {
            $package->slug = Str::slug($package->title);
        }
        $package->fillBudgetBand();
    });

    static::updating(function (PartnerPackage $package) {
        if (empty($package->slug)) {
            $package->slug = Str::slug($package->title);
        }
        $package->fillBudgetBand();
    });
}
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Narrow by the persona tags. Every key is optional and ANDed together.
     *
     * @param  array{interest?: string, budget?: string, days?: string, group?: int, audience?: string}  $filters
     */
    public function scopeTagged($query, array $filters)
    {
        if (! empty($filters['audience'])) {
            $query->whereJsonContains('audiences', $filters['audience']);
        }

        if (! empty($filters['interest'])) {
            $query->whereJsonContains('interests', $filters['interest']);
        }

        if (! empty($filters['budget'])) {
            $query->where('budget_band', $filters['budget']);
        }

        match ($filters['days'] ?? null) {
            'short' => $query->where('duration_days', '<=', 3),
            'week' => $query->whereBetween('duration_days', [4, 7]),
            'long' => $query->where('duration_days', '>=', 8),
            default => null,
        };

        if (! empty($filters['group'])) {
            $size = (int) $filters['group'];
            // A tour with no stated limit is assumed to fit any party.
            $query->where(fn ($q) => $q->whereNull('group_size_min')->orWhere('group_size_min', '<=', $size))
                  ->where(fn ($q) => $q->whereNull('group_size_max')->orWhere('group_size_max', '>=', $size));
        }

        return $query;
    }

    /** 2 = tagged for this persona, 1 = untagged, 0 = tagged only for other personas. */
    public function personaScore(?string $persona): int
    {
        $audiences = $this->audiences ?? [];

        if ($audiences === []) {
            return 1;
        }

        return in_array($persona, $audiences, true) ? 2 : 0;
    }

    /** Set the budget band from the price when nobody has chosen one. */
    public function fillBudgetBand(): void
    {
        if (empty($this->budget_band)) {
            $this->budget_band = \App\Support\Persona::bandForPrice($this->price);
        }
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

        public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}