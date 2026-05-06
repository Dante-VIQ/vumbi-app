@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white overflow-x-hidden" x-data="discovery()">
    
    <!-- HERO SECTION -->
    <section class="relative h-screen flex items-center justify-center">
        <div class="absolute inset-0 bg-[url('https://picsum.photos/id/1015/2000/1200')] bg-cover bg-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/70 to-zinc-950"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
            <h1 class="text-7xl md:text-8xl font-bold tracking-tighter leading-none mb-4">
                Discover Before<br>You Travel
            </h1>

            <form class="max-w-2xl mx-auto mt-10" @submit.prevent="searchPlace()">
                <div class="relative">
                    <input
                        type="text"
                        x-model="search"
                        placeholder="Where are you dreaming of? e.g. Nakuru..."
                        class="w-full bg-white/10 backdrop-blur-xl border border-white/20 focus:border-amber-400 rounded-3xl py-7 pl-16 pr-8 text-xl placeholder-zinc-400 focus:outline-none"
                    >
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white text-black px-10 py-4 rounded-3xl font-semibold">
                        Explore
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- DISCOVERY ENGINE SECTION -->
    <section class="py-24 bg-black">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-5xl font-bold">Deep Discovery Engine</h2>
            </div>

            <div class="max-w-xl mx-auto mb-12">
                <input 
                    type="text"
                    x-model="search"
                    @input.debounce.600ms="searchPlace()"
                    placeholder="Search any city..."
                    class="w-full bg-zinc-900 border border-zinc-700 rounded-3xl px-8 py-6 text-lg"
                >
            </div>

            <!-- Loading -->
            <div x-show="loading" class="text-center py-20">
                <span class="text-amber-400">Loading rich data...</span>
            </div>

            <!-- Results -->
            <div x-show="!loading && totalResults > 0">
                <pre x-text="JSON.stringify(results, null, 2)" class="bg-zinc-900 p-6 rounded-2xl text-sm overflow-auto"></pre>
            </div>

            <!-- Empty / Building State -->
            <div x-show="!loading && search.length > 2 && totalResults === 0" class="text-center py-20 text-zinc-400">
                <p x-text="results.place_info?.description || 'No data yet...'"></p>
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
        results: {},
        totalResults: 0,

        async searchPlace() {
            if (!this.search || this.search.length < 2) return;

            this.loading = true;

            try {
                const res = await fetch('/discover/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ search: this.search })
                });

                const json = await res.json();

                console.log('API Response:', json);   // ← Helpful for debugging

                if (json.status === 'ready' && json.redirect) {
                    window.location.href = json.redirect;
                    return;
                }

                this.results = json.data || json;
                this.totalResults = json.meta?.total || 1;

            } catch (err) {
                console.error('Search Error:', err);
                this.results = { error: err.message };
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush