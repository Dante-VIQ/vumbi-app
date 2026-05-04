@extends('layouts.app')  {{-- adjust to your layout --}}

@section('content')
<div x-data="discovery()" class="bg-deep-earth min-h-screen">
    
    <!-- Hero Section -->
    <section class="pt-24 pb-16 border-b border-dust-mite">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-light leading-tight text-raw-linen mb-6">
                    Discover <span class="text-sunflare">Africa</span>
                </h1>
                <p class="text-xl text-[#C4B9A6] max-w-2xl mx-auto">
                    Search any destination and instantly get real stories, hotels, tours, flights, and exclusive offers.
                </p>
            </div>

            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mt-12">
                <form @submit.prevent="searchPlace" class="flex gap-3">
                    @csrf
                    <input type="text" 
                           x-model="search"
                           placeholder="Where are you exploring? (Maasai Mara, Diani, Nakuru, Lamu...)"
                           class="flex-1 bg-transparent border border-dust-mite focus:border-sunflare rounded-2xl px-8 py-6 text-lg text-raw-linen placeholder-[#8B7A6A] focus:outline-none">
                    <button type="submit"
                            class="bg-terracotta hover:bg-sunflare px-10 py-6 rounded-2xl text-raw-linen font-medium transition flex items-center gap-3 whitespace-nowrap">
                        <i class="fas fa-search"></i>
                        <span>Explore</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Loading State -->
    <div x-show="loading" class="container mx-auto px-6 py-20">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-night bg-opacity-30 mb-6">
                <i class="fas fa-spinner fa-spin text-4xl text-sunflare"></i>
            </div>
            <p class="text-[#C4B9A6] text-lg" x-text="'Discovering the best of ' + (placeName || 'Africa') + '...'"></p>
        </div>
    </div>

    <!-- Browse Mode (No Search Yet) -->
    <div x-show="!loading && !placeName">
        <div class="container mx-auto px-6 py-16">

            <!-- Popular Destinations -->
            <section class="mb-20">
                <h2 class="text-3xl font-light text-raw-linen mb-10 text-center">Popular Destinations</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($destinations as $dest)
                        <button @click="exploreDestination('{{ addslashes($dest->name) }}')"
                                class="group bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden transition-all">
                            @if($dest->media_path)
                                <img src="{{ asset($dest->media_path) }}" 
                                     alt="{{ $dest->name }}"
                                     class="w-full h-56 object-cover group-hover:scale-105 transition">
                            @endif
                            <div class="p-6">
                                <h3 class="text-2xl font-light text-raw-linen group-hover:text-sunflare">{{ $dest->name }}</h3>
                                <p class="text-[#C4B9A6] mt-1">{{ $dest->location ?? 'Kenya' }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </section>

            <!-- Culture & Stories -->
            <section>
                <h2 class="text-3xl font-light text-raw-linen mb-10 text-center">Culture & Stories</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($cultureEntries as $culture)
                        <a href="{{ route('culture.show', $culture->id) }}" 
                           class="group bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden transition-all">
                            @if($culture->media_path)
                                <img src="{{ asset($culture->media_path) }}" 
                                     alt="{{ $culture->title ?? $culture->name }}"
                                     class="w-full h-56 object-cover group-hover:scale-105 transition">
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-light text-raw-linen group-hover:text-sunflare">{{ $culture->title ?? $culture->name }}</h3>
                                <p class="text-[#C4B9A6] mt-2 line-clamp-3">{{ Str::limit(strip_tags($culture->description ?? $culture->detail), 110) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <!-- Search Results -->
    <div x-show="!loading && placeName">
        <div class="container mx-auto px-6 py-12">
            <div class="mb-10">
                <h2 class="text-3xl font-light text-raw-linen">
                    Discoveries in <span class="text-sunflare" x-text="placeName"></span>
                </h2>
                <p class="text-[#C4B9A6] mt-1" x-text="totalResults + ' results found'"></p>
            </div>

            <!-- Hotels Section -->
            <template x-if="results.hotels && results.hotels.length > 0">
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-hotel"></i> Hotels & Lodges
                        <span x-show="sourceHealth.hotels === 'curated'" class="text-xs bg-terracotta text-white px-3 py-1 rounded-full ml-2">Curated</span>
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="hotel in results.hotels" :key="hotel.name">
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                                <img :src="hotel.image" :alt="hotel.name" class="w-full h-52 object-cover" x-show="hotel.image">
                                <div class="p-6">
                                    <h4 class="font-medium text-raw-linen" x-text="hotel.name"></h4>
                                    <p class="text-sm text-[#C4B9A6]" x-text="hotel.price || 'Best rates'"></p>
                                    <a :href="hotel.url || '#'" target="_blank" rel="nofollow sponsored"
                                       class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium hover:bg-raw-linen">
                                        View & Book →
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </template>

            <!-- Tours Section -->
            <template x-if="results.tours && results.tours.length > 0">
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-compass"></i> Tours & Experiences
                        <span x-show="sourceHealth.tours === 'curated'" class="text-xs bg-terracotta text-white px-3 py-1 rounded-full ml-2">Curated</span>
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="tour in results.tours" :key="tour.name">
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                                <div class="p-6">
                                    <h4 class="font-medium text-raw-linen" x-text="tour.name || 'Guided Experience'"></h4>
                                    <p class="text-sm text-[#C4B9A6]" x-text="tour.price || ''"></p>
                                    <a :href="tour.url || '#'" target="_blank" rel="nofollow sponsored"
                                       class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium hover:bg-raw-linen">
                                        Book Experience →
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </template>

            <!-- Flights Section -->
            <template x-if="results.flights && results.flights.length > 0">
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-plane"></i> Flight Deals
                        <span x-show="sourceHealth.flights === 'curated'" class="text-xs bg-terracotta text-white px-3 py-1 rounded-full ml-2">Curated</span>
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="flight in results.flights" :key="flight.airline">
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                                <div class="p-6">
                                    <h4 class="font-medium text-raw-linen" x-text="flight.airline || 'Flight Deal'"></h4>
                                    <p class="text-sm text-[#C4B9A6]" x-text="(flight.from || '') + ' → ' + (flight.to || placeName)"></p>
                                    <p class="text-lg font-medium text-sunflare mt-1" x-text="flight.price || 'Best price'"></p>
                                    <a :href="flight.link || '#'" target="_blank" rel="nofollow sponsored"
                                       class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium hover:bg-raw-linen">
                                        Book Flight →
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </template>

            <!-- Awin Offers Section -->
            <template x-if="results.awin_offers && results.awin_offers.length > 0">
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-tag"></i> Special Offers
                        <span x-show="sourceHealth.awin_offers === 'curated'" class="text-xs bg-terracotta text-white px-3 py-1 rounded-full ml-2">Curated</span>
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <template x-for="offer in results.awin_offers" :key="offer.title">
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl p-6 hover:border-sunflare transition">
                                <span class="text-xs uppercase tracking-widest text-terracotta">Awin</span>
                                <h4 class="font-medium text-raw-linen mt-2" x-text="offer.title"></h4>
                                <p class="text-sm text-[#C4B9A6] mt-1" x-text="offer.description"></p>
                                <a :href="offer.link" target="_blank" rel="nofollow sponsored"
                                   class="mt-5 inline-block bg-sunflare text-deep-earth px-6 py-3 rounded-2xl text-sm font-medium">
                                    Get Offer →
                                </a>
                            </div>
                        </template>
                    </div>
                </section>
            </template>

            <!-- Stories Section -->
            <template x-if="results.stories && results.stories.length > 0">
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-book-open"></i> Related Stories
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="story in results.stories" :key="story.id">
                            <a :href="'/blog/' + story.id" 
                               class="group bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden transition-all">
                                <img :src="story.media_path ? '/storage/' + story.media_path : ''" 
                                     :alt="story.title"
                                     class="w-full h-52 object-cover" x-show="story.media_path">
                                <div class="p-6">
                                    <h4 class="text-xl font-light text-raw-linen group-hover:text-sunflare" x-text="story.title"></h4>
                                    <p class="text-sm text-[#C4B9A6] mt-2 line-clamp-3" x-text="truncate(story.description || story.excerpt, 110)"></p>
                                </div>
                            </a>
                        </template>
                    </div>
                </section>
            </template>

            <!-- Place Info (optional) -->
            <template x-if="results.place_info">
                <section class="mb-16 bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl p-8">
                    <h3 class="text-2xl text-sunflare mb-4">About <span x-text="placeName"></span></h3>
                    <div class="prose prose-invert text-[#C4B9A6]" x-html="results.place_info"></div>
                </section>
            </template>

            <!-- Attractions -->
            <template x-if="results.attractions && results.attractions.length > 0">
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6">Must‑See Attractions</h3>
                    <div class="grid md:grid-cols-2 gap-6" x-html="results.attractions"></div> 
                    {{-- Note: adjust if PlaceDiscoveryService returns HTML or array --}}
                </section>
            </template>

            <!-- No Results at all -->
            <template x-if="totalResults === 0">
                <div class="text-center py-20">
                    <div class="text-6xl mb-6">🔍</div>
                    <h3 class="text-2xl font-light text-raw-linen mb-3">No results found</h3>
                    <p class="text-[#C4B9A6]">Try searching for a different destination or browse our curated picks above.</p>
                    <button @click="resetSearch" class="mt-8 text-sunflare hover:text-white transition">
                        ← Back to Discover Africa
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function discovery() {
        return {
            search: '',
            placeName: '',
            loading: false,
            results: {
                stories: [],
                hotels: [],
                tours: [],
                flights: [],
                awin_offers: [],
                place_info: null,
                attractions: [],
            },
            sourceHealth: {},
            totalResults: 0,

            exploreDestination(name) {
                this.search = name;
                this.searchPlace();
            },

            async searchPlace() {
                if (this.search.length < 2) return;
                this.loading = true;
                this.placeName = this.search.charAt(0).toUpperCase() + this.search.slice(1);

                try {
                    const response = await fetch('/discover/search', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ search: this.search })
                    });

                    if (!response.ok) throw new Error('Network error');
                    
                    const json = await response.json();
                    
                    // Assign results from the response structure
                    this.results = json.data;
                    this.sourceHealth = json.meta.source_health;
                    this.totalResults = json.meta.total;
                    
                } catch (error) {
                    console.error('Search failed:', error);
                    // Graceful fallback: show empty state
                    this.results = {};
                    this.totalResults = 0;
                } finally {
                    this.loading = false;
                }
            },

            resetSearch() {
                this.placeName = '';
                this.search = '';
                this.results = {};
                this.totalResults = 0;
            },

            truncate(text, length) {
                if (!text) return '';
                return text.length > length ? text.substring(0, length) + '...' : text;
            }
        }
    }
</script>
@endpush