@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white overflow-x-hidden" x-data="discovery()">
    
    <!-- HERO SECTION -->
    <section class="relative h-screen flex items-center justify-center">
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
                Real stories, real costs, hidden gems, and unforgettable experiences.
            </p>

            <!-- Hero Search -->
            <form class="max-w-2xl mx-auto" @submit.prevent="searchPlace()">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 01-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input
                        type="text"
                        x-model="search"
                        placeholder="Where are you dreaming of? e.g. Nakuru, Santorini..."
                        class="w-full bg-white/10 backdrop-blur-xl border border-white/20 focus:border-amber-400 rounded-3xl py-7 pl-16 pr-8 text-xl placeholder-zinc-400 focus:outline-none transition-all"
                    >

                    <button
                        type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white text-zinc-900 hover:bg-amber-400 transition-colors px-10 py-4 rounded-3xl font-semibold flex items-center gap-2"
                    >
                        Explore
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- TRENDING DESTINATIONS -->
    <section class="py-20 bg-zinc-900">
        <!-- ... your trending section (unchanged) ... -->
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span class="uppercase tracking-[3px] text-amber-400 text-sm font-medium">Trending Now</span>
                    <h2 class="text-5xl font-bold tracking-tighter">Popular Escapes</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($trendingCities as $city)
                <div onclick="window.location.href='/discover/{{ strtolower($city->country->name) }}/{{ $city->slug }}'" 
                     class="group bg-zinc-800 rounded-3xl overflow-hidden cursor-pointer hover:scale-[1.03] transition-all duration-500">
                    <!-- ... your city card ... -->
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- DISCOVERY ENGINE -->
    <section class="py-24 bg-black">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-bold tracking-tighter mb-4">Deep Discovery Engine</h2>
                <p class="text-zinc-400 max-w-md mx-auto">Stories, hotels, tours, flights, and local offers — all in one place.</p>
            </div>

            <!-- Secondary Search -->
            <div class="max-w-xl mx-auto mb-16">
                <input 
                    type="text"
                    x-model="search"
                    @input.debounce.500ms="searchPlace()"
                    placeholder="Try: Nairobi, Maasai Mara, Diani Beach..."
                    class="w-full bg-zinc-900 border border-zinc-700 focus:border-amber-400 rounded-3xl px-8 py-6 text-lg placeholder-zinc-500 focus:outline-none"
                >
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
            <div x-show="!loading && totalResults > 0" class="space-y-20">

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

                <!-- Stories, Hotels, Attractions... (rest of your result templates) -->
                <!-- Keep them as they are -->

            </div>

            <!-- Empty State -->
            <div x-show="!loading && search.length > 2 && totalResults === 0" class="text-center py-32 text-zinc-500">
                <p class="text-2xl">No results found for "<span class="text-white" x-text="search"></span>"</p>
                <p class="mt-2">Try another destination or check spelling.</p>
            </div>

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

                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const json = await response.json();

                    this.results = json.data || {};
                    this.sourceHealth = json.meta?.source_health || {};
                    this.totalResults = json.meta?.total || Object.keys(json.data || {}).length || 0;

                } catch (error) {
                    console.error('Search failed:', error);
                    this.results = {};
                    this.totalResults = 0;
                } finally {
                    this.loading = false;
                }
            },

            truncate(text, length = 120) {
                if (!text) return '';
                return text.length > length ? text.substring(0, length) + '...' : text;
            }
        }
    }
</script>
@endpush