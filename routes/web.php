<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\ProfileController;
use App\Models\Blog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::get('/', function () {
    return view('home');
});

Route::get('about', function () {
    return view('pages.about');
});

Route::get('services', function () {
    return view('pages.services');
});

Route::get('contact', function () {
    return view('pages.contact');
});

Route::get('ecosystem', function () {
    return view('pages.ecosystem');
});




// ======================
// BLOG ROUTES (Public)
// ======================

// Main blog index / list page (optional - you can expand this later)
Route::get('/blog', function () {
    $blogs = Blog::latest()->paginate(12);
    return view('partials.field-notes', compact('blogs')); // Create this view if needed
})->name('blog.index');

// Individual blog post - SEO-friendly with slug (RECOMMENDED)
Route::get('blog/{id}', function ($id) {
    $blog = Blog::with('author')->findOrFail($id); // or find($id) if you handle 404 manually
    return view('singleblog', compact('blog'));
})->name('blog.show');

// Category pages (example)
Route::get('/blog/category/{category}', function ($category) {
    $blogs = Blog::where('category', $category)
                ->latest()
                ->paginate(12);
    return view('blog.category', compact('blogs', 'category'));
})->name('blog.category');

// Optional: Tag pages
Route::get('/blog/tag/{tag}', function ($tag) {
    $blogs = Blog::where('tags', 'LIKE', "%{$tag}%")
                ->latest()
                ->paginate(12);
    return view('blog.tag', compact('blogs', 'tag'));
})->name('blog.tag');

// ======================
// ADMIN / CRUD ROUTES (Protected)
// ======================

// Fallback or home route
// Route::resource('blogs', BlogController::class);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::view('africa', 'admin.dashboard')->name('admin.dashboard');
Route::middleware(['auth', 'role:master|engineer'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('africa', function () {
        return view('admin.dashboard');
    });
    // Blog CRUD
    Route::resource('blogs', BlogController::class);
    Route::delete('blogs/bulk/delete', [BlogController::class, 'bulkDestroy'])->name('blogs.bulk-destroy');
});

Route::get('header', function () {
    return view('pages.header-media');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
