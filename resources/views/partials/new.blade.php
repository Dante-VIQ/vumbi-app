<div class="container mx-auto px-6 py-12" x-data="{ showFilters: false }">

    {{-- Header with Title and Filter Toggle --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-4xl md:text-5xl font-light text-raw-linen">
                Field <span class="text-sunflare">Notes</span>
            </h1>
            <p class="text-[#C4B9A6] mt-2">Dispatches from overlooked places</p>
        </div>

        {{-- Filter Toggle Button (Mobile) --}}
        <button
            @click="showFilters = !showFilters"
            class="md:hidden bg-terracotta px-4 py-2 text-raw-linen flex items-center gap-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            Filters
        </button>
    </div>

    <div class="grid md:grid-cols-4 gap-8">

        {{-- Sidebar / Filters --}}
        <div
            class="md:block bg-[#2A3B4C] bg-opacity-30 p-6 border border-dust-mite"
            :class="{ 'hidden': !showFilters, 'block': showFilters }"
        >
            <div class="flex justify-between items-center mb-4 md:hidden">
                <h3 class="text-sunflare text-lg">Filters</h3>
                <button @click="showFilters = false" class="text-raw-linen text-2xl">&times;</button>
            </div>

            {{-- Search --}}
            <div class="mb-6">
                <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Search</label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search posts..."
                        class="w-full bg-transparent border border-dust-mite p-3 text-raw-linen focus:border-sunflare focus:outline-none"
                    >
                    @if($search)
                        <button
                            wire:click="$set('search', '')"
                            class="absolute right-3 top-3 text-[#C4B9A6] hover:text-sunflare"
                        >
                            ✕
                        </button>
                    @endif
                </div>
            </div>

            {{-- Categories --}}
            <div class="mb-6">
                <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Categories</label>
                <div class="space-y-2">
                    <div
                        wire:click="$set('selectedCategory', 'all')"
                        class="cursor-pointer p-2 transition {{ $selectedCategory === 'all' ? 'bg-terracotta text-raw-linen' : 'text-[#C4B9A6] hover:bg-terracotta hover:bg-opacity-30' }}"
                    >
                        All Categories
                    </div>

                    @foreach($categories as $category)
                        <div
                            wire:click="$set('selectedCategory', '{{ $category }}')"
                            class="cursor-pointer p-2 transition {{ $selectedCategory === $category ? 'bg-terracotta text-raw-linen' : 'text-[#C4B9A6] hover:bg-terracotta hover:bg-opacity-30' }}"
                        >
                            {{ ucfirst($category) }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Active Filters Summary --}}
            @if($selectedCategory !== 'all' || !empty($search))
            <div class="mb-6 pt-4 border-t border-dust-mite">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-[#C4B9A6]">Active filters:</span>
                    <button
                        wire:click="clearFilters"
                        class="text-sunflare text-sm hover:underline"
                    >
                        Clear all
                    </button>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($selectedCategory !== 'all')
                        <span class="bg-terracotta bg-opacity-30 px-3 py-1 text-sm flex items-center gap-2">
                            {{ ucfirst($selectedCategory) }}
                            <button wire:click="$set('selectedCategory', 'all')" class="text-raw-linen hover:text-sunflare">✕</button>
                        </span>
                    @endif
                    @if(!empty($search))
                        <span class="bg-terracotta bg-opacity-30 px-3 py-1 text-sm flex items-center gap-2">
                            "{{ $search }}"
                            <button wire:click="$set('search', '')" class="text-raw-linen hover:text-sunflare">✕</button>
                        </span>
                    @endif
                </div>
            </div>
            @endif

            {{-- Sort Options --}}
            <div class="mb-6">
                <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Sort by</label>
                <select
                    wire:model.live="sortField"
                    class="w-full bg-transparent border border-dust-mite p-3 text-raw-linen"
                >
                    <option value="created_at">Date</option>
                    <option value="title">Title</option>
                    <option value="category">Category</option>
                </select>

                <div class="flex mt-2">
                    <button
                        wire:click="$set('sortDirection', 'asc')"
                        class="flex-1 p-2 text-center transition {{ $sortDirection === 'asc' ? 'bg-terracotta text-raw-linen' : 'border border-dust-mite text-[#C4B9A6] hover:bg-terracotta hover:bg-opacity-30' }}"
                    >
                        Asc ↑
                    </button>
                    <button
                        wire:click="$set('sortDirection', 'desc')"
                        class="flex-1 p-2 text-center transition {{ $sortDirection === 'desc' ? 'bg-terracotta text-raw-linen' : 'border border-dust-mite text-[#C4B9A6] hover:bg-terracotta hover:bg-opacity-30' }}"
                    >
                        Desc ↓
                    </button>
                </div>
            </div>

            {{-- Posts Per Page --}}
            <div>
                <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Show</label>
                <select
                    wire:model.live="perPage"
                    class="w-full bg-transparent border border-dust-mite p-3 text-raw-linen"
                >
                    <option value="6">6 per page</option>
                    <option value="9">9 per page</option>
                    <option value="12">12 per page</option>
                    <option value="24">24 per page</option>
                </select>
            </div>
        </div>

        {{-- Blog Posts Grid --}}
        <div class="md:col-span-3">

            {{-- Results Count --}}
            <div class="mb-4 text-[#C4B9A6]">
                Showing {{ $blogs->firstItem() ?? 0 }} - {{ $blogs->lastItem() ?? 0 }} of {{ $blogs->total() }} blogs
            </div>

            {{-- Posts Grid --}}
            @if($blogs->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($blogs as $blog)
                        <article class="group border border-dust-mite hover:border-sunflare transition-all duration-300 overflow-hidden">
                            {{-- Media --}}
                            @if($blog->media_path)
                                <div class="h-48 overflow-hidden">
                                    @if($blog->media_type === 'image')
                                        <img src="{{ Storage::url($blog->media_path) }}" alt="{{ $blog->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @elseif($blog->media_type === 'video')
                                        <video src="{{ Storage::url($blog->media_path) }}" class="w-full h-full object-cover" muted></video>
                                    @endif
                                </div>
                            @else
                                <div class="h-48 bg-[#2A3B4C] flex items-center justify-center">
                                    <span class="text-4xl text-[#C4B9A6]">📝</span>
                                </div>
                            @endif

                            {{-- Content --}}
                            <div class="p-6">
                                {{-- Category Badge --}}
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs text-sunflare uppercase tracking-wider">
                                        {{ $blog->category }}
                                    </span>
                                    <span class="text-xs text-[#C4B9A6]">
                                        {{ $blog->reading_time }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h2 class="text-xl text-raw-linen group-hover:text-sunflare transition mb-3">
                                    <a href="{{ route('blog.show', $blog->id) }}">
                                        {{ $blog->title }}
                                    </a>
                                </h2>

                                {{-- Excerpt --}}
                                <p class="text-sm text-[#C4B9A6] mb-4">
                                    @php
                                        $rawDesc = $blog->getRawOriginal('description') ?? $blog->getOriginal('description') ?? '';
                                    @endphp
                                    {{ Str::limit(strip_tags($rawDesc), 120) }}
                                </p>

                                {{-- Meta --}}
                                <div class="flex justify-between items-center text-xs text-[#C4B9A6]">
                                    <span>By {{ $blog->author->name ?? 'Vumbi' }}</span>
                                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                </div>

                                {{-- Read More --}}
                                {{-- <a href="{{ route('blog.show', $blog->id) }}"
                                   class="block mt-4 text-sunflare text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                                    Read Article
                                    <span>→</span>
                                </a> --}}
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $blogs->links() }}
                </div>
            @else
                {{-- No Results --}}
                <div class="text-center py-24 border border-dust-mite">
                    <span class="text-6xl block mb-4">🔍</span>
                    <h3 class="text-2xl text-raw-linen mb-2">No blogs found</h3>
                    <p class="text-[#C4B9A6] mb-6">Try adjusting your search or filters</p>
                    <button
                        wire:click="clearFilters"
                        class="bg-terracotta px-6 py-3 text-raw-linen hover:bg-sunflare transition"
                    >
                        Clear all filters
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
