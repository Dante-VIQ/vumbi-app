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

    if ($cached && !app()->environment('local')) {
        return response($cached, 200)->header('Content-Type', 'text/xml');
    }

    $staticPages = [
        (object) ['loc' => url('/'), 'lastmod' => now()->toDateString(), 'priority' => '1.0', 'changefreq' => 'weekly'],
        (object) ['loc' => url('/ecosystem'), 'lastmod' => now()->toDateString(), 'priority' => '0.8', 'changefreq' => 'monthly'],
        (object) ['loc' => url('/contact'), 'lastmod' => now()->toDateString(), 'priority' => '0.6', 'changefreq' => 'yearly'],
        (object) ['loc' => url('/discover'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'weekly'],
        (object) ['loc' => url('/blog'), 'lastmod' => now()->toDateString(), 'priority' => '0.9', 'changefreq' => 'daily'],
    ];

    $blogs = Blog::orderBy('updated_at', 'desc')
        ->get()
        ->map(function ($post) {
            $post->loc = route('blog.show', $post->id);
            $post->lastmod = $post->updated_at->toDateString();
            $post->priority = '0.7';
            $post->changefreq = 'monthly';
            return $post;
        });

    $all = collect($staticPages)->merge($blogs);

    $view = view('sitemap', ['pages' => $all])->render();

    // Cache for 24 hours in production
    if (!app()->environment('local')) {
        cache()->put($cacheKey, $view, now()->addDay());
    }

    return response($view, 200)->header('Content-Type', 'text/xml');
}
}
