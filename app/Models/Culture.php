<?php

namespace App\Models;

use App\Models\User;
use App\TrackableViews;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use PHPStan\PhpDocParser\Ast\Comment;

class Culture extends Model
{
    use HasFactory, HasSlug;
    // use Searchable;
    // use TrackableViews;

    protected $fillable = ['user_id', 'name', 'location', 'detail', 'image'];

    protected $hidden = ['user_id'];

    //   public function comments()
    // {
    //     return $this->morphMany(Comment::class, 'commentable');
    // }

    // public function toSearchableArray(){
    //     return [
    //     'id' => $this->id,
    //     'name' => $this->name,
    //     'detail' => $this->detail,
    //     ];
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';  // or any column other than 'id'
    }

    public function getExcerptAttribute($length = 150)
    {
        return Str::limit(strip_tags((string) $this->detail), $length);
    }

    protected static function booted()
    {
        static::saving(function ($culture) {
            $culture->detail = clean($culture->detail ?? '');
        });

    }

        public function getMediaUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        return null;
    }
}
