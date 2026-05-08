@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white" x-data="discoveryPage()">

    <!-- HERO -->
    <section class="relative h-screen flex items-center justify-center">
        <div class="absolute inset-0 bg-[url('https://picsum.photos/id/1015/2000/1200')] bg-cover bg-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/70 to-zinc-950"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
            <h1 class="text-6xl md:text-8xl font-bold tracking-tighter mb-6">
                Discover Before You Travel
            </h1>
            <p class="text-xl text-zinc-300 mb-10">Real insights • Real deals • Real intelligence</p>

            <form class="max-w-2xl mx-auto" @submit.prevent="searchPlace()">
                <div class="relative">
                    <input type="text"
                           x-model="query"
                           placeholder="Where are you dreaming of? (e.g. Nairobi, Mombasa, Nakuru...)"
                           class="w-full bg-white/10 backdrop-blur-xl border border-white/20 focus:border-amber-400 rounded-3xl py-6 pl-8 pr-40 text-lg placeholder-zinc-400 focus:outline-none transition">
                    <button type="submit"
                            :disabled="loading"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-amber-400 hover:bg-amber-300 disabled:bg-zinc-600 text-black px-10 py-3.5 rounded-2xl font-semibold transition">
                        Explore
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="py-12 bg-black">
        <div class="max-w-7xl mx-auto px-6">

            <!-- LOADING -->
            <div x-show="loading" class="text-center py-24">
                <div class="animate-spin w-12 h-12 border-4 border-amber-400 border-t-transparent rounded-full mx-auto mb-6"></div>
                <p class="text-amber-400 text-xl">Gathering the best information for you...</p>
            </div>

            <!-- ERROR -->
            <div x-show="error" class="bg-red-900/30 border border-red-500 text-red-400 p-8 rounded-3xl text-center">
                <p x-text="error"></p>
            </div>

            <!-- RESULTS -->
            <div x-show="!loading && result?.city" class="space-y-16">

                <!-- CITY HEADER -->
                <div class="text-center">
                    <h1 class="text-5xl md:text-6xl font-bold mb-4" x-text="result.city"></h1>
                    <p class="text-lg text-zinc-300 max-w-3xl mx-auto" x-text="result.description"></p>
                </div>

                <!-- BUILDING INDICATOR -->
                <div x-show="building" class="bg-amber-400/10 border border-amber-400/30 rounded-3xl p-8 text-center">
                    <p class="text-amber-400 text-xl font-medium" x-text="buildingMessage"></p>
                    <p class="text-sm text-zinc-400 mt-2">This may take 15–45 seconds...</p>
                </div>

                <!-- TABS -->
                <div class="border-b border-zinc-800 mb-8">
                    <div class="flex flex-wrap gap-2">
                        <button @click="activeTab = 'overview'" 
                                :class="{ 'bg-zinc-900 border-amber-400 text-white' : activeTab === 'overview' }"
                                class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Overview
                        </button>
                        <button @click="activeTab = 'places'" 
                                :class="{ 'bg-zinc-900 border-amber-400 text-white' : activeTab === 'places' }"
                                class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Things To Do
                        </button>
                        <button @click="activeTab = 'hotels'" 
                                :class="{ 'bg-zinc-900 border-amber-400 text-white' : activeTab === 'hotels' }"
                                class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Where To Stay
                        </button>
                        <button @click="activeTab = 'flights'" 
                                :class="{ 'bg-zinc-900 border-amber-400 text-white' : activeTab === 'flights' }"
                                class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Flights & Deals
                        </button>
                        <button @click="activeTab = 'practical'" 
                                :class="{ 'bg-zinc-900 border-amber-400 text-white' : activeTab === 'practical' }"
                                class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Practical Info
                        </button>
                    </div>
                </div>

                <!-- OVERVIEW TAB -->
                <div x-show="activeTab === 'overview'" class="grid md:grid-cols-2 gap-12">
                    <div class="space-y-10">
                        <div x-show="result.cultural_info?.content">
                            <h3 class="text-2xl font-semibold mb-4">Culture & People</h3>
                            <p class="text-zinc-300 leading-relaxed" x-text="result.cultural_info.content"></p>
                        </div>
                        <div x-show="result.educational_info?.content">
                            <h3 class="text-2xl font-semibold mb-4">History & Facts</h3>
                            <p class="text-zinc-300 leading-relaxed" x-text="result.educational_info.content"></p>
                        </div>
                    </div>
                    <div class="space-y-10">
                        <div x-show="result.best_time_to_visit?.content">
                            <h3 class="text-2xl font-semibold mb-4">Best Time to Visit</h3>
                            <p class="text-zinc-300" x-text="result.best_time_to_visit.content"></p>
                        </div>
                        <div x-show="result.visa_info?.content">
                            <h3 class="text-2xl font-semibold mb-4">Visa & Entry Requirements</h3>
                            <p class="text-zinc-300" x-text="result.visa_info.content"></p>
                        </div>
                    </div>
                </div>

                <!-- PLACES TAB -->
                <div x-show="activeTab === 'places'">
                    <h2 class="text-3xl font-semibold mb-6">Things To Do</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="place in result.places" :key="place.id || place.xid">
                            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 hover:border-amber-400/50 transition">
                                <h3 class="font-semibold text-lg mb-2" x-text="place.properties?.name || place.name"></h3>
                                <p class="text-sm text-zinc-400 line-clamp-3" 
                                   x-text="place.properties?.descr || place.description || 'No description available'"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- HOTELS TAB -->
                <div x-show="activeTab === 'hotels'">
                    <h2 class="text-3xl font-semibold mb-6">Recommended Hotels</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="hotel in result.hotels" :key="hotel.hotelId || hotel.id">
                            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                                <h3 class="font-semibold" x-text="hotel.hotelName || hotel.name || 'Hotel'"></h3>
                                <p class="text-amber-400 text-xl font-medium mt-2" 
                                   x-text="'KES ' + (hotel.priceAvg || hotel.price || 'N/A')"></p>
                                <p class="text-xs text-zinc-500 mt-1" x-text="hotel.address"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FLIGHTS & DEALS TAB -->
                <div x-show="activeTab === 'flights'">
                    <h2 class="text-3xl font-semibold mb-6">Flights & Travel Deals</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="item in [...(result.flights || []), ...(result.affiliate_deals || [])]" 
                                  :key="item.id">
                            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold" x-text="item.from + ' → ' + item.to"></h3>
                                        <p class="text-amber-400 text-2xl font-medium mt-1" x-text="item.price"></p>
                                    </div>
                                    <span class="text-xs bg-zinc-800 px-3 py-1 rounded-full" 
                                          x-text="item.source || 'Deal'"></span>
                                </div>
                                <p class="text-sm text-zinc-400 mt-3" x-text="item.airline || item.description"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- PRACTICAL INFO TAB -->
                <div x-show="activeTab === 'practical'">
                    <h2 class="text-3xl font-semibold mb-8">Practical Information</h2>
                    <div class="grid md:grid-cols-2 gap-10">
                        <div x-show="result.nearby_destinations?.length">
                            <h3 class="text-2xl font-semibold mb-4">Nearby Destinations</h3>
                            <div class="space-y-4">
                                <template x-for="dest in result.nearby_destinations" :key="dest.name">
                                    <div class="flex justify-between bg-zinc-900 p-4 rounded-2xl">
                                        <span x-text="dest.name"></span>
                                        <span class="text-zinc-400" x-text="dest.distance_km + ' km'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="result.weather?.temperature">
                            <h3 class="text-2xl font-semibold mb-4">Current Weather</h3>
                            <div class="bg-zinc-900 p-8 rounded-3xl text-center">
                                <p class="text-6xl font-light" x-text="result.weather.temperature + '°C'"></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- EMPTY STATE -->
            <div x-show="!loading && !result?.city && !error" 
                 class="text-center py-32 text-zinc-500">
                <p class="text-2xl">Search a destination above to get started</p>
            </div>

        </div>
    </section>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('discoveryPage', () => ({
        query: '',
        loading: false,
        error: null,
        building: false,
        buildingMessage: '',
        activeTab: 'overview',

        result: {
            city: '',
            description: '',
            places: [],
            hotels: [],
            flights: [],
            affiliate_deals: [],
            cultural_info: {},
            educational_info: {},
            best_time_to_visit: {},
            visa_info: {},
            nearby_destinations: [],
            weather: {}
        },

        async searchPlace() {
            if (!this.query?.trim()) return;

            this.loading = true;
            this.error = null;
            this.building = false;

            try {
                const res = await fetch('/discover/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ q: this.query })
                });

                const data = await res.json();

                if (!res.ok) throw new Error(data.message || 'Search failed');

                this.result = data.result || this.result;

                if (data.status === 'building') {
                    this.building = true;
                    this.buildingMessage = data.message || 'Building your personalized travel guide...';
                    this.startPolling(this.query);
                }

            } catch (err) {
                console.error(err);
                this.error = err.message || 'Failed to fetch results';
            } finally {
                this.loading = false;
            }
        },

        async startPolling(query) {
            let attempts = 0;
            const interval = setInterval(async () => {
                attempts++;
                if (attempts > 15) {
                    clearInterval(interval);
                    this.building = false;
                    return;
                }

                try {
                    const res = await fetch(`/discover/search?q=${encodeURIComponent(query)}`);
                    const data = await res.json();

                    if (data.status === 'ready') {
                        this.result = data.result;
                        this.building = false;
                        clearInterval(interval);
                    }
                } catch (e) {
                    console.error(e);
                }
            }, 4500);
        }
    }));
});
</script>
@endsection