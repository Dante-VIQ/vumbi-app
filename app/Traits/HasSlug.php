<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Auto-generates a unique slug from a source field (default: "title")
 * whenever a model is created, or whenever that field changes on update.
 *
 * Usage:
 *   class Culture extends Model
 *   {
 *       use HasSlug;
 *   }
 *
 * Override the source field per-model if needed:
 *   protected string $slugSourceField = 'name';
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            $model->slug = $model->generateUniqueSlug();
        });

        static::updating(function ($model) {
            $sourceField = $model->slugSourceField ?? 'title';

            // Only regenerate if the source field actually changed and
            // no slug was explicitly set — avoids clobbering a manually
            // edited slug just because the title was tweaked.
            if ($model->isDirty($sourceField) && !$model->isDirty('slug')) {
                $model->slug = $model->generateUniqueSlug();
            }
        });
    }

    public function generateUniqueSlug(): string
    {
        $sourceField = $this->slugSourceField ?? 'title';
        $base = Str::slug($this->{$sourceField});
        $slug = $base;
        $i = 1;

        $query = fn ($candidate) => static::where('slug', $candidate)
            ->when($this->exists, fn ($q) => $q->where('id', '!=', $this->id))
            ->exists();

        while ($query($slug)) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}