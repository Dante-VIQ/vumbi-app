@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white overflow-x-hidden"
     x-data="discoveryPage()">

    <!-- =========================
        HERO SECTION
    ========================== -->
    <section class="relative h-screen flex items-center justify-center">

        <div class="absolute inset-0 bg-[url('https://picsum.photos/id/1015/2000/1200')] bg-cover bg-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/70 to-zinc-950"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">

            <h1 class="text-7xl md:text-8xl font-bold tracking-tighter leading-none mb-4">
                Discover Before<br>You Travel
            </h1>

            <form class="max-w-2xl mx-auto mt-10"
                  @submit.prevent="searchPlace()">

                <div class="relative">

                    <input type="text"
                           x-model="query"
                           placeholder="Where are you dreaming of? e.g. Nakuru..."
                           class="w-full bg-white/10 backdrop-blur-xl border border-white/20 focus:border-amber-400 rounded-3xl py-7 pl-16 pr-8 text-xl placeholder-zinc-400 focus:outline-none">

                    <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 bg-white text-black px-10 py-4 rounded-3xl font-semibold">
                        Explore
                    </button>

                </div>
            </form>

        </div>
    </section>

    <!-- =========================
        DISCOVERY ENGINE SECTION
    ========================== -->
    <section class="py-24 bg-black">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Title -->
            <div class="text-center mb-12">
                <h2 class="text-5xl font-bold">Deep Discovery Engine</h2>
            </div>

            <!-- Search Bar -->
            <div class="max-w-xl mx-auto mb-10">
                <input type="text"
                       x-model="query"
                       @keydown.enter="searchPlace()"
                       @input.debounce.500ms="searchPlace()"
                       placeholder="Search any city..."
                       class="w-full bg-zinc-900 border border-zinc-700 rounded-3xl px-8 py-6 text-lg focus:outline-none focus:border-amber-400">
            </div>

            <!-- =========================
                LOADING STATE
            ========================== -->
            <div x-show="loading"
                 class="text-center py-20 text-amber-400">
                Searching the world...
            </div>

            <!-- =========================
                BUILDING STATE
            ========================== -->
            <div x-show="status === 'building' && !loading"
                 class="text-center py-20">

                <div class="text-amber-400 text-xl mb-4"
                     x-text="message"></div>

                <p class="text-zinc-400">
                    We are preparing a full travel guide. This may take a few seconds...
                </p>
            </div>

            <!-- =========================
                ERROR STATE
            ========================== -->
            <div x-show="error"
                 class="text-center py-10 text-red-400"
                 x-text="error"></div>

            <!-- =========================
                RESULTS HEADER
            ========================== -->
            <div x-show="results && status === 'ready'" class="space-y-12">

                <!-- CITY INTRO -->
                <div class="text-center">
                    <h1 class="text-4xl font-bold"
                        x-text="results?.city ?? ''"></h1>

                    <p class="mt-4 text-gray-300 max-w-3xl mx-auto"
                       x-text="results?.description ?? ''"></p>
                </div>

                <!-- =========================
                    ATTRACTIONS
                ========================== -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6">Things to do</h2>

                    <div class="grid md:grid-cols-3 gap-4">

                        <template x-for="place in (results?.places ?? [])"
                                  :key="place.xid">

                            <div class="border border-zinc-800 bg-zinc-900 p-4 rounded-xl">

                                <h3 class="font-semibold"
                                    x-text="place.name ?? ''"></h3>

                                <p class="text-sm text-zinc-400 mt-2"
                                   x-text="place.kinds ?? ''"></p>

                            </div>

                        </template>

                    </div>
                </div>

                <!-- =========================
                    HOTELS
                ========================== -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6">Hotels</h2>

                    <div class="grid md:grid-cols-3 gap-4">

                        <template x-for="hotel in (results?.hotels ?? [])"
                                  :key="hotel.hotelId">

                            <div class="border border-zinc-800 bg-zinc-900 p-4 rounded-xl">

                                <h3 class="font-semibold"
                                    x-text="hotel.hotelName ?? ''"></h3>

                                <p class="text-amber-400 mt-2"
                                   x-text="'KES ' + (hotel.priceAvg ?? 'N/A')"></p>

                            </div>

                        </template>

                    </div>
                </div>

                <!-- =========================
                    FLIGHTS
                ========================== -->
                <div>
                    <h2 class="text-2xl font-semibold mb-6">Flights</h2>

                    <div class="grid md:grid-cols-3 gap-4">

                        <template x-for="flight in (results?.flights?.data ?? [])"
                                  :key="flight.id">

                            <div class="border border-zinc-800 bg-zinc-900 p-4 rounded-xl">

                                <h3 class="font-semibold">
                                    <span x-text="flight.origin ?? ''"></span>
                                    →
                                    <span x-text="flight.destination ?? ''"></span>
                                </h3>

                                <p class="text-amber-400 mt-2"
                                   x-text="'KES ' + (flight.price ?? 'N/A')"></p>

                            </div>

                        </template>

                    </div>
                </div>

            </div>

            <!-- =========================
                EMPTY STATE
            ========================== -->
            <div x-show="!loading && !results && !error && status !== 'building'"
                 class="text-center py-20 text-zinc-500">

                Start by searching a destination above.

            </div>

        </div>
    </section>
</div>
@endsection

<!-- =========================
    ALPINE JS CONTROLLER
========================== -->
@push('scripts')
<script>
function discoveryPage() {
    return {

        query: '',
        loading: false,
        results: null,
        error: null,
        status: null,
        message: null,

        async searchPlace() {

            if (!this.query || this.query.length < 2) return;

            this.loading = true;
            this.error = null;
            this.results = null;
            this.status = null;
            this.message = null;

            try {
                const res = await fetch('/discover/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        q: this.query
                    })
                });

                const data = await res.json();

                this.status = data.status || 'ready';
                this.message = data.message || null;

                if (data.status === 'building') {
                    this.loading = false;
                    return;
                }

                this.results = data.results ?? null;

            } catch (e) {
                console.error(e);
                this.error = "Search failed. Please try again.";
            }

            this.loading = false;
        }
    }
}
</script>
@endpush