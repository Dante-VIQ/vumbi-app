@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white">
    <!-- Hero -->
    <section class="bg-black py-20 px-6 text-center">
        <h1 class="text-4xl md:text-6xl font-bold">East Africa Safari Marketplace</h1>
        <p class="text-zinc-400 mt-4 text-lg">Find your perfect adventure in Kenya, Tanzania & beyond</p>
        <form method="GET" action="{{ route('tours.index') }}" class="max-w-2xl mx-auto mt-8">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search safaris, destinations..."
                       class="w-full bg-white/10 border border-white/20 rounded-2xl py-4 pl-6 pr-32 text-white placeholder-zinc-400">
                <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-green-600 hover:bg-green-500 px-6 py-2 rounded-xl font-medium">
                    Search
                </button>
            </div>
        </form>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <aside class="space-y-6" x-data="{ open: true }">
            <button @click="open = !open" class="lg:hidden w-full text-left text-lg font-semibold mb-4">
                Filters {{ '▾' }}
            </button>
            <div :class="open ? 'block' : 'hidden'" class="lg:block space-y-6">
                <!-- Location -->
                <div>
                    <h3 class="font-medium mb-2">Destination</h3>
                    <select name="location" form="filter-form"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-zinc-300">
                        <option value="">All</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <h3 class="font-medium mb-2">Type</h3>
                    <select name="type" form="filter-form"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-zinc-300">
                        <option value="">All</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Difficulty -->
                <div>
                    <h3 class="font-medium mb-2">Difficulty</h3>
                    <select name="difficulty" form="filter-form"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-zinc-300">
                        <option value="">All</option>
                        @foreach($difficulties as $diff)
                            <option value="{{ $diff }}" {{ request('difficulty') == $diff ? 'selected' : '' }}>{{ ucfirst($diff) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <h3 class="font-medium mb-2">Price Range</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" form="filter-form" placeholder="Min (KES)" value="{{ request('min_price') }}"
                               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-zinc-300">
                        <input type="number" name="max_price" form="filter-form" placeholder="Max (KES)" value="{{ request('max_price') }}"
                               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-zinc-300">
                    </div>
                </div>

                <button type="submit" form="filter-form"
                        class="w-full bg-green-600 hover:bg-green-500 px-4 py-2 rounded-xl font-medium">
                    Apply Filters
                </button>
            </div>
        </aside>

        <!-- Listings -->
        <div class="lg:col-span-3">
            <form id="filter-form" method="GET" action="{{ route('tours.index') }}" class="hidden"></form>

            <!-- Sort -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-zinc-400">{{ $packages->total() }} tours found</p>
                <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                        class="bg-zinc-800 border border-zinc-700 rounded-xl p-2 text-zinc-300 text-sm">
                    @foreach(['newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low'] as $val => $label)
                        <option value="{{ $val }}" {{ request('sort') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tour cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($packages as $pkg)
                <a href="{{ route('tours.show', $pkg) }}" class="group bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden hover:border-green-400/50 transition hover:-translate-y-1">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $pkg->image ?: 'https://picsum.photos/400/250?random='.$pkg->id }}')"></div>
                    <div class="p-5">
                        <span class="text-xs text-green-400 uppercase tracking-wide">{{ $pkg->type }}</span>
                        <h3 class="font-semibold text-lg mt-1">{{ $pkg->title }}</h3>
                        <p class="text-sm text-zinc-400 mt-1 line-clamp-2">{{ $pkg->location }}</p>
                        @if($pkg->duration_days)
                            <p class="text-sm text-zinc-500 mt-2">{{ $pkg->duration_days }} days / {{ $pkg->duration_nights }} nights</p>
                        @endif
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xl font-bold text-green-400">KSh {{ number_format($pkg->price) }}</span>
                            <span class="text-zinc-500 text-sm">View Details →</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">{{ $packages->links() }}</div>
        </div>
    </div>
</div>
@endsection