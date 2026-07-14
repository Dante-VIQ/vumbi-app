<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PartnerLeadController;
use App\Http\Controllers\Admin\PartnerPackageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CulturalController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TourController;
use App\Models\Blog;
use App\Models\Culture;
use App\Models\Destination;
use App\Models\HeaderMedia;
use App\Models\PartnerPackage;
use Illuminate\Support\Facades\Route;






Route::get('/sitemap.xml', [SitemapController::class, 'index']);
// Route::view('/', 'home');

Route::get('/', function () {
    $blogs = App\Models\Blog::latest()
                ->with('author')
                ->take(3)
                ->get();

    $featuredBlog = App\Models\Blog::where('is_featured', true)
                ->latest()
                ->first();

    $headerMedia = App\Models\HeaderMedia::latest()
                ->take(6)
                ->get();

    return view('home', compact('blogs', 'featuredBlog', 'headerMedia'));
});

Route::get('/go/{source}/{tourId}', [RedirectController::class, 'affiliate'])
    ->name('affiliate.redirect');

Route::get('/cultures', [CulturalController::class, 'index'])->name('cultures.index');   
Route::get('/destinations', [PlaceController::class, 'index'])->name('destinations.index');

// /doctor/123 → /destinations/maasai-mara
Route::get('/doctor/{id}', function ($id) {
    $destination = Destination::where('legacy_doctor_id', $id)->firstOrFail();

    return redirect()->route('destination.show', [
        'slug' => $destination->slug,
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

Route::get('/booking/{partnerPackage:slug}', function (PartnerPackage $partnerPackage) {
    return view('booking', compact('partnerPackage'));
})->name('booking');


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
| TOURS AND SAFARI
|--------------------------------------------------------------------------
*/

Route::prefix('tours')->name('tours.')->group(function () {
    Route::get('/', [TourController::class, 'index'])->name('index');
    Route::get('/{package:slug}', [TourController::class, 'show'])->name('show');
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

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit')->middleware('throttle:5,10');

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

// Blog post by ID
Route::get('/blog/{blog}', function (Blog $blog) {
    $blog->load('author');

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

        Route::resource('packages', PartnerPackageController::class);

        // Leads management (read-only + status update)
        Route::get('leads', [PartnerLeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [PartnerLeadController::class, 'show'])->name('leads.show');
        Route::patch('leads/{lead}/status', [PartnerLeadController::class, 'updateStatus'])->name('leads.update-status');

        Route::get('leads/export', [PartnerLeadController::class, 'export'])->name('leads.export');
    });

require __DIR__.'/api.php';
require __DIR__.'/auth.php';
