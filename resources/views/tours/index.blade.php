@extends('layouts.app')

@section('title', 'East Africa Safari & Tour Marketplace | Find Your Perfect Adventure')
@section('description', 'Find and book your perfect adventure in Kenya, Tanzania & beyond. Compare tours, safaris, and cultural experiences across East Africa.')
@section('keywords', 'East Africa safari, Kenya tours, Tanzania safari, book African safari, African tour operator, adventure travel Africa')

@push('schema')
@php
    $pageSchemas = [];

    $pageSchemas[] = [
        "@context" => "https://schema.org",
        "@type" => "ItemList",
        "name" => "East Africa Safari & Tour Packages",
        "description" => "Browse our curated list of authentic African safaris and cultural experiences.",
        "url" => url()->current(),
        "numberOfItems" => $packages->count(),
        "itemListElement" => $packages->map(function ($pkg, $index) {
            return [
                "@type" => "ListItem",
                "position" => $index + 1,
                "url" => route('tours.show', $pkg),
                "name" => $pkg->title ?? $pkg->name,
                "image" => $pkg->featured_image ? asset('storage/' . $pkg->featured_image) : ($pkg->image ?: asset('images/og-default.jpg')),
                "description" => \Illuminate\Support\Str::limit($pkg->short_description ?? $pkg->description, 120)
            ];
        })->toArray()
    ];
@endphp
@endpush

@section('content')
    <div class="min-h-screen bg-slate-950 text-slate-100">
        
        {{-- ================= HERO SEARCH SECTION ================= --}}
        <section class="relative bg-slate-900 py-16 lg:py-24 px-4 sm:px-6 lg:px-8 text-center border-b border-slate-800 overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#8B5A2B_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-[#8B5A2B]/20 text-amber-400 border border-[#8B5A2B]/40 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Curated Expedition Marketplace</span>
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight">
                    East Africa Safari Marketplace
                </h1>
                <p class="text-slate-400 mt-4 text-base sm:text-lg max-w-2xl mx-auto">
                    Compare safari itineraries, custom game drives, and regional expeditions across Kenya & East Africa.
                </p>

                <form method="GET" action="{{ route('tours.index') }}" class="max-w-2xl mx-auto mt-8">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search safaris, national parks, destinations..."
                            class="w-full bg-slate-800/90 border border-slate-700/80 rounded-2xl py-4 pl-6 pr-32 text-white placeholder-slate-400 focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B] transition shadow-inner">
                        <button type="submit"
                            class="absolute right-2 bg-[#8B5A2B] hover:bg-[#6e4620] text-white px-6 py-2.5 rounded-xl font-semibold transition shadow-md flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        {{-- ================= MAIN CONTENT GRID ================= --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            {{-- Filter Form (Hidden submission container) --}}
            <form id="filter-form" method="GET" action="{{ route('tours.index') }}" class="hidden">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>

            {{-- ================= FILTERS SIDEBAR ================= --}}
            <aside x-data="{ openMobile: false }" class="lg:col-span-1">
                <div class="lg:sticky lg:top-8 space-y-4">
                    
                    <!-- Mobile Filter Toggle Button -->
                    <button @click="openMobile = !openMobile"
                        class="lg:hidden w-full flex items-center justify-between text-base font-semibold bg-slate-900 border border-slate-800 rounded-xl p-4 text-white shadow-sm"
                        :aria-expanded="openMobile">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter Tours
                        </span>
                        <svg :class="openMobile ? 'rotate-180' : ''" class="w-5 h-5 transform transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Filter Container -->
                    <div x-show="openMobile" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2" 
                        class="lg:block bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-5" 
                        x-cloak>
                        
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <h3 class="font-bold text-white text-base">Filter Expeditions</h3>
                            @if(request()->anyFilled(['location', 'type', 'difficulty', 'min_price', 'max_price', 'search']))
                                <a href="{{ route('tours.index') }}" class="text-xs text-amber-400 hover:text-amber-300 font-medium">Reset All</a>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <!-- Destination Filter -->
                            <div>
                                <label class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">Destination</label>
                                <select name="location" form="filter-form"
                                    class="w-full bg-slate-800 border border-slate-700/80 rounded-xl p-3 text-slate-200 text-sm focus:border-amber-500 focus:outline-none">
                                    <option value="">All Destinations</option>
                                    @foreach ($locations as $loc)
                                        <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tour Type Filter -->
                            <div>
                                <label class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">Tour Type</label>
                                <select name="type" form="filter-form"
                                    class="w-full bg-slate-800 border border-slate-700/80 rounded-xl p-3 text-slate-200 text-sm focus:border-amber-500 focus:outline-none">
                                    <option value="">All Types</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Difficulty Filter -->
                            <div>
                                <label class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">Difficulty</label>
                                <select name="difficulty" form="filter-form"
                                    class="w-full bg-slate-800 border border-slate-700/80 rounded-xl p-3 text-slate-200 text-sm focus:border-amber-500 focus:outline-none">
                                    <option value="">All Levels</option>
                                    @foreach ($difficulties as $diff)
                                        <option value="{{ $diff }}" {{ request('difficulty') == $diff ? 'selected' : '' }}>{{ ucfirst($diff) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range Filter -->
                            <div>
                                <label class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">Price Range (USD)</label>
                                <div class="flex gap-2">
                                    <input type="number" name="min_price" form="filter-form" placeholder="Min ($)"
                                        value="{{ request('min_price') }}"
                                        class="w-1/2 bg-slate-800 border border-slate-700/80 rounded-xl p-3 text-slate-200 text-sm placeholder-slate-500 focus:border-amber-500 focus:outline-none">
                                    <input type="number" name="max_price" form="filter-form" placeholder="Max ($)"
                                        value="{{ request('max_price') }}"
                                        class="w-1/2 bg-slate-800 border border-slate-700/80 rounded-xl p-3 text-slate-200 text-sm placeholder-slate-500 focus:border-amber-500 focus:outline-none">
                                </div>
                            </div>

                            <!-- Submit Filter Action -->
                            <button type="submit" form="filter-form"
                                class="w-full bg-[#8B5A2B] hover:bg-[#6e4620] text-white py-3 rounded-xl font-bold text-sm transition shadow-md mt-2">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ================= LISTINGS & CONTENT ================= --}}
            <div class="lg:col-span-3 space-y-8">
                
                <!-- Toolbar & Sorting -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900 border border-slate-800 rounded-2xl p-4">
                    <p class="text-slate-400 text-sm font-medium">
                        Showing <span class="text-white font-bold">{{ $packages->total() }}</span> available safari package{{ $packages->total() === 1 ? '' : 's' }}
                    </p>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <label for="sort" class="text-xs text-slate-400 whitespace-nowrap">Sort by:</label>
                        <select id="sort" name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                            class="w-full sm:w-auto bg-slate-800 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 text-sm focus:border-amber-500 focus:outline-none">
                            @foreach (['newest' => 'Newest First', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low'] as $val => $label)
                                <option value="{{ $val }}" {{ request('sort') == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tour Package Cards Grid -->
                @if($packages->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($packages as $pkg)
                            @php
                                $pkgTitle = $pkg->title ?? $pkg->name;
                                $pkgImg = $pkg->featured_image ? asset('storage/' . $pkg->featured_image) : ($pkg->image ?: 'https://picsum.photos/400/250?random=' . $pkg->id);
                            @endphp
                            <a href="{{ route('tours.show', $pkg) }}"
                                class="group bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-[#8B5A2B]/60 transition duration-300 hover:-translate-y-1 flex flex-col justify-between shadow-lg">
                                <div>
                                    <div class="h-48 bg-cover bg-center relative" style="background-image: url('{{ $pkgImg }}')">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                                        <span class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-sm text-amber-400 text-xs font-bold px-3 py-1 rounded-full border border-amber-500/20 uppercase tracking-wide">
                                            {{ $pkg->type ?? 'Safari' }}
                                        </span>
                                    </div>
                                    <div class="p-5 space-y-2">
                                        <h3 class="font-bold text-lg text-white group-hover:text-amber-400 transition line-clamp-1">
                                            {{ $pkgTitle }}
                                        </h3>
                                        <p class="text-xs text-slate-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-[#8B5A2B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span class="line-clamp-1">{{ $pkg->location }}</span>
                                        </p>
                                        @if ($pkg->duration_days)
                                            <p class="text-xs text-slate-500">
                                                {{ $pkg->duration_days }} Days / {{ $pkg->duration_nights ?? ($pkg->duration_days - 1) }} Nights
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-5 pt-0 border-t border-slate-800/60 mt-4 flex justify-between items-center">
                                    <div>
                                        <span class="text-xs text-slate-500 block">Starting from</span>
                                        <span class="text-xl font-extrabold text-amber-400">${{ number_format($pkg->price) }}</span>
                                    </div>
                                    <span class="text-xs font-bold text-slate-300 group-hover:text-white flex items-center gap-1 transition">
                                        Details <span>→</span>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $packages->links() }}
                    </div>
                @else
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center max-w-lg mx-auto my-8">
                        <div class="w-12 h-12 bg-slate-800 text-amber-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">No Safari Packages Found</h3>
                        <p class="text-slate-400 text-sm mb-6">We couldn't find any packages matching your search criteria.</p>
                        <a href="{{ route('tours.index') }}" class="inline-block bg-[#8B5A2B] hover:bg-[#6e4620] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition">
                            Clear Filters & View All
                        </a>
                    </div>
                @endif

                {{-- Livewire Content Bridges --}}
                <div class="space-y-8 pt-8 border-t border-slate-800">
                    <livewire:culture-page :location="request('location')" :limit="$cultureLimit" :teaser="true" />
                    <livewire:destination-page :location="request('location')" :limit="$cultureLimit" :teaser="true" />
                </div>

            </div>
        </div>
    </div>
@endsection