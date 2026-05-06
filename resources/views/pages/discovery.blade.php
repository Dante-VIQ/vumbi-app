@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white overflow-x-hidden" x-data="discovery()">
    <!-- HERO SECTION - Luxurious & Immersive -->
    <section class="relative h-screen flex items-center justify-center">
        <!-- Background -->
        <div class="absolute inset-0 bg-[url('https://picsum.photos/id/1015/2000/1200')] bg-cover bg-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/70 to-zinc-950"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-6 py-2 rounded-full text-sm mb-6 border border-white/20">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                Live from 142 destinations
            </div>

            <h1 class="text-7xl md:text-8xl font-bold tracking-tighter leading-none mb-4">
                Discover Before<br>You Travel
            </h1>
            <p class="text-2xl text-zinc-300 max-w-2xl mx-auto mb-10">
                Real stories, real costs, hidden gems, and unforgettable experiences — all before you book a ticket.
            </p>

            <!-- Search Bar -->
  <form class="max-w-2xl mx-auto" @submit.prevent="searchPlace()">
    @csrf

    <div class="relative group">

        <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 01-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <input
            type="text"
            id="heroSearch"
            x-model="search"
            placeholder="Where are you dreaming of? e.g. Nakuru, Santorini, Kyoto..."
            class="w-full bg-white/10 backdrop-blur-xl border border-white/20 focus:border-amber-400 rounded-3xl py-7 pl-16 pr-8 text-xl placeholder-zinc-400 focus:outline-none transition-all"
        >

        <button
            type="submit"
            class="absolute right-3 top-1/2 -translate-y-1/2 bg-white text-zinc-900 hover:bg-amber-400 transition-colors px-10 py-4 rounded-3xl font-semibold flex items-center gap-2"
        >
            <span>Explore</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7" />
            </svg>
        </button>

    </div>
</form>

            <div class="mt-8 text-sm text-zinc-400 flex items-center justify-center gap-8">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-400">●</span> Real-time prices
                </div>
                <div>Local insights</div>
                <div>Hidden experiences</div>
            </div>
        </div>

        <!-- Scroll prompt -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center text-xs tracking-widest">
            <span class="mb-2">SCROLL TO EXPLORE</span>
            <div class="w-px h-12 bg-gradient-to-b from-transparent via-white/50 to-transparent"></div>
        </div>
    </section>

    <!-- TRENDING DESTINATIONS -->
    <section class="py-20 bg-zinc-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span class="uppercase tracking-[3px] text-amber-400 text-sm font-medium">Trending Now</span>
                    <h2 class="text-5xl font-bold tracking-tighter">Popular Escapes</h2>
                </div>
                <a href="#" class="text-amber-400 hover:text-amber-300 flex items-center gap-2 group">
                    View all destinations 
                    <span class="transition-transform group-hover:translate-x-1">→</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($trendingCities as $city)
                <div onclick="window.location.href='/discover/{{ strtolower($city->country->name) }}/{{ $city->slug }}'" 
                     class="group bg-zinc-800 rounded-3xl overflow-hidden cursor-pointer hover:scale-[1.03] transition-all duration-500 border border-white/5 hover:border-amber-400/30">
                    <div class="h-72 bg-cover bg-center relative" 
                         style="background-image: url('https://picsum.photos/id/{{ 101 + $loop->index }}/800/600')">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                        <div class="absolute top-6 right-6 bg-black/70 text-xs px-4 py-2 rounded-2xl backdrop-blur-md">
                            Trending
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-3xl font-semibold tracking-tight">{{ $city->name }}</h3>
                        <p class="text-zinc-400">{{ $city->country->name }}</p>
                        
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm">
                                <span class="text-emerald-400">★</span> 4.9
                            </div>
                            <button class="bg-white/10 hover:bg-white/20 transition-colors px-6 py-3 rounded-2xl text-sm">
                                Explore Now
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- DISCOVERY ENGINE (Alpine.js Powered) -->
    <section class="py-24 bg-black" x-data="discovery()">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold tracking-tighter mb-4">Deep Discovery Engine</h2>
                <p class="text-zinc-400 max-w-md mx-auto">Stories, hotels, tours, flights, and local offers — all in one place.</p>
            </div>

            <!-- Search Input (Secondary) -->
            <div class="max-w-xl mx-auto mb-16">
                <div class="relative">
                    <input 
                        type="text"
                        x-model="search"
                        @input.debounce.500ms="searchPlace()"
                        placeholder="Try: Nairobi, Maasai Mara, Diani Beach..."
                        class="w-full bg-zinc-900 border border-zinc-700 focus:border-amber-400 rounded-3xl px-8 py-6 text-lg placeholder-zinc-500 focus:outline-none"
                    >
                </div>
            </div>

            <!-- Loading -->
            <div x-show="loading" class="flex justify-center py-20">
                <div class="flex items-center gap-4 text-amber-400">
                    <svg class="animate-spin h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-xl">Uncovering hidden gems...</span>
                </div>
            </div>

            <!-- Results -->
            <div x-show="!loading && search.length > 2 && totalResults === 0" class="space-y-20">

                <!-- Place Info -->
                <div x-show="results.place_info" class="bg-gradient-to-br from-zinc-900 to-black border border-amber-400/20 rounded-3xl p-12">
                    <div class="flex flex-col md:flex-row gap-12 items-center">
                        <div class="flex-1">
                            <h3 class="text-5xl font-bold" x-text="placeName"></h3>
                            <p class="text-2xl text-zinc-400 mt-2" x-text="results.place_info?.description || ''"></p>
                        </div>
                        <div class="text-right">
                            <div class="text-emerald-400 text-6xl font-light" x-text="results.place_info?.avg_cost || '$$$'"></div>
                            <div class="uppercase text-xs tracking-widest">Average Daily Cost</div>
                        </div>
                    </div>
                </div>

                <!-- Stories -->
                <div x-show="results.stories && results.stories.length > 0">
                    <h4 class="text-3xl font-semibold mb-8 flex items-center gap-3">
                        <span>Traveler Stories</span>
                        <span class="text-xs bg-white/10 px-3 py-1 rounded-full">Real experiences</span>
                    </h4>
                    <div class="grid md:grid-cols-3 gap-8">
                        <template x-for="(story, i) in results.stories" :key="i">
                            <div class="bg-zinc-900 rounded-3xl overflow-hidden">
                                <img :src="story.image || 'https://picsum.photos/600/400'" class="w-full h-56 object-cover">
                                <div class="p-6">
                                    <h5 class="font-semibold" x-text="story.title"></h5>
                                    <p class="text-sm text-zinc-400 line-clamp-3 mt-2" x-text="truncate(story.excerpt, 140)"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Hotels -->
                <div x-show="results.hotels && results.hotels.length > 0">
                    <h4 class="text-3xl font-semibold mb-8">Luxury Stays</h4>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <template x-for="(hotel, i) in results.hotels" :key="i">
                            <div class="bg-zinc-900 rounded-3xl p-6 hover:ring-2 hover:ring-amber-400/30 transition-all">
                                <div class="text-emerald-400 font-mono text-sm" x-text="hotel.price_range"></div>
                                <h5 class="font-semibold mt-2" x-text="hotel.name"></h5>
                                <p class="text-zinc-400 text-sm" x-text="hotel.location"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Tours & Attractions -->
                <div x-show="results.attractions && results.attractions.length > 0" class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h4 class="text-3xl font-semibold mb-8">Must-Do Experiences</h4>
                        <div class="space-y-6">
                            <template x-for="(attr, i) in results.attractions" :key="i">
                                <div class="flex gap-6 bg-zinc-900/70 rounded-3xl p-6">
                                    <div class="w-24 h-24 bg-zinc-800 rounded-2xl flex-shrink-0"></div>
                                    <div>
                                        <h5 class="font-semibold" x-text="attr.name"></h5>
                                        <p class="text-zinc-400 text-sm" x-text="truncate(attr.description, 110)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Empty State -->
            <div x-show="!loading && totalResults === 0 && search.length > 2" class="text-center py-32 text-zinc-500">
                <p class="text-2xl">No results yet.</p>
                <p class="mt-2">Try a different destination or check back later.</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 bg-gradient-to-br from-amber-400 to-yellow-500 text-zinc-950">
        <div class="max-w-4xl mx-auto text-center px-6">
            <h2 class="text-6xl font-bold tracking-tighter">Ready for your next adventure?</h2>
            <p class="mt-6 text-xl">Join thousands of travelers discovering smarter.</p>
            <button onclick="document.getElementById('heroSearch').focus()" 
                    class="mt-10 bg-zinc-900 hover:bg-black text-white text-lg px-12 py-6 rounded-3xl transition-all">
                Start Exploring →
            </button>
        </div>
    </section>
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
    if (!this.search || this.search.length < 2) return;
                
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
                    
                    this.results = json.data || {};
                    this.sourceHealth = json.meta?.source_health || {};
                    this.totalResults = json.meta?.total || 0;
                    
                } catch (error) {
                    console.error('Search failed:', error);
                    this.results = {};
                    this.totalResults = 0;
                } finally {
                    this.loading = false;
                }
            },

            resetSearch() {
                this.search = '';
                this.placeName = '';
                this.results = {};
                this.totalResults = 0;
            },

            truncate(text, length = 120) {
                if (!text) return '';
                return text.length > length ? text.substring(0, length) + '...' : text;
            }
        }
    }

    // Bonus: Quick search from hero (vanilla)
    document.addEventListener('DOMContentLoaded', () => {
        const heroInput = document.getElementById('heroSearch');
        if (heroInput) {
            heroInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && window.Alpine) {
                    // Trigger Alpine search
                    const alpineRoot = document.querySelector('[x-data]');
                    if (alpineRoot && alpineRoot.__x) {
                        alpineRoot.__x.$data.search = this.value;
                        alpineRoot.__x.$data.searchPlace();
                    }
                }
            });
        }
    });
</script>
@endpush