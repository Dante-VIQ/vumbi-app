<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Admin\CultureManager;
use App\Livewire\Admin\DestinationManager;
use App\Models\Blog;
use App\Models\Culture;
use App\Models\Destination;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::view('/', 'home');

// /doctor/123 → /destinations/maasai-mara
Route::get('/doctor/{id}', function ($id) {
    $destination = Destination::where('legacy_doctor_id', $id)->firstOrFail();

    return redirect()->route('destination.show', [
        'slug' => $destination->slug
    ], 301);
})->whereNumber('id');

// /doctors/123 → /doctor/123
Route::get('/doctors/{id}', function ($id) {
    return redirect("/doctor/$id", 301);
})->whereNumber('id');


/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/

Route::view('/about', 'pages.about');
Route::view('/services', 'pages.services');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/ecosystem', 'pages.ecosystem');
Route::view('/header', 'pages.header-media');

Route::post('/contact', [ContactController::class, 'submit'])
    ->name('contact.submit');


/*
|--------------------------------------------------------------------------
| DISCOVERY
|--------------------------------------------------------------------------
*/

Route::prefix('discover')->group(function () {
    Route::get('/', [DiscoveryController::class, 'index']);
    Route::post('/search', [DiscoveryController::class, 'search'])
        ->name('discover.search');


    // MUST BE LAST to avoid conflicts
    Route::get('/{country}/{city}', [CityController::class, 'show'])
        ->name('discover.city');
});

/*
|--------------------------------------------------------------------------
| DESTINATIONS & CULTURE
|--------------------------------------------------------------------------
*/

Route::get('/destinations/{slug}', function ($slug) {
    return view('pages.destination', compact('slug'));
})->name('destination.show');

Route::get('/cultures/{slug}', function ($slug) {
    return view('pages.culture', compact('slug'));
})->name('culture.show');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// ======================
// BLOG ROUTES (Public)
// ======================

/*
|--------------------------------------------------------------------------
| BLOG PUBLIC
|--------------------------------------------------------------------------
*/

// Blog index
Route::get('/blog', function () {
    $blogs = Blog::latest()->paginate(12);
    return view('partials.field-notes', compact('blogs'));
})->name('blog.index');

// Blog post by slug
Route::get('/blog/{id}', function ($id) {
    $blog = Blog::where('id', $id)
        ->with('author')
        ->firstOrFail();

    return view('singleblog', compact('blog'));
})->name('blog.show');

// Category
Route::get('/blog/category/{category}', function ($category) {
    $blogs = Blog::where('category', $category)->latest()->paginate(12);
    return view('blog.category', compact('blogs', 'category'));
})->name('blog.category');

// Tag
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
/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')
        ->name('dashboard')
        ->middleware('verified');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Route::view('africa', 'admin.dashboard')->name('admin.dashboard');
/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:master|engineer'])
    ->prefix('admin')
    ->group(function () {

        Route::view('/africa', 'admin.dashboard');

        // Blog CRUD
        Route::resource('blogs', BlogController::class);
        Route::delete('blogs/bulk/delete',
            [BlogController::class, 'bulkDestroy']
        )->name('blogs.bulk-destroy');

        // Culture CRUD
        Route::resource('cultures', CultureController::class);

        // Livewire pages
        Route::view('/places', 'pages.destination-manager');
        Route::view('/people', 'pages.culture-manager');
    });

require __DIR__.'/api.php';
require __DIR__.'/auth.php';
