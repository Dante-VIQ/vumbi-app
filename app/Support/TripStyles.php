<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\PartnerPackage;
use Illuminate\Support\Collection;

/**
 * Reads the trip-style config and pulls the matching tours and Field Notes.
 */
final class TripStyles
{
    public static function all(): array
    {
        return config('trip_styles', []);
    }

    public static function find(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    public static function slugFor(?string $persona): ?string
    {
        foreach (self::all() as $slug => $style) {
            if (($style['persona'] ?? null) === $persona) {
                return $slug;
            }
        }

        return null;
    }

    public static function url(string $slug): string
    {
        return route('trips.show', $slug);
    }

    /** Active tours tagged for this style, newest first. */
    public static function tours(string $slug, int $limit = 3): Collection
    {
        $persona = self::find($slug)['persona'] ?? null;

        if (! $persona) {
            return collect();
        }

        return PartnerPackage::active()
            ->whereJsonContains('audiences', $persona)
            ->latest()
            ->take($limit)
            ->get();
    }

    /** Lowest and highest price among tours tagged for this style, or null when none. */
    public static function priceRange(string $slug): ?array
    {
        $persona = self::find($slug)['persona'] ?? null;

        if (! $persona) {
            return null;
        }

        $query = PartnerPackage::active()->whereJsonContains('audiences', $persona);

        if (! $query->exists()) {
            return null;
        }

        return ['min' => (float) $query->min('price'), 'max' => (float) $query->max('price'), 'count' => $query->count()];
    }

    /** Field Notes whose title, category or keywords mention this style's topics. */
    public static function relatedPosts(string $slug, int $limit = 3): Collection
    {
        $keywords = self::find($slug)['blog_keywords'] ?? [];

        if ($keywords === []) {
            return collect();
        }

        return Blog::query()
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('title', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%")
                        ->orWhere('meta_keywords', 'like', "%{$keyword}%");
                }
            })
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Move posts that match a persona's interests to the front, keeping the existing
     * order inside each group. Nothing is dropped, so the page still shows the same posts.
     */
    public static function prioritisePosts(Collection $posts, ?string $persona): Collection
    {
        $slug = self::slugFor($persona);
        $keywords = $slug ? (self::find($slug)['blog_keywords'] ?? []) : [];
        if ($keywords === []) {
            return $posts->values();
        }

        $matches = fn ($post) => collect($keywords)->contains(
            fn ($k) => stripos((string) $post->title . ' ' . (string) $post->category . ' ' . (string) $post->meta_keywords, $k) !== false
        );

        return $posts->sortByDesc(fn ($post) => $matches($post) ? 1 : 0)->values();
    }
}
