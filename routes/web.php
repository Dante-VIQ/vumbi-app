<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Admin\CultureManager;
use App\Livewire\Admin\DestinationManager;
use App\Models\Blog;
use App\Models\Culture;
use App\Models\Destination;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CultureController;




Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::post('/discover/search', DiscoveryController::class)->name('discovery.search');

Route::get('/doctor/{id}', function ($id) {
    $destination = Destination::where('legacy_doctor_id', $id)->first();
    if ($destination) {
        return redirect()->route('destination.show', $destination, 301);
    }
    abort(404);
})->where('id', '[0-9]+');

// Also handle plural /doctors/{id}
Route::get('/doctors/{id}', function ($id) {
    return redirect()->route('doctor.redirect', ['id' => $id], 301);
})->where('id', '[0-9]+');

// Named route for convenience
Route::get('/doctor/{id}')->name('doctor.redirect');

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
})->name('contact');

Route::get('ecosystem', function () {
    return view('pages.ecosystem');
});

// routes/web.php
Route::get('/discover', function () {
    // Load initial data for the static browse mode — exactly what your mount() did
    $destinations = Destination::latest()->limit(6)->get();
    $cultureEntries = Culture::latest()->limit(6)->get();
    return view('pages.discovery', compact('destinations', 'cultureEntries'));
})->name('pages.discovery');


Route::get('/destinations/{slug}', function ($slug) {
    return view('pages.destination', ['slug' => $slug]);
})->name('destination.show');

Route::get('/cultures/{slug}', function ($slug) {
    return view('pages.culture', ['slug' => $slug]);
})->name('culture.show');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

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
    Route::resource('cultures', CultureController::class);
    Route::delete('blogs/bulk/delete', [BlogController::class, 'bulkDestroy'])->name('blogs.bulk-destroy');

   Route::get('places', function () {
    return view('pages.destination-manager');
});

   Route::get('people', function () {
    return view('pages.culture-manager');
});

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
