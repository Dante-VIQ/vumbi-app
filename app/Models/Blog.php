<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mews\Purifier\Casts\CleanHtmlOutput;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs'; // Ensure this matches your table name

    protected $fillable = ['user_id', 'category', 'title', 'slug', 'description', 'media_path', 'media_type', 'meta_title', 'meta_description', 'meta_keywords', 'is_featured'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'description' => CleanHtmlOutput::class,
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessors
    public function getExcerptAttribute($length = 150)
    {
        $raw = $this->getRawOriginal('description') ?? $this->getOriginal('description') ?? '';

        return Str::limit(strip_tags((string) $raw), $length);
    }

    public function getReadingTimeAttribute()
    {
        $raw = $this->getRawOriginal('description') ?? $this->getOriginal('description') ?? '';
        $words = str_word_count(strip_tags((string) $raw));
        $minutes = ceil($words / 200);

        return $minutes . ' min read';
    }

    public function getMediaUrlAttribute()
    {
        if ($this->media_path) {
            return asset('storage/' . $this->media_path);
        }

        return null;
    }

    public function getIsVideoAttribute()
    {
        return $this->media_type === 'video';
    }

    public function getIsImageAttribute()
    {
        return $this->media_type === 'image';
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%")
                ->orWhere('category', 'LIKE', "%{$term}%");
        });
    }

    public function getCategoryIconAttribute()
    {
        return match ($this->category) {
            'people' => 'fa-user',
            'culture' => 'fa-music',
            'destinations' => 'fa-map-marker-alt',
            default => 'fa-tag',
        };
    }

    /** Canonical public URL: the slug URL, or the numeric one only if a slug is missing. */
    public function getUrlAttribute(): string
    {
        return route('blog.show', $this->slug ?: $this->id);
    }

    /**
     * Unique, URL-safe slug. Never all digits, so it cannot be confused with an old numeric id URL.
     */
    public function generateUniqueSlug(): string
    {
        $base = Str::limit(Str::slug((string) $this->title), 80, '');
        if ($base === '' || ctype_digit($base)) {
            $base = 'post-' . ($base !== '' ? $base : Str::lower(Str::random(6)));
        }

        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($this->exists, fn ($q) => $q->where('id', '!=', $this->id))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    protected static function booted()
    {
        // Set once on creation. Editing a title later does not change the URL.
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = $blog->generateUniqueSlug();
            }
        });

        static::saving(function ($blog) {
            $blog->description = clean($blog->description ?? '');
        });
    }
}
