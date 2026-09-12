<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LandingController extends Controller
{
     public function index() {
        $blogs = \App\Models\Blog::latest()->take(3)->get();
        $featuredBlog = \App\Models\Blog::where('is_featured', true)->latest()->first();
        $headerMedia = \App\Models\HeaderMedia::latest()->take(6)->get();

        return view('home', compact('blogs', 'featuredBlog', 'headerMedia'));
     }
}
