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
            <div x-show="results && !loading" class="space-y-12">

                <!-- CITY HEADER -->
                <div class="text-center">
                    <h1 class="text-5xl font-bold" x-text="results?.city ?? ''"></h1>
                    <p class="mt-4 text-gray-300 max-w-3xl mx-auto"
                       x-text="results?.description ?? ''"></p>
                </div>

                <!-- PLACES -->
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Things to Do</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="place in (results?.places ?? [])" :key="place.xid || place.id">
                            <div class="border border-zinc-800 rounded-xl p-4 bg-zinc-900">
                                <h3 class="font-semibold" x-text="place.name"></h3>
                                <p class="text-sm text-zinc-400" x-text="place.kinds"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- HOTELS -->
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Hotels</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="hotel in (results?.hotels ?? [])" :key="hotel.hotelId">
                            <div class="border border-zinc-800 rounded-xl p-4 bg-zinc-900">
                                <h3 class="font-semibold" x-text="hotel.hotelName"></h3>
                                <p class="text-amber-400" x-text="'KES ' + (hotel.priceAvg ?? 'N/A')"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FLIGHTS -->
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Flights</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="flight in (results?.flights?.data ?? [])" :key="flight.id">
                            <div class="border border-zinc-800 rounded-xl p-4 bg-zinc-900">
                                <h3 class="font-semibold">
                                    <span x-text="flight.origin"></span> →
                                    <span x-text="flight.destination"></span>
                                </h3>
                                <p class="text-amber-400" x-text="'KES ' + flight.price"></p>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- EMPTY STATE -->
            <div x-show="!loading && !results && !error"
                 class="text-center py-20 text-zinc-500">
                Search a destination to begin exploring.
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
function discoveryPage() {
    return {
        query: '',
        loading: false,
        error: null,

        // IMPORTANT: initialize as empty object (NOT null)
        results: null,

        async searchPlace() {

            if (!this.query) return;

            this.loading = true;
            this.error = null;
            this.results = null;

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

                if (!res.ok) {
                    throw new Error(data.message || 'Search failed');
                }

                if (data.status === 'building') {
                    this.error = data.message;
                    this.results = null;
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