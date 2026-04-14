{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard - Vumbi Ventures Admin')
@section('header', 'Dashboard')

@section('content')
<div class="grid md:grid-cols-3 gap-6 mb-8">
    {{-- Total Posts Card --}}
    <div class="bg-indigo-night bg-opacity-30 border border-dust-mite p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sunflare text-lg">Total Posts</h3>
            <i class="fas fa-blog text-3xl text-sunflare"></i>
        </div>
        <p class="text-4xl font-light">{{ \App\Models\Blog::count() }}</p>
        <p class="text-sm text-[#C4B9A6] mt-2">Across all categories</p>
    </div>

    {{-- Categories Card --}}
    <div class="bg-indigo-night bg-opacity-30 border border-dust-mite p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sunflare text-lg">Categories</h3>
            <i class="fas fa-tags text-3xl text-sunflare"></i>
        </div>
        <p class="text-4xl font-light">{{ \App\Models\Blog::select('category')->distinct()->count() }}</p>
        <p class="text-sm text-[#C4B9A6] mt-2">Active categories</p>
    </div>

    {{-- Recent Activity Card --}}
    <div class="bg-indigo-night bg-opacity-30 border border-dust-mite p-6 rounded">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sunflare text-lg">Recent</h3>
            <i class="fas fa-clock text-3xl text-sunflare"></i>
        </div>
        <p class="text-4xl font-light">{{ \App\Models\Blog::where('created_at', '>=', now()->subDays(7))->count() }}</p>
        <p class="text-sm text-[#C4B9A6] mt-2">Posts in last 7 days</p>
    </div>
</div>

{{-- Recent Posts Table --}}
<div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded p-6">
    <h3 class="text-sunflare text-lg mb-4">Recent Posts</h3>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dust-mite">
                    <th class="pb-3 text-left text-sm text-[#C4B9A6]">Title</th>
                    <th class="pb-3 text-left text-sm text-[#C4B9A6]">Category</th>
                    <th class="pb-3 text-left text-sm text-[#C4B9A6]">Created</th>
                    <th class="pb-3 text-left text-sm text-[#C4B9A6]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Blog::with('author')->latest()->take(5)->get() as $blog)
                <tr class="border-b border-dust-mite border-opacity-50">
                    <td class="py-3">{{ Str::limit($blog->title, 40) }}</td>
                    <td class="py-3">
                        <span class="bg-terracotta bg-opacity-30 px-2 py-1 text-xs rounded">
                            {{ ucfirst($blog->category) }}
                        </span>
                    </td>
                    <td class="py-3">{{ $blog->created_at->diffForHumans() }}</td>
                    <td class="py-3">
                        <a href="{{ route('blogs.edit', $blog) }}" class="text-sunflare hover:underline">
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-right">
        <a href="{{ route('blogs.index') }}" class="text-sunflare hover:underline">
            View all posts →
        </a>
    </div>
</div>
@endsection
