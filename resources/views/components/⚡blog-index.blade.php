<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Blog;

new class extends Component {
    use WithPagination;

    // Properties
    public $selectedCategory = 'all';
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 9;

    // Filters
    public $categories = [];

    // Events
    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    // Initialize
    public function mount()
    {
        try {
            // Get all unique categories from the blog table
            $this->categories = Blog::select('category')->distinct()->pluck('category')->toArray();
        } catch (\Exception $e) {
            // If table doesn't exist yet, set empty categories
            $this->categories = [];
            // Log the error for debugging
            logger()->error('Blog table error: ' . $e->getMessage());
        }
    }

    // Real-time search (updates as user types)
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // When category changes
    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->selectedCategory = 'all';
        $this->search = '';
        $this->resetPage();
    }

    // Get blogs for the current page
    public function blogs()
    {
        try {
            $query = Blog::with('author'); // Eager load author

            // Apply category filter
            if ($this->selectedCategory !== 'all') {
                $query->where('category', $this->selectedCategory);
            }

            // Apply search
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }

            // Apply sorting
            $query->orderBy($this->sortField, $this->sortDirection);

            return $query->paginate($this->perPage);
        } catch (\Exception $e) {
            // Return empty paginator if table doesn't exist
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->perPage);
        }
    }

    // For Volt, we need to explicitly pass data to the view
    public function with()
    {
        return [
            'blogs' => $this->blogs(),
            'categories' => $this->categories,
        ];
    }
};

?>

<div>
    <section class="relative pt-32 pb-16 bg-white overflow-hidden">
        <!-- Dust particles background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-32 h-32 bg-[#8B5A2B]/5 rounded-full animate-pulse-slow"></div>
            <div class="absolute bottom-20 right-10 w-40 h-40 bg-[#D98C5F]/5 rounded-full animate-pulse-slow"
                style="animation-delay: 1s;"></div>
            <div class="absolute top-40 right-20 w-24 h-24 bg-[#C7B5A6]/10 rounded-full animate-pulse-slow"
                style="animation-delay: 2s;"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block mb-6">
                    <span class="bg-[#E5E0D9] text-[#8B5A2B] px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-pen-fancy mr-2"></i>Stories from the Field
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Field <span class="text-[#8B5A2B] relative">
                        Notes
                        <span class="absolute bottom-2 left-0 w-full h-3 bg-[#D98C5F]/20 -z-10"></span>
                    </span>
                </h1>
                <p class="text-xl text-[#6B6B6B] leading-relaxed max-w-3xl mx-auto">
                    Documenting the overlooked people, cultures, and destinations that inspire our work.
                    Real stories from the places others forget.
                </p>
            </div>
        </div>
    </section>

    <!-- Categories Navigation -->
    <section class="py-8 border-y border-[#E5E0D9] bg-white static top-20 z-40">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="/"
                    class="category-badge bg-[#8B5A2B] text-white px-5 py-2 rounded-full text-sm font-medium">
                    All Stories
                </a>

                @foreach ($categories as $category)
                    <a wire:click="$set('selectedCategory', '{{ $category }}')" href=""
                        class="category-badge bg-[#F9F5F0] text-[#1A1A1A] hover:bg-[#8B5A2B] hover:text-white px-5 py-2 rounded-full text-sm font-medium transition {{ $selectedCategory === $category ? 'bg-terracotta text-raw-linen' : 'text-[#C4B9A6] hover:bg-terracotta hover:bg-opacity-30' }}">
                        <i class="fas fa-user mr-1"></i>{{ ucfirst($category) }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Article -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold mb-6 flex items-center">
                <i class="fas fa-star text-[#8B5A2B] mr-2"></i> Featured Story
            </h2>

            <div
                class="featured-article bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] rounded-3xl overflow-hidden relative">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative z-10 p-8 md:p-12 text-white">
                    <div
                        class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-4">
                        <i class="fas fa-user mr-2"></i>People · 6 min read
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 max-w-2xl">
                        The Tailor Who Teaches: How a Kumasi Seamstress Built a Secret School
                    </h2>
                    <p class="text-white/90 text-lg mb-6 max-w-2xl">
                        In a small workshop in Ghana's second city, Ama Serwaa has been quietly educating dozens of
                        children whose families couldn't afford formal schooling.
                    </p>
                    <div class="flex items-center gap-6 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm opacity-90">Published</div>
                                <div class="font-semibold">March 15, 2025</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-eye text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm opacity-90">Views</div>
                                <div class="font-semibold">2.4K</div>
                            </div>
                        </div>
                    </div>
                    <a href="#"
                        class="inline-flex items-center gap-2 bg-white text-[#8B5A2B] px-6 py-3 rounded-full font-semibold hover:bg-[#F9F5F0] transition">
                        Read Featured Story <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Blog Grid -->
    <section class="py-12 dust-bg">
        <div class="container mx-auto px-6">
            <!-- Search and Filter Bar -->
            <div class="bg-white p-4 rounded-2xl shadow-sm mb-10">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-[#C7B5A6]"></i>
                        <input type="text" placeholder="Search stories..."
                            class="w-full pl-12 pr-4 py-3 border border-[#E5E0D9] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]">
                    </div>
                    <div class="flex gap-2">
                        <select
                            class="px-4 py-3 border border-[#E5E0D9] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
                            <option>All Categories</option>
                            <option>People</option>
                            <option>Culture</option>
                            <option>Destinations</option>
                            <option>Innovation</option>
                            <option>Interviews</option>
                        </select>
                        <select
                            class="px-4 py-3 border border-[#E5E0D9] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
                            <option>Latest</option>
                            <option>Most Read</option>
                            <option>Oldest</option>
                        </select>
                    </div>
                </div>
            </div>
            @if ($blogs->count() > 0)
                <!-- Blog Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">


                    @foreach ($blogs as $blog)
                        <!-- Article 1 -->
                        <article
                            class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition article-card">
                            <a href="#" class="block">
                                @if ($blog->media_path)
                                    <div class="h-48 bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] relative">

                                        <img src="{{ asset($blog->media_path) }}" alt="{{ $blog->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-black/20"></div>
                                        <div class="absolute top-4 right-4">
                                            <span
                                                class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-[#8B5A2B]">
                                                <i class="far fa-clock mr-1"></i> {{ $blog->reading_time }}
                                            </span>
                                        </div>
                                        <div class="absolute bottom-4 left-4 text-white">
                                            <span class="text-sm bg-[#8B5A2B] px-3 py-1 rounded-full">
                                                <i class="fas fa-user mr-1"></i>{{ $blog->category }}
                                            </span>
                                        </div>
                                    </div>
                                @elseif($blog->media_type === 'video')
                                    <div class="h-48 bg-gradient-to-br from-[#8B5A2B] to-[#D98C5F] relative">
                                        <video src="{{ Storage::url($blog->media_path) }}"
                                            class="w-full h-full object-cover" muted></video>

                                        <div class="absolute inset-0 bg-black/20"></div>
                                        <div class="absolute top-4 right-4">
                                            <span
                                                class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-[#8B5A2B]">
                                                <i class="far fa-clock mr-1"></i>5 min read
                                            </span>
                                        </div>
                                        <div class="absolute bottom-4 left-4 text-white">
                                            <span class="text-sm bg-[#8B5A2B] px-3 py-1 rounded-full">
                                                <i class="fas fa-user mr-1"></i>People
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="h-48 bg-[#2A3B4C] flex items-center justify-center">
                                        <span class="text-4xl text-[#C4B9A6]">📝</span>
                                    </div>
                                @endif
                                <div class="p-6">
                                    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] mb-3">
                                        <i class="far fa-calendar"></i>
                                        <span>March 15, 2025</span>
                                        <span class="w-1 h-1 bg-[#C7B5A6] rounded-full"></span>
                                        <i class="far fa-eye"></i>
                                        <span>2.4K views</span>
                                    </div>
                                    <h2 class="text-xl font-bold mb-2 group-hover:text-[#8B5A2B] transition">
                                        {{ $blog->title }}
                                    </h2>
                                    <p class="text-[#6B6B6B] text-sm mb-4">
                                       {{ Str::limit(strip_tags($blog->description), 120) }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-[#F9F5F0] rounded-full flex items-center justify-center">
                                                <i class="fas fa-user-edit text-xs text-[#8B5A2B]"></i>
                                            </div>
                                            <span class="text-xs font-medium">By {{ $blog->author->name ?? 'Vumbi' }}</span>
                                        </div>
                                  <a href="{{ route('blog.show', $blog->id) }}" class="text-sunflare text-sm font-semibold hover:underline">
                                         Read More →
                                    </a>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach

                </div>
            @endif
            <!-- Pagination -->
            <div class="flex justify-center mt-12">
                <nav class="flex items-center gap-2">
                    <a href="#"
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-[#E5E0D9] hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-[#8B5A2B] text-white">1</a>
                    <a href="#"
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-[#E5E0D9] hover:bg-[#8B5A2B] hover:text-white transition">2</a>
                    <a href="#"
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-[#E5E0D9] hover:bg-[#8B5A2B] hover:text-white transition">3</a>
                    <span class="w-10 h-10 flex items-center justify-center">...</span>
                    <a href="#"
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-[#E5E0D9] hover:bg-[#8B5A2B] hover:text-white transition">8</a>
                    <a href="#"
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-[#E5E0D9] hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                </nav>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center">
                <div class="w-20 h-20 bg-[#F9F5F0] rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-envelope-open-text text-3xl text-[#8B5A2B]"></i>
                </div>
                <h2 class="text-3xl font-bold mb-3">Never Miss a Story</h2>
                <p class="text-[#6B6B6B] mb-8">Get Field Notes delivered to your inbox. Monthly stories from overlooked
                    places.</p>

                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <input type="email" placeholder="Your email address"
                        class="flex-1 px-4 py-3 border border-[#E5E0D9] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B5A2B]">
                    <button type="submit"
                        class="bg-[#8B5A2B] text-white px-6 py-3 rounded-xl hover:bg-[#6B421F] transition font-medium">
                        Subscribe
                    </button>
                </form>
                <p class="text-xs text-[#6B6B6B] mt-4">
                    We respect your privacy. Unsubscribe at any time.
                </p>
            </div>
        </div>
    </section>

    <!-- Popular Tags -->
    <section class="pb-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="border-t border-[#E5E0D9] pt-10">
                <h3 class="text-sm font-semibold text-[#6B6B6B] mb-4 text-center">POPULAR TOPICS</h3>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#Artisans</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#HiddenVillages</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#Traditions</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#Innovation</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#StreetFood</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#Music</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#Markets</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#WomenLeaders</a>
                    <a href="#"
                        class="bg-[#F9F5F0] px-4 py-2 rounded-full text-sm hover:bg-[#8B5A2B] hover:text-white transition">#Sustainability</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 dust-bg">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-4">Have a Story to Tell?</h2>
                    <p class="text-[#6B6B6B] mb-6">
                        Know someone remarkable from an overlooked place? We're always looking for stories to feature in
                        Field Notes.
                    </p>
                    <a href="/contact"
                        class="bg-[#8B5A2B] text-white px-6 py-3 rounded-full hover:bg-[#6B421F] transition font-medium inline-flex items-center gap-2">
                        Submit a Story <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#8B5A2B]">50+</div>
                        <div class="text-xs text-[#6B6B6B]">Stories</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#8B5A2B]">12</div>
                        <div class="text-xs text-[#6B6B6B]">Countries</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#8B5A2B]">15K</div>
                        <div class="text-xs text-[#6B6B6B]">Readers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#8B5A2B]">8</div>
                        <div class="text-xs text-[#6B6B6B]">Languages</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
