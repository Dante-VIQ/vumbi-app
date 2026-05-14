<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view-blog')->only(['index', 'show']);
        // $this->middleware('permission:create-blog')->only(['create', 'store']);
        // $this->middleware('permission:edit-blog')->only(['edit', 'update']);
        // $this->middleware('permission:delete-blog')->only(['destroy', 'bulkDestroy']);
    }

    public function index(Request $request)
    {
        $query = Blog::with('author');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $allowedSorts = ['created_at', 'title', 'category', 'updated_at'];
        $sortField = in_array($request->get('sort_field'), $allowedSorts) ? $request->get('sort_field') : 'created_at';
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';

        $blogs = $query->orderBy($sortField, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        $categories = Blog::select('category')->distinct()->pluck('category');

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        $categories = Blog::select('category')->distinct()->pluck('category');

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(StoreBlogRequest $request)
    {
        $key = 'blog-store|'.$request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 8)) { // 8 posts per hour per admin
            abort(429, 'Too many blog posts created recently. Please try again later.');
        }
        RateLimiter::hit($key, 3600);

        $validated = $request->validated();

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            // Store directly inside 'public/uploads/headers' (subfolder for organization)
            // The 'public_direct' disk should point to 'public' directory (default config)
            $storedPath = $request->file('media')->store('uploads/headers', 'public_direct');

            // $storedPath will be something like 'uploads/headers/abc123.jpg'
            $mediaPath = 'uploads/'.$storedPath;   // No extra prepending!
            $validated['media_path'] = $mediaPath;
            $validated['media_type'] = $request->media_type;
            // $mediaType = $this->getMediaType($this->media->getClientOriginalExtension());
        }

        $validated['user_id'] = $request->user()->id;

        Blog::create($validated);

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = Blog::select('category')->distinct()->pluck('category');

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();

        // Remove old media
        if ($request->boolean('remove_media') && $blog->media_path) {
            Storage::disk('public_direct')->delete($blog->media_path);
            $blog->media_path = null;
            $blog->media_type = null;
        }

        // Upload new media
        if ($request->hasFile('media')) {
            if ($blog->media_path) {
                Storage::disk('public_direct')->delete($blog->media_path);
            }
            $mediaPath = $request->file('media')->store('blog-media', 'public_direct');
            $validated['media_path'] = $mediaPath;
            $validated['media_type'] = $request->media_type;
        }

        $blog->update($validated);

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->media_path) {
            Storage::disk('public_direct')->delete($blog->media_path);
        }

        $blog->delete();

        return redirect()->route('blogs.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:blogs,id',
        ]);

        $blogs = Blog::whereIn('id', $request->ids)->get();

        foreach ($blogs as $blog) {
            if ($blog->media_path) {
                Storage::disk('public_direct')->delete($blog->media_path);
            }
            $blog->delete();
        }

        return redirect()->route('blogs.index')
            ->with('success', count($request->ids).' blog posts deleted successfully.');
    }
}
