<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\HeaderMedia;
use App\Models\Landing;
use Illuminate\Routing\Controller;

class HomePageController extends Controller
{
    public function home()
    {
        $blogs = Blog::latest()
            ->with('author')
            ->take(3)
            ->get();

        $featuredBlog = Blog::where('is_featured', true)
            ->latest()
            ->first();

        // $headerMedia = HeaderMedia::latest()
        //     ->take(6)
        //     ->get();

        return view('home', compact('blogs', 'featuredBlog'));
    }

    public function setDymanicSeo(Landing $landing)
    {
        $landing->seo->update([
            'title' => 'Vumbi Ventures — Discover Africa. Before You Travel.',
            'description' => 'Real stories, live prices, hidden gems, and authentic African experiences from Kenya to Cape Town.',
            'image' => 'images/posts/1.jpg', // Will point to `public_path('images/posts/1.jpg')`
        ]);
    }
}
