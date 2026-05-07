@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white overflow-x-hidden"
     x-data="discoveryPage()">

    <!-- HERO -->
    <section class="relative h-screen flex items-center justify-center">
        <div class="absolute inset-0 bg-[url('https://picsum.photos/id/1015/2000/1200')] bg-cover bg-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/70 to-zinc-950"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
            <h1 class="text-6xl md:text-8xl font-bold tracking-tighter mb-6">
                Discover Before You Travel
            </h1>

            <form class="max-w-2xl mx-auto mt-10" @submit.prevent="searchPlace()">
                <div class="relative">
                    <input type="text"
                           x-model="query"
                           @keydown.enter.prevent="searchPlace"
                           placeholder="Where are you dreaming of? e.g. Nakuru..."
                           class="w-full bg-white/10 backdrop-blur-xl border border-white/20 focus:border-amber-400 rounded-3xl py-6 pl-6 pr-40 text-lg placeholder-zinc-400 focus:outline-none">

                    <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-white text-black px-8 py-3 rounded-2xl font-semibold">
                        Explore
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- ENGINE -->
    <section class="py-20 bg-black">
        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold">Discovery Engine</h2>
            </div>

            <!-- SEARCH INPUT -->
            <div class="max-w-xl mx-auto mb-10">
                <input type="text"
                       x-model="query"
                       @keydown.enter.prevent="searchPlace"
                       placeholder="Search any city..."
                       class="w-full bg-zinc-900 border border-zinc-700 rounded-2xl px-6 py-5 text-lg">
            </div>

            <!-- LOADING -->
            <div x-show="loading" class="text-center py-20 text-amber-400">
                Searching the world...
            </div>

            <!-- ERROR -->
            <div x-show="error" class="text-center py-10 text-red-400" x-text="error"></div>

            <!-- RESULTS WRAPPER -->
            <div x-show="result && !loading" class="space-y-12">

                <!-- CITY HEADER -->
                <div class="text-center">
                    <h1 class="text-5xl font-bold" x-text="result.city ?? ''"></h1>
                    <p class="mt-4 text-gray-300 max-w-3xl mx-auto"
                       x-text="result.description ?? ''"></p>
                </div>

                <!-- BUILDING STATUS -->
                <div x-show="building" class="text-center py-6 bg-amber-400/10 border border-amber-400/30 rounded-2xl">
                    <p class="text-amber-400 font-semibold" x-text="buildingMessage"></p>
                </div>

                <!-- PLACES -->
                <div x-show="result.places?.length">
                    <h2 class="text-2xl font-semibold mb-4">Things to Do</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="place in result.places" :key="place.id ?? place.xid">
                            <div class="border border-zinc-800 rounded-xl p-4 bg-zinc-900">
                                <h3 class="font-semibold" x-text="place.name"></h3>
                                <p class="text-sm text-zinc-400" x-text="place.kinds"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- HOTELS -->
                <div x-show="result.hotels?.length">
                    <h2 class="text-2xl font-semibold mb-4">Hotels</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="hotel in result.hotels" :key="hotel.hotelId ?? hotel.id">
                            <div class="border border-zinc-800 rounded-xl p-4 bg-zinc-900">
                                <h3 class="font-semibold" x-text="hotel.hotelName || 'Hotel'"></h3>
                                <p class="text-amber-400" x-text="'KES ' + (hotel.priceAvg ?? 'N/A')"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FLIGHTS -->
                <div x-show="result.flights?.length">
                    <h2 class="text-2xl font-semibold mb-4">Flights</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="flight in result.flights" :key="flight.id">
                            <div class="border border-zinc-800 rounded-xl p-4 bg-zinc-900">
                                <h3 class="font-semibold">
                                    <span x-text="flight.from"></span> →
                                    <span x-text="flight.to"></span>
                                </h3>
                                <p class="text-amber-400" x-text="flight.price"></p>
                                <p class="text-xs text-zinc-500" x-text="flight.airline || flight.source"></p>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- EMPTY STATE -->
            <div x-show="!loading && !result && !error"
                 class="text-center py-20 text-zinc-500">
                Search a destination to begin exploring.
            </div>

        </div>
    </section>
</div>

<script>
// Define the Alpine component directly in the page to avoid loading order issues
document.addEventListener('alpine:init', () => {
    Alpine.data('discoveryPage', () => ({
        query: '',
        loading: false,
        error: null,

        // SAFE DEFAULT — not null!
        result: {
            city: '',
            description: '',
            places: [],
            hotels: [],
            flights: [],
            data_quality: {},
            meta: {}
        },

        building: false,
        buildingMessage: '',

        async searchPlace() {
            if (!this.query) return;

            this.loading = true;
            this.error = null;
            // Keep the previous result while loading
            this.building = false;
            this.buildingMessage = '';

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

                if (!res.ok) {
                    throw new Error(data.message || 'Search failed');
                }

                // Merge with safe defaults
                this.result = {
                    city: data.result?.city ?? '',
                    description: data.result?.description ?? '',
                    places: data.result?.places ?? [],
                    hotels: data.result?.hotels ?? [],
                    flights: data.result?.flights ?? [],
                    data_quality: data.result?.data_quality ?? {},
                    meta: data.result?.meta ?? {},
                };

                if (data.status === 'building') {
                    this.building = true;
                    this.buildingMessage = data.message || 'We are preparing your travel guide...';
                }

            } catch (e) {
                console.error(e);
                this.error = e.message || "Search failed. Please try again.";
                // Keep default result (no city)
                this.result.city = '';
            }

            this.loading = false;
        }
    }));
});
</script>
@endsection