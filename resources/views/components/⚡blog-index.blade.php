<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Blog;

new class extends Component {
    use WithPagination;

    public $selectedCategory = 'all';
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 9;

    public $categories = [];

    protected $queryString = [
    'selectedCategory' => ['except' => 'all'],
    'search' => ['except' => ''],
    'sortField' => ['except' => 'created_at'],
    'sortDirection' => ['except' => 'desc'],
    'page' => ['except' => 1],
];

    public function mount()
    {
        $this->categories = Blog::select('category')->distinct()->pluck('category')->toArray();


    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedSelectedCategory() { $this->resetPage(); }

    public function clearFilters()
    {
        $this->selectedCategory = 'all';
        $this->search = '';
        $this->resetPage();
    }

    public function blogs()
    {
        $query = Blog::with('author');

        if ($this->selectedCategory !== 'all') {
            $query->where('category', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }


        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function with()
    {
        return [
            'blogs' => $this->blogs(),
            'categories' => $this->categories,
            'featuredBlogs' => Blog::where('is_featured', true)->latest()->take(1)->get(),
        ];
    }
};
?>

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">

    {{-- HERO --}}
    <section class="relative overflow-hidden pt-28 pb-16">
        <div class="absolute inset-0 grain opacity-[0.03]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(139,90,43,0.04),transparent_50%)]"></div>

        <div class="relative container mx-auto px-6 text-center max-w-4xl">
            <span class="inline-flex items-center gap-2 text-sm bg-white/70 backdrop-blur-sm border border-white/40 px-4 py-2 rounded-full shadow-sm">
                <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
                Field Notes Archive
            </span>

            <h1 class="text-5xl md:text-6xl font-semibold mt-6 leading-tight tracking-tight">
                Stories from the
                <span class="text-[#8B5A2B] relative inline-block">
                    Undiscovered
                    <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
                </span>
            </h1>

            <p class="mt-6 text-[#5C5C5C] text-lg leading-relaxed max-w-2xl mx-auto">
                Travel narratives, cultural encounters, and human stories from places that rarely make headlines — but always leave a mark.
            </p>
        </div>
    </section>

    {{-- STICKY FILTER BAR --}}
    <section class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-y border-black/5 py-4">
        <div class="container mx-auto px-6 flex flex-col md:flex-row gap-4 items-center justify-between">

            {{-- Search --}}
            <div class="w-full md:w-1/2">
                <input wire:model.live.debounce.400ms="search"
                    type="text"
                    placeholder="Search stories, places, cultures..."
                    class="w-full bg-white border border-black/10 px-5 py-3 rounded-xl focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]/20 transition shadow-sm">
            </div>

            {{-- Category Filter & Reset --}}
            <div class="flex gap-3 flex-wrap justify-center">
                <select wire:model.live="selectedCategory"
                    class="bg-white border border-black/10 px-4 py-3 rounded-xl text-sm focus:outline-none focus:border-[#8B5A2B] shadow-sm">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>

                <button wire:click="clearFilters"
                    class="px-4 py-3 rounded-xl bg-white border border-black/10 hover:bg-[#F5EFE6] transition text-sm shadow-sm">
                    Reset
                </button>
            </div>
        </div>
    </section>


    @foreach($featuredBlogs as $blog)
    {{-- your featured story card --}}

    {{-- FEATURED STORY (Optional - can be dynamic) --}}
    <section class="container mx-auto px-6 py-14">
        <div class="glass-panel rounded-3xl overflow-hidden border border-white/50 shadow-xl">
            <div class="grid md:grid-cols-2 items-center">
                <div class="aspect-[4/3] md:aspect-auto md:h-full overflow-hidden">
                    <img src="{{ asset($blog->media_path) }}"
                        alt="{{ $blog->title }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="p-8 md:p-10">
                    <span class="text-xs uppercase tracking-widest text-[#8B5A2B] font-medium">Featured Story</span>
                    <h2 class="text-2xl md:text-3xl font-semibold mt-3 leading-snug">
                        {{ $blog->title }}
                    </h2>
                    <p class="text-[#5C5C5C] mt-4 leading-relaxed">
                        {{ Str::limit(strip_tags($blog->description), 120) }}
                    </p>
                    <a href="{{ route('blog.show', $blog->id) }}" class="inline-block mt-6 px-6 py-3 bg-[#8B5A2B] text-white rounded-xl font-medium hover:bg-[#5C3A1E] transition shadow-md">
                        Read Story →
                    </a>
                </div>
            </div>
        </div>
    </section>

    @endforeach

    {{-- BLOG GRID --}}
    <section class="container mx-auto px-6 pb-24">
        @if($blogs->count())
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                    <article class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-xl hover:border-[#8B5A2B]/20 transition duration-300">
                        @if($blog->media_path)
                            <div class="h-52 overflow-hidden">
                                <img src="{{ asset($blog->media_path) }}"
                                    alt="{{ $blog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-center justify-between text-xs text-[#6B6B6B]">
                                <span class="uppercase tracking-wider">{{ $blog->category }}</span>
                                <span>{{ $blog->reading_time ?? '5 min read' }}</span>
                            </div>

                            <h3 class="mt-3 text-xl font-semibold group-hover:text-[#8B5A2B] transition-colors">
                                <a href="{{ route('blog.show', $blog->id) }}">{{ $blog->title }}</a>
                            </h3>

                            <p class="text-sm text-[#5C5C5C] mt-3 line-clamp-3">
                                {{ Str::limit(strip_tags($blog->description), 120) }}
                            </p>

                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-[#6B6B6B]">
                                    {{ $blog->author->name ?? 'Field Writer' }}
                                </span>

                                <a href="{{ route('blog.show', $blog->id) }}"
                                   class="text-sm font-medium text-[#8B5A2B] hover:text-[#5C3A1E] transition">
                                    Read →
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $blogs->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-20 text-[#6B6B6B]">
                <p class="text-lg">No stories found.</p>
                <p class="text-sm mt-2">Try adjusting your search or filters.</p>
            </div>
        @endif
    </section>

    {{-- Grain SVG Definition (reuse from homepage) --}}
    <style>
        .grain {
            background-image: url("https://grainy-gradients.vercel.app/noise.svg");
            opacity: 0.035;
            pointer-events: none;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</div>