<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PartnerLeadController;
use App\Http\Controllers\Admin\PartnerPackageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CulturalController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LandingController;
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

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/', [HomePageController::class, 'home'])->name('home');

Route::get('/go/{source}/{tourId}', [RedirectController::class, 'affiliate'])
    ->whereNumber('tourId')
    ->name('affiliate.redirect');

Route::get('/culture', [CulturalController::class, 'index'])->name('cultures.give');
Route::get('/culture/{culture:id}', [CulturalController::class, 'show'])->name('pages.culture');

Route::get('/destinations', [PlaceController::class, 'index'])->name('destinations.give');
Route::get('/destinations/{destination}', [PlaceController::class, 'show'])->name('pages.destination');
Route::get('/doctor/{id}', function ($id) {
    $destination = Destination::where('legacy_doctor_id', $id)->firstOrFail();
    return redirect()->route('pages.destination', ['destination' => $destination->slug], 301);
})->whereNumber('id');

Route::get('/doctors/{id}', function ($id) {
    return redirect("/doctor/$id", 301);
})->whereNumber('id');

Route::post('/inquiry/send-quote', [InquiryController::class, 'sendQuote'])
    ->middleware('throttle:5,1')
    ->name('inquiry.send-quote');

Route::view('/about', 'pages.about');
Route::view('/services', 'pages.services');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/safari-checklist', 'pages.safari-checklist')->name('safari-checklist');
Route::get('/newsletter/unsubscribe/{id}', [\App\Http\Controllers\NewsletterUnsubscribeController::class, 'show'])
    ->whereNumber('id')->middleware('signed')->name('newsletter.unsubscribe');
Route::post('/newsletter/unsubscribe/{id}', [\App\Http\Controllers\NewsletterUnsubscribeController::class, 'destroy'])
    ->whereNumber('id')->middleware('signed')->name('newsletter.unsubscribe.confirm');
Route::view('/ecosystem', 'pages.ecosystem');
Route::view('/header', 'pages.header-media');
Route::view('/studio', 'studio');
Route::get('/studio/case-studies/{slug}', function (string $slug) {
    $study = config("case_studies.$slug");
    abort_if(! $study, 404);
    return view('studio-case-study', ['study' => $study, 'slug' => $slug, 'title' => $study['name'].' case study | Vumbi Tech Studio']);
})->where('slug', '[a-z0-9-]+')->name('studio.case-study');
Route::view('/demo', 'demo');

Route::get('/booking/{partnerPackage:slug}', function (PartnerPackage $partnerPackage) {
    return view('booking', compact('partnerPackage'));
})->name('booking');

Route::post('/contact', [ContactController::class, 'submit'])
    ->name('contact.submit');

Route::prefix('discover')->group(function () {
    Route::get('/', [DiscoveryController::class, 'index']);
    Route::post('/search', [DiscoveryController::class, 'search'])->name('discover.search');
    Route::get('/{country}/{city}', [CityController::class, 'show'])->name('discover.city');
});

Route::prefix('tours')->name('tours.')->group(function () {
    Route::get('/', [TourController::class, 'index'])->name('index');
    Route::get('/{package:slug}', [TourController::class, 'show'])->name('show');
});

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit')->middleware('throttle:5,10');

Route::get('/portfolio', function () {
    return view('portfolio');
})->name('portfolio');

Route::get('/blog', function () {
    $blogs = Blog::latest()->paginate(12);
    return view('blog', compact('blogs'));
})->name('blog');

Route::get('/blog/{blog}', function (Blog $blog) {
    $blog->load('author');
    return view('singleblog', compact('blog'));
})->name('blog.show');

Route::get('/blog/category/{category}', function ($category) {
    $blogs = Blog::where('category', $category)->latest()->paginate(12);
    return view('blog.category', compact('blogs', 'category'));
})->name('blog.category');

Route::get('/blog/tag/{tag}', function ($tag) {
    $blogs = Blog::where('tags', 'LIKE', "%{$tag}%")
        ->latest()
        ->paginate(12);
    return view('blog.tag', compact('blogs', 'tag'));
})->name('blog.tag');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard')->middleware('verified');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:master|engineer'])
    ->prefix('admin')
    ->group(function () {
        Route::view('/africa', 'admin.dashboard');
        Route::resource('blogs', BlogController::class);
        Route::delete('blogs/bulk/delete', [BlogController::class, 'bulkDestroy'])->name('blogs.bulk-destroy');
        Route::resource('cultures', CultureController::class);
        Route::view('/places', 'pages.destination-manager');
        Route::view('/people', 'pages.culture-manager');
        Route::resource('packages', PartnerPackageController::class);
        Route::get('leads', [PartnerLeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [PartnerLeadController::class, 'show'])->name('leads.show');
        Route::patch('leads/{lead}/status', [PartnerLeadController::class, 'updateStatus'])->name('leads.update-status');
        Route::get('leads/export', [PartnerLeadController::class, 'export'])->name('leads.export');
    });

require __DIR__.'/api.php';
require __DIR__.'/auth.php';
