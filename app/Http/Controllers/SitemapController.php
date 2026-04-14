<?php

namespace App\Http\Controllers;

use App\Models\Blog;
// use App\Models\Culture;
// use App\Models\Doctor; // Destinations model
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {

        $cacheKey = 'sitemap_content';
        $cached = cache()->get($cacheKey);

        if ($cached && ! app()->environment('local')) {
            return response($cached, 200)->header('Content-Type', 'text/xml');
        }
        // Static pages (change if your routes are named differently)
        $staticPages = [
            (object) ['loc' => url('/'), 'lastmod' => now()->toDateString(), 'priority' => '1.0', 'changefreq' => 'weekly'],
            (object) ['loc' => url('/manifesto'), 'lastmod' => now()->toDateString(), 'priority' => '0.8', 'changefreq' => 'monthly'],
            (object) ['loc' => url('/ecosystem'), 'lastmod' => now()->toDateString(), 'priority' => '0.8', 'changefreq' => 'monthly'],
            (object) ['loc' => url('/contact'), 'lastmod' => now()->toDateString(), 'priority' => '0.6', 'changefreq' => 'yearly'],
            (object) ['loc' => url('/discover'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'weekly'],
            (object) ['loc' => url('/blog'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'daily'],
            (object) ['loc' => url('/culture'), 'lastmod' => now()->toDateString(), 'priority' => '0.8', 'changefreq' => 'weekly'],
        ];

        // Blog posts (published)
        $blogs = Blog::where('is_published', true)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($post) {
                $post->loc = route('blog.show', $post->id);
                $post->lastmod = $post->updated_at->toDateString();
                $post->priority = '0.7';
                $post->changefreq = 'monthly';

                return $post;
            });

        // Culture entries
        // $cultures = Culture::orderBy('updated_at', 'desc')
        //     ->get()
        //     ->map(function ($item) {
        //         $item->loc = route('culture.show', $item->id);
        //         $item->lastmod = $item->updated_at->toDateString();
        //         $item->priority = '0.6';
        //         $item->changefreq = 'monthly';

        //         return $item;
        //     });

        // Destinations (Doctor model)
        // $destinations = Doctor::orderBy('updated_at', 'desc')
        //     ->get()
        //     ->map(function ($dest) {
        //         $dest->loc = route('destinations.show', $dest->id); // adjust route name if different
        //         $dest->lastmod = $dest->updated_at->toDateString();
        //         $dest->priority = '0.7';
        //         $dest->changefreq = 'monthly';

        //         return $dest;
        //     });

        $all = collect($staticPages)
            ->merge($blogs);
            // ->merge($cultures)
            // ->merge($destinations);

        return response()->view('sitemap', ['pages' => $all])->header('Content-Type', 'text/xml');

        $view = view('sitemap', ['pages' => $all])->render();
        cache()->put($cacheKey, $view, now()->addDay()); // cache for 24 hours

        return response($view, 200)->header('Content-Type', 'text/xml');
    }
}
