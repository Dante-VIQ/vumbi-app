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

    protected $fillable = ['user_id', 'category', 'title', 'description', 'media_path', 'media_type'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'description' => CleanHtmlOutput::class,
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessors
    public function getExcerptAttribute($length = 150)
    {
        return Str::limit(strip_tags((string) $this->description), $length);
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags((string) $this->description));
        $minutes = ceil($words / 200);

        return $minutes.' min read';
    }

    public function getMediaUrlAttribute()
    {
        if ($this->media_path) {
            return asset('storage/'.$this->media_path);
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

    protected static function booted()
    {
        static::saving(function ($blog) {
            $blog->description = clean($blog->description ?? '');
        });

    }


}
