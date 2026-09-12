@extends('layouts.admin')

@section('title', 'Manage Blog Posts - Vumbi Ventures Admin')
@section('header', 'Blog Posts')

@section('content')
    <div class="space-y-6">
        {{-- Header with Create Button --}}
        <div class="flex justify-between items-center">
            <div class="text-sm text-[#C4B9A6]">
                Total Posts: <span class="text-raw-linen">{{ $blogs->total() }}</span>
            </div>
            @can('create-blog', App\Models\Blog::class)
                <a href="{{ route('blogs.create') }}"
                    class="bg-terracotta px-6 py-3 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Create New Post
                </a>
            @endcan
        </div>

        {{-- Filters --}}
        <div class="bg-indigo-night bg-opacity-30 p-6 border border-dust-mite rounded">
            <form method="GET" class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by title or content..."
                        class="w-full bg-transparent border border-dust-mite p-3 text-raw-linen focus:border-sunflare focus:outline-none">
                </div>

                <div>
                    <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Category</label>
                    <select name="category"
                        class="w-full bg-transparent border border-dust-mite p-3 text-raw-linen focus:border-sunflare focus:outline-none">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Sort By</label>
                    <select name="sort_field"
                        class="w-full bg-transparent border border-dust-mite p-3 text-raw-linen focus:border-sunflare focus:outline-none">
                        <option value="created_at" {{ request('sort_field', 'created_at') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="title" {{ request('sort_field') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="category" {{ request('sort_field') == 'category' ? 'selected' : '' }}>Category</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-terracotta px-6 py-3 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition flex-1">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>

                    <a href="{{ route('blogs.index') }}"
                        class="border border-dust-mite px-6 py-3 text-raw-linen hover:bg-terracotta transition">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center gap-4 p-4 bg-indigo-night bg-opacity-30 border border-dust-mite rounded"
            x-data="{ showBulkActions: false, selectedIds: [] }">
            <div class="flex items-center gap-2">
                <input type="checkbox" @change="showBulkActions = $event.target.checked;
                                if($event.target.checked) {
                                    document.querySelectorAll('.select-item').forEach(cb => { cb.checked = true; selectedIds.push(cb.value); });
                                } else {
                                    document.querySelectorAll('.select-item').forEach(cb => { cb.checked = false; selectedIds = []; });
                                }" class="w-4 h-4 bg-transparent border border-dust-mite">
                <span class="text-sm text-[#C4B9A6]">Select All</span>
            </div>

            <div x-show="showBulkActions" x-cloak class="flex gap-2">
                <form action="{{ route('blogs.bulk-destroy') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete selected posts?')">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedIds">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit"
                        class="border border-red-500 px-4 py-2 text-red-500 hover:bg-red-500 hover:text-raw-linen transition text-sm">
                        <i class="fas fa-trash mr-2"></i>Delete Selected
                    </button>
                </form>
            </div>
        </div>

        {{-- Blog Posts Table --}}
        <div class="overflow-x-auto border border-dust-mite rounded">
            <table class="w-full">
                <thead class="bg-indigo-night">
                    <tr>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider w-12"></th>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider">ID</th>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider">Media</th>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider">Title</th>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider">Category</th>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider">Author</th>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider">Created</th>
                        <th class="p-4 text-left text-sm font-medium text-sunflare uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dust-mite">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-indigo-night hover:bg-opacity-30 transition">
                            <td class="p-4">
                                <input type="checkbox" value="{{ $blog->id }}"
                                    class="select-item w-4 h-4 bg-transparent border border-dust-mite" @change="if($event.target.checked) { selectedIds.push($event.target.value) }
                                              else { selectedIds = selectedIds.filter(id => id != $event.target.value) }">
                            </td>
                            <td class="p-4">{{ $blog->id }}</td>
                            <td class="p-4">
                                @if($blog->media_path)
                                    @if($blog->media_type === 'image')
                                        <div class="w-12 h-12 bg-cover bg-center rounded"
                                            style="background-image: url('{{ Storage::url($blog->media_path) }}')"></div>
                                    @elseif($blog->media_type === 'video')
                                        <div class="w-12 h-12 bg-terracotta bg-opacity-30 flex items-center justify-center rounded">
                                            <i class="fas fa-video text-sunflare"></i>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-12 h-12 bg-indigo-night flex items-center justify-center rounded">
                                        <i class="fas fa-image text-[#C4B9A6]"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 max-w-xs">
                                <p class="font-medium">{{ $blog->title }}</p>
                                <p class="text-sm text-[#C4B9A6]">
                                    @php $rawDesc = $blog->getRawOriginal('description') ?? $blog->getOriginal('description') ?? ''; @endphp{{ Str::limit(strip_tags($rawDesc), 60) }}
                                </p>
                            </td>
                            <td class="p-4">
                                <span class="bg-terracotta bg-opacity-30 px-3 py-1 text-sm rounded">
                                    {{ ucfirst($blog->category) }}
                                </span>
                            </td>
                            <td class="p-4">{{ $blog->author->name ?? 'Unknown' }}</td>
                            <td class="p-4">
                                <div>{{ $blog->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-[#C4B9A6]">{{ $blog->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    @can('update-blog', $blog)
                                        <a href="{{ route('blogs.edit', $blog) }}"
                                            class="p-2 border border-dust-mite hover:bg-terracotta transition text-sunflare"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    {{-- <a href="{{ route('blog.show', $blog->id) }}" target="_blank"
                                        class="p-2 border border-dust-mite hover:bg-terracotta transition text-sunflare"
                                        title="View">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a> --}}
                                    @can('delete-blog', $blog)
                                        <form action="{{ route('blogs.destroy', $blog) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 border border-dust-mite hover:bg-red-500 hover:text-raw-linen transition text-red-500"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-[#C4B9A6]">
                                <i class="fas fa-blog text-4xl mb-4 block"></i>
                                <p class="text-xl mb-2">No blog posts found</p>
                                <p class="mb-4">Get started by creating your first post</p>
                                @can('create-blog', App\Models\Blog::class)
                                    <a href="{{ route('blogs.create') }}"
                                        class="bg-terracotta px-6 py-3 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition inline-flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        Create New Post
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $blogs->withQueryString()->links() }}
        </div>
    </div>

    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Initialize bulk selection
            document.addEventListener('alpine:init', () => {
                Alpine.data('bulkSelect', () => ({
                    selectedIds: [],
                    showBulkActions: false,
                }))
            })
        </script>
    @endpush
@endsection