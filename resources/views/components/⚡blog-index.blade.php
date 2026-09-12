<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Blog;

new class extends Component {
    use WithPagination;

    #[Url(except: 'all')]
    public string $selectedCategory = 'all';

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: 'created_at')]
    public string $sortField = 'created_at';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public int $perPage = 9;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['selectedCategory', 'search']);
        $this->resetPage();
    }

    public function getCategoriesProperty()
    {
        return Blog::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    public function getFeaturedBlogsProperty()
    {
        // Don't show featured section if filtering or searching
        if ($this->selectedCategory !== 'all' || !empty($this->search)) {
            return collect();
        }

        return Blog::with('author')
            ->where('is_featured', true)
            ->latest()
            ->take(1)
            ->get();
    }

    public function blogs()
    {
        $featuredIds = $this->featuredBlogs->pluck('id')->toArray();

        $query = Blog::with('author');

        if (!empty($featuredIds)) {
            $query->whereNotIn('id', $featuredIds);
        }

        if ($this->selectedCategory !== 'all') {
            $query->where('category', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhere('category', 'like', $searchTerm);
            });
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function with(): array
    {
        return [
            'blogs' => $this->blogs(),
            'categories' => $this->categories,
            'featuredBlogs' => $this->featuredBlogs,
        ];
    }
};
?>

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">

    {{-- HERO HEADER --}}
    <section class="relative overflow-hidden pt-28 pb-16">
        <div class="absolute inset-0 grain opacity-[0.035] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(139,90,43,0.04),transparent_50%)]">
        </div>

        <div class="relative container mx-auto px-6 text-center max-w-4xl">
            <span
                class="inline-flex items-center gap-2 text-sm bg-white/70 backdrop-blur-sm border border-black/5 px-4 py-2 rounded-full shadow-sm text-zinc-700">
                <span class="w-2 h-2 bg-[#8B5A2B] rounded-full animate-pulse"></span>
                Field Notes Archive
            </span>

            <h1 class="text-4xl md:text-6xl font-semibold mt-6 leading-tight tracking-tight text-zinc-900">
                Stories from the
                <span class="text-[#8B5A2B] relative inline-block">
                    Undiscovered
                    <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
                </span>
            </h1>

            <p class="mt-6 text-[#5C5C5C] text-base md:text-lg leading-relaxed max-w-2xl mx-auto">
                Travel narratives, cultural encounters, and deep human stories from places that rarely make headlines —
                but always leave a lasting mark.
            </p>
        </div>
    </section>

    {{-- STICKY FILTER BAR --}}
    <section class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-y border-black/5 py-4 transition-all">
        <div class="container mx-auto px-6 flex flex-col md:flex-row gap-4 items-center justify-between">

            {{-- Search Bar --}}
            <div class="w-full md:w-1/2 relative">
                <input wire:model.live.debounce.350ms="search" type="text"
                    placeholder="Search stories, history, cultures..."
                    class="w-full bg-white border border-black/10 px-5 py-3 pr-10 rounded-xl text-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-2 focus:ring-[#8B5A2B]/20 transition shadow-sm placeholder:text-zinc-400">

                @if(!empty($search))
                    <button wire:click="$set('search', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-600 text-sm">
                        ✕
                    </button>
                @endif
            </div>

            {{-- Category Selector & Actions --}}
            <div class="flex gap-3 flex-wrap items-center justify-end w-full md:w-auto">
                <select wire:model.live="selectedCategory"
                    class="bg-white border border-black/10 px-4 py-3 rounded-xl text-sm focus:outline-none focus:border-[#8B5A2B] shadow-sm text-zinc-700 capitalize">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>

                @if($selectedCategory !== 'all' || !empty($search))
                    <button wire:click="clearFilters"
                        class="px-4 py-3 rounded-xl bg-[#8B5A2B]/10 border border-[#8B5A2B]/20 hover:bg-[#8B5A2B]/20 transition text-sm font-medium text-[#8B5A2B] shadow-sm flex items-center gap-2">
                        <span>Clear Filters</span>
                        <span>✕</span>
                    </button>
                @endif
            </div>
        </div>
    </section>

    {{-- FEATURED STORY SECTION --}}
    @if($featuredBlogs->count() > 0)
        @foreach($featuredBlogs as $blog)
            <section class="container mx-auto px-6 pt-12 pb-6">
                <div class="glass-panel rounded-3xl overflow-hidden border border-white/60 shadow-xl bg-white/70">
                    <div class="grid md:grid-cols-2 items-center">
                        <div class="aspect-[4/3] md:aspect-auto md:h-full overflow-hidden bg-zinc-200">
                            <img src="{{ $blog->media_path ? asset('storage/' . $blog->media_path) : 'https://picsum.photos/800/600?random=' . $blog->id }}"
                                alt="{{ $blog->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-8 md:p-12">
                            <span
                                class="inline-block text-xs uppercase tracking-widest text-[#8B5A2B] font-semibold bg-[#8B5A2B]/10 px-3 py-1 rounded-full mb-3">
                                Featured Story
                            </span>
                            <h2 class="text-2xl md:text-4xl font-semibold leading-snug text-zinc-900">
                                {{ $blog->title }}
                            </h2>
                            <p class="text-[#5C5C5C] mt-4 leading-relaxed line-clamp-3">
                                @php
                                    $rawDesc = $blog->getRawOriginal('description') ?? $blog->getRawOriginal('content') ?? $blog->getOriginal('description') ?? $blog->getOriginal('content') ?? '';
                                @endphp
                                {{ Str::limit(strip_tags($rawDesc), 160) }}
                            </p>
                            <div class="mt-8 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 font-medium">
                                    By {{ $blog->author->name ?? 'Vumbi Field Writer' }}
                                </span>
                                <a href="{{ route('blog.show', $blog->slug ?? $blog->id) }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#8B5A2B] text-white rounded-xl font-medium hover:bg-[#5C3A1E] transition shadow-md hover:shadow-lg">
                                    <span>Read Story</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    {{-- MAIN BLOG POSTS GRID --}}
    <section class="container mx-auto px-6 py-12 pb-24">
        @if($blogs->count())
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                    <article
                        class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-xl hover:border-[#8B5A2B]/20 transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="h-52 overflow-hidden bg-zinc-100 relative">
                                <img src="{{ $blog->media_path ? asset('uploads/' . $blog->media_path) : 'https://picsum.photos/600/400?random=' . $blog->id }}"
                                    alt="{{ $blog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @if($blog->category)
                                    <span
                                        class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-[#8B5A2B] text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-md shadow-sm">
                                        {{ $blog->category }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-6">
                                <div class="flex items-center justify-between text-xs text-[#6B6B6B] mb-2">
                                    <span>{{ $blog->created_at ? $blog->created_at->format('M d, Y') : '' }}</span>
                                    <span>{{ $blog->reading_time ?? '5 min read' }}</span>
                                </div>

                                <h3
                                    class="text-xl font-semibold text-zinc-900 group-hover:text-[#8B5A2B] transition-colors leading-snug">
                                    <a href="{{ route('blog.show', $blog->slug ?? $blog->id) }}">{{ $blog->title }}</a>
                                </h3>

                                <p class="text-sm text-[#5C5C5C] mt-3 line-clamp-3 leading-relaxed">
                                    @php
                                        $rawDesc = $blog->getRawOriginal('description') ?? $blog->getRawOriginal('content') ?? $blog->getOriginal('description') ?? $blog->getOriginal('content') ?? '';
                                    @endphp
                                    {{ Str::limit(strip_tags($rawDesc), 120) }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0 border-t border-zinc-100/80 mt-4">
                            <div class="pt-4 flex items-center justify-between">
                                <span class="text-xs text-[#6B6B6B] font-medium">
                                    {{ $blog->author->name ?? 'Vumbi Contributor' }}
                                </span>

                                <a href="{{ route('blog.show', $blog->slug ?? $blog->id) }}"
                                    class="text-sm font-semibold text-[#8B5A2B] hover:text-[#5C3A1E] transition flex items-center gap-1">
                                    <span>Read</span>
                                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-14 flex justify-center">
                {{ $blogs->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-zinc-300 max-w-xl mx-auto my-8">
                <div class="text-4xl mb-3">📖</div>
                <h3 class="text-lg font-semibold text-zinc-800">No stories found</h3>
                <p class="text-sm text-zinc-500 mt-1 max-w-sm mx-auto">
                    We couldn't find any articles matching your search query or selected category filter.
                </p>
                <button wire:click="clearFilters"
                    class="mt-6 px-5 py-2.5 rounded-xl bg-[#8B5A2B] text-white text-sm font-medium hover:bg-[#5C3A1E] transition shadow-md">
                    Reset All Filters
                </button>
            </div>
        @endif
    </section>

    {{-- Inline Styles --}}
    <style>
        .grain {
            background-image: url("https://grainy-gradients.vercel.app/noise.svg");
        }

        .glass-panel {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>
</div>