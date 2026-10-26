@extends('layouts.app')
 
@section('title', 'Discover Africa – Destinations, Safaris & Travel Deals by Region')
@section('description', 'Search African destinations, safaris and cultural trips — Maasai Mara, Zanzibar, Diani Beach and more. Compare hotels, safaris and flights with real local insight.')

@push('schema')
    @php
    $pageSchemas = [];

    $pageSchemas[] = [
        "@context" => "https://schema.org",
        "@type" => "CollectionPage",
        "name" => "African Travel Experiences | Vumbi Ventures",
        "description" => "Explore authentic African safaris, cultural immersions, and hidden gems.",
        "url" => url()->current(),
        "about" => [
            "@type" => "Thing",
            "name" => "African Travel Experiences"
        ],
    ];
    @endphp
@endpush

@section('content')
    <div class="min-h-screen bg-zinc-950 text-white" x-data="discoveryPage()">

        <!-- ============================================= -->
        <!-- 1. HERO                                       -->
        <!-- ============================================= -->
        <section class="relative h-screen flex items-center justify-center">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/maasai-mara.jpg') }}')">
                <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/70 to-zinc-950"></div>
            </div>

            <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
                <h1 class="text-6xl md:text-8xl font-bold tracking-tighter mb-6">
                    Discover Before You Travel
                </h1>
                <p class="text-xl text-zinc-300 mb-10">Real insights • Real deals • Real intelligence</p>

                <form class="max-w-2xl mx-auto" @submit.prevent="searchPlace()">
                    <div class="relative">
                        <input type="text" x-model="query"
                            placeholder="Where are you dreaming of? (e.g. Nairobi, Mombasa, Nakuru...)"
                            class="w-full bg-white/10 backdrop-blur-xl border border-white/20 focus:border-amber-400 rounded-3xl py-6 pl-8 pr-40 text-lg placeholder-zinc-400 focus:outline-none transition">
                        <button type="submit" :disabled="loading"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-amber-400 hover:bg-amber-300 disabled:bg-zinc-600 text-black px-10 py-3.5 rounded-2xl font-semibold transition">
                            Explore
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- ============================================= -->
        <!-- 2. BEFORE SEARCH: Trending & Categories       -->
        <!-- ============================================= -->
        {{-- No-results / error fallback: never leave the visitor at a dead end. --}}
        <div x-show="error && !loading" x-cloak class="max-w-2xl mx-auto px-6 pb-20 -mt-20 relative z-20 text-center">
            <h2 class="text-3xl font-bold mb-3">We couldn't find a guide for that yet</h2>
            <p class="text-zinc-300 mb-8">Try a nearby or more popular destination, or tell us where you want to go and we'll put together a custom itinerary for you.</p>
            <div class="flex flex-wrap justify-center gap-3 mb-8">
                <button @click="query = 'Maasai Mara'; searchPlace()" class="px-5 py-2.5 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">Maasai Mara</button>
                <button @click="query = 'Nairobi'; searchPlace()" class="px-5 py-2.5 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">Nairobi</button>
                <button @click="query = 'Mombasa'; searchPlace()" class="px-5 py-2.5 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">Mombasa</button>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('contact') }}"
                    data-gtag-event="no_results_quote_click"
                    class="px-8 py-3.5 bg-amber-400 hover:bg-amber-300 text-black rounded-2xl font-semibold transition">Get a custom quote</a>
                <a :href="'https://wa.me/{{ config('services.whatsapp.number') }}?text=' + encodeURIComponent('Hi Vumbi Ventures, I searched for ' + query + ' and would like help planning a trip.')"
                    target="_blank" rel="noopener"
                    data-gtag-event="whatsapp_click" data-gtag-params='{"placement":"discover_no_results"}'
                    class="px-8 py-3.5 bg-green-600 hover:bg-green-500 text-white rounded-2xl font-semibold transition">Chat on WhatsApp</a>
            </div>
        </div>

        <div x-show="!result?.city && !loading && !error" class="max-w-7xl mx-auto px-6 pb-20 -mt-20 relative z-20">
            <!-- Trending Cities -->
            @if (isset($trendingCities) && count($trendingCities) > 0)
                <section class="mb-16">
                    <h2 class="text-3xl font-bold mb-8 text-center">Trending Destinations</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($trendingCities as $city)
                            <button @click="query = '{{ $city->name }}'; searchPlace()"
                                class="group relative bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden hover:border-amber-400/60 transition transform hover:-translate-y-1 shadow-lg">
                                <img src="{{ $city->image_url ?? asset('images/maasai-mara.jpg') }}"
                                    alt="{{ $city->name }}"
                                    class="w-full h-40 object-cover group-hover:scale-105 transition">
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg">{{ $city->name }}</h3>
                                    <p class="text-sm text-zinc-400 mt-1">{{ $city->country ?? 'Kenya' }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Category Pills -->
            <section class="text-center mb-12">
                <h2 class="text-2xl font-semibold mb-6">Find your perfect trip</h2>
                <div class="flex flex-wrap justify-center gap-4">
                    <button @click="query = 'Mombasa'; searchPlace()"
                        class="px-6 py-3 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">🏖️ Beach</button>
                    <button @click="query = 'Maasai Mara'; searchPlace()"
                        class="px-6 py-3 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">🦁 Safari</button>
                    <button @click="query = 'Nairobi'; searchPlace()"
                        class="px-6 py-3 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">🌆 City</button>
                    <button @click="query = 'Nakuru'; searchPlace()"
                        class="px-6 py-3 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">🦩 Lake</button>
                    <button @click="query = 'Lamu'; searchPlace()"
                        class="px-6 py-3 bg-zinc-900 border border-zinc-800 rounded-full hover:border-amber-400 transition">🏝️ Island</button>
                </div>
            </section>

            <div class="text-center py-12 text-zinc-500">
                <p class="text-2xl">Search a destination above to get started</p>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- 2b. TRIPS: who's travelling + filters         --}}
        {{-- ============================================= --}}
        @php
            $personas = \App\Support\Persona::all();
            $toggle = function (string $key, $value) use ($filters) {
                $next = (string) ($filters[$key] ?? '') === (string) $value ? null : $value;

                return request()->fullUrlWithQuery([$key => $next]) . '#trips';
            };
            $pill = 'px-4 py-2 rounded-full border text-sm transition ';
            $pillOn = 'bg-amber-400 border-amber-400 text-black font-semibold';
            $pillOff = 'bg-zinc-900 border-zinc-800 hover:border-amber-400 text-zinc-200';
        @endphp

        <section id="trips" class="max-w-7xl mx-auto px-6 pb-16 -mt-20 relative z-20 scroll-mt-24">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2">Who's travelling?</h2>
                <p class="text-zinc-400">Pick one and we'll put the trips that suit you first.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                @foreach ($personas as $key => $info)
                    <a href="{{ request()->fullUrlWithQuery(['persona' => $key]) }}#trips"
                        data-gtag-event="persona_selected" data-gtag-params='{"persona":"{{ $key }}","placement":"discover"}'
                        class="block rounded-2xl border p-5 transition {{ $persona === $key ? 'border-amber-400 bg-amber-400/10' : 'border-zinc-800 bg-zinc-900 hover:border-amber-400/60' }}">
                        <div class="font-semibold text-lg">{{ $info['label'] }}</div>
                        <div class="text-sm text-zinc-400 mt-1">{{ $info['tagline'] }}</div>
                    </a>
                @endforeach
            </div>
            @if ($persona)
                <p class="text-center text-sm text-zinc-500 mb-8">
                    Showing trips for <span class="text-zinc-300">{{ \App\Support\Persona::label($persona) }}</span>.
                    <a href="{{ request()->fullUrlWithQuery(['persona' => 'all']) }}#trips" class="text-amber-400 hover:text-amber-300">Show everyone's trips</a>
                </p>
            @else
                <div class="mb-8"></div>
            @endif

            {{-- Filters --}}
            <div class="space-y-3 mb-8">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs uppercase tracking-wider text-zinc-500 w-20">Interest</span>
                    @foreach (\App\Support\Persona::interests() as $key => $label)
                        <a href="{{ $toggle('interest', $key) }}" data-gtag-event="trip_filter" data-gtag-params='{"filter":"interest","value":"{{ $key }}"}'
                            class="{{ $pill }}{{ ($filters['interest'] ?? null) === $key ? $pillOn : $pillOff }}">{{ $label }}</a>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs uppercase tracking-wider text-zinc-500 w-20">Budget</span>
                    @foreach (\App\Support\Persona::budgetBands() as $key => $label)
                        <a href="{{ $toggle('budget', $key) }}" data-gtag-event="trip_filter" data-gtag-params='{"filter":"budget","value":"{{ $key }}"}'
                            class="{{ $pill }}{{ ($filters['budget'] ?? null) === $key ? $pillOn : $pillOff }}">{{ $label }}</a>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs uppercase tracking-wider text-zinc-500 w-20">Length</span>
                    @foreach (\App\Support\Persona::durations() as $key => $label)
                        <a href="{{ $toggle('days', $key) }}" data-gtag-event="trip_filter" data-gtag-params='{"filter":"days","value":"{{ $key }}"}'
                            class="{{ $pill }}{{ ($filters['days'] ?? null) === $key ? $pillOn : $pillOff }}">{{ $label }}</a>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs uppercase tracking-wider text-zinc-500 w-20">Party</span>
                    @foreach (\App\Support\Persona::partySizes() as $key => $label)
                        <a href="{{ $toggle('group', $key) }}" data-gtag-event="trip_filter" data-gtag-params='{"filter":"group","value":"{{ $key }}"}'
                            class="{{ $pill }}{{ (string) ($filters['group'] ?? '') === (string) $key ? $pillOn : $pillOff }}">{{ $label }}</a>
                    @endforeach
                </div>
                @if ($filters !== [])
                    <div>
                        <a href="{{ route('discover.index') }}{{ $persona ? '?persona=' . $persona : '' }}#trips" class="text-sm text-amber-400 hover:text-amber-300">Clear filters</a>
                    </div>
                @endif
            </div>

            @if ($noMatches)
                <div class="rounded-2xl border border-amber-400/30 bg-amber-400/10 p-6 text-center mb-8">
                    <p class="text-lg font-semibold">No trips match all of those filters yet.</p>
                    <p class="text-zinc-300 text-sm mt-1">Here are our most popular trips, or tell us what you want and we'll build it for you.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center mt-4">
                        <a href="{{ route('contact') }}" data-gtag-event="no_results_quote_click" data-gtag-params='{"placement":"trip_filters"}'
                            class="px-6 py-3 bg-amber-400 hover:bg-amber-300 text-black rounded-2xl font-semibold transition">Get a custom quote</a>
                        <a href="https://wa.me/{{ config('services.whatsapp.number') }}?text={{ rawurlencode('Hi Vumbi Ventures, I could not find the right trip on your site and would like help planning one.') }}"
                            target="_blank" rel="noopener"
                            data-gtag-event="whatsapp_click" data-gtag-params='{"placement":"trip_filters_no_match"}'
                            class="px-6 py-3 bg-green-600 hover:bg-green-500 text-white rounded-2xl font-semibold transition">Chat on WhatsApp</a>
                    </div>
                </div>
            @endif

            @if (isset($featuredPackages) && count($featuredPackages) > 0)
                <h2 class="text-3xl font-bold mb-8 text-center">{{ $noMatches ? 'Popular Trips' : 'Trips for You' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($featuredPackages as $pkg)
                        <a href="{{ route('tours.show', $pkg) }}"
                            class="group bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden hover:border-green-400/50 transition hover:-translate-y-1 shadow-lg">
                            <div class="h-48 bg-cover bg-center"
                                style="background-image: url('{{ $pkg->image ?: asset('images/maasai-mara.jpg') }}')">
                            </div>
                            <div class="p-5">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="text-xs text-green-400 uppercase tracking-wide">{{ ucfirst($pkg->type) }}</span>
                                    @if ($pkg->duration_days)
                                        <span class="text-xs text-zinc-400">{{ $pkg->duration_days }} {{ \Illuminate\Support\Str::plural('day', $pkg->duration_days) }}</span>
                                    @endif
                                    @foreach (array_slice($pkg->audiences ?? [], 0, 2) as $aud)
                                        <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded-full border {{ $persona === $aud ? 'border-amber-400 text-amber-300' : 'border-zinc-700 text-zinc-400' }}">{{ \App\Support\Persona::label($aud) }}</span>
                                    @endforeach
                                </div>
                                <h3 class="font-semibold text-lg">{{ $pkg->title }}</h3>
                                <p class="text-sm text-zinc-400 mt-1">{{ \Illuminate\Support\Str::words(strip_tags((string) $pkg->description), 25) }}</p>
                                <div class="flex items-center justify-between mt-4">
                                    <span class="text-2xl font-bold text-green-400">$ {{ number_format($pkg->price) }}</span>
                                    <button onclick="event.preventDefault(); dispatchBookingEvent({{ $pkg->id }})"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-xl font-medium transition">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- ============================================= -->
        <!-- 3. AFTER SEARCH: Results & Tabs               -->
        <!-- ============================================= -->
        <section class="py-12 bg-black" x-show="!loading && result?.city && !error">
            <div class="max-w-7xl mx-auto px-6">

                <!-- CITY HEADER -->
                <div class="text-center mb-16">
                    <h1 class="text-5xl md:text-6xl font-bold mb-4" x-text="result.city"></h1>
                    <p class="text-lg text-zinc-300 max-w-3xl mx-auto" x-text="result.description"></p>
                </div>

                <!-- BUILDING INDICATOR -->
                <div x-show="building" class="bg-amber-400/10 border border-amber-400/30 rounded-3xl p-8 text-center mb-12">
                    <p class="text-amber-400 text-xl font-medium" x-text="buildingMessage"></p>
                    <p class="text-sm text-zinc-400 mt-2">This may take 15–45 seconds...</p>
                </div>

                <!-- TABS -->
                <div class="border-b border-zinc-800 mb-12">
                    <div class="flex flex-wrap gap-2">
                        <button @click="activeTab = 'overview'"
                            :class="{ 'bg-zinc-900 border-amber-400 text-white': activeTab === 'overview' }"
                            class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Overview
                        </button>
                        <button @click="activeTab = 'places'"
                            :class="{ 'bg-zinc-900 border-amber-400 text-white': activeTab === 'places' }"
                            class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Things To Do
                        </button>
                        <button @click="activeTab = 'hotels'"
                            :class="{ 'bg-zinc-900 border-amber-400 text-white': activeTab === 'hotels' }"
                            class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Where To Stay
                        </button>
                        <button @click="activeTab = 'flights'"
                            :class="{ 'bg-zinc-900 border-amber-400 text-white': activeTab === 'flights' }"
                            class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Flights & Deals
                        </button>
                        <button @click="activeTab = 'practical'"
                            :class="{ 'bg-zinc-900 border-amber-400 text-white': activeTab === 'practical' }"
                            class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition">
                            Practical Info
                        </button>
                        <button x-show="result.partner_packages?.length" @click="activeTab = 'book'"
                            :class="{ 'bg-zinc-900 border-amber-400 text-white': activeTab === 'book' }"
                            class="px-7 py-3 rounded-2xl border border-transparent hover:border-zinc-700 transition bg-green-900/30 border-green-700/30">
                            📅 Book a Trip
                        </button>
                    </div>
                </div>

                <!-- OVERVIEW TAB -->
                <div x-show="activeTab === 'overview'" class="grid md:grid-cols-2 gap-12">
                    <div class="space-y-10">
                        <div x-show="result.cultural_info?.content">
                            <h3 class="text-2xl font-semibold mb-4">🎭 Culture & People</h3>
                            <div class="text-zinc-300 leading-relaxed" x-text="result.cultural_info.content"></div>
                        </div>
                        <div x-show="result.educational_info?.content">
                            <h3 class="text-2xl font-semibold mb-4">📚 History & Facts</h3>
                            <div class="text-zinc-300 leading-relaxed" x-text="result.educational_info.content"></div>
                        </div>
                    </div>
                    <div class="space-y-10">
                        <div x-show="result.best_time_to_visit?.content">
                            <h3 class="text-2xl font-semibold mb-4">☀️ Best Time to Visit</h3>
                            <p class="text-zinc-300" x-text="result.best_time_to_visit.content"></p>
                        </div>
                        <div x-show="result.visa_info?.content">
                            <h3 class="text-2xl font-semibold mb-4">🛂 Visa & Entry Requirements</h3>
                            <p class="text-zinc-300" x-text="result.visa_info.content"></p>
                        </div>
                    </div>
                </div>

                <!-- PLACES TAB -->
                <div x-show="activeTab === 'places'">
                    <h2 class="text-3xl font-semibold mb-8">Things To Do</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="place in result.places" :key="place.id || place.xid">
                            <div class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-6 hover:border-amber-400/50 transition hover:-translate-y-1">
                                <h3 class="font-semibold text-lg mb-2" x-text="place.properties?.name || place.name"></h3>
                                <p class="text-sm text-zinc-400 line-clamp-3"
                                    x-text="place.properties?.descr || place.description || 'No description available'">
                                </p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- HOTELS TAB -->
                <div x-show="activeTab === 'hotels'">
                    <h2 class="text-3xl font-semibold mb-8">Recommended Hotels</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="hotel in result.hotels" :key="hotel.hotelId || hotel.id">
                            <div class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-6 hover:border-amber-400/50 transition hover:-translate-y-1">
                                <h3 class="font-semibold text-lg" x-text="hotel.hotelName || hotel.name || 'Hotel'"></h3>
                                <p class="text-amber-400 text-xl font-medium mt-2"
                                    x-text="'KES ' + (hotel.priceAvg || hotel.price || 'N/A')"></p>
                                <p class="text-xs text-zinc-500 mt-1" x-text="hotel.address"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FLIGHTS & DEALS TAB -->
                <div x-show="activeTab === 'flights'">
                    <h2 class="text-3xl font-semibold mb-8">Flights & Travel Deals</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="item in [...(result.flights || []), ...(result.affiliate_deals || [])]" :key="item.id">
                            <div class="group bg-zinc-900 border border-zinc-800 rounded-2xl p-6 hover:border-amber-400/50 transition hover:-translate-y-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold" x-text="item.from + ' → ' + item.to"></h3>
                                        <p class="text-amber-400 text-2xl font-medium mt-1" x-text="item.price"></p>
                                    </div>
                                    <span class="text-xs bg-zinc-800 px-3 py-1 rounded-full" x-text="item.source || 'Deal'"></span>
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
                            <h3 class="text-2xl font-semibold mb-4">📍 Nearby Destinations</h3>
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
                            <h3 class="text-2xl font-semibold mb-4">🌤️ Current Weather</h3>
                            <div class="bg-zinc-900 p-8 rounded-3xl text-center">
                                <p class="text-6xl font-light" x-text="result.weather.temperature + '°C'"></p>
                                <p class="text-zinc-400 mt-2"
                                    x-text="result.weather.windspeed ? result.weather.windspeed+' km/h' : ''"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOOK A TRIP TAB -->
                <div x-show="activeTab === 'book' && result.partner_packages?.length" class="space-y-6">
                    <h2 class="text-3xl font-semibold mb-8">Book a Trip to <span x-text="result.city"></span></h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="pkg in result.partner_packages" :key="pkg.id">
                            <div class="group bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden hover:border-green-400/50 transition hover:-translate-y-1 shadow-lg">
                                <div class="h-48 bg-cover bg-center"
                                    :style="'background-image: url(' + (pkg.image || '{{ asset('images/maasai-mara.jpg') }}') + ')'">
                                </div>
                                <div class="p-5">
                                    <h3 class="font-semibold text-lg" x-text="pkg.title"></h3>
                                    <p class="text-sm text-zinc-400 mt-1 line-clamp-2" x-text="pkg.description"></p>
                                    <div class="flex items-center justify-between mt-4">
                                        <span class="text-2xl font-bold text-green-400"
                                            x-text="'KSh ' + Number(pkg.price).toLocaleString()"></span>
                                        <button @click="$dispatch('open-booking', { packageId: pkg.id })"
                                            class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-xl font-medium transition">
                                            Book Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </section>

        <!-- ============================================= -->
        <!-- 4. GLOBAL BOOK A TRIP FLOATING BUTTON          -->
        <!-- ============================================= -->
        <div x-data="{ openGeneralBooking: false, search: '', packages: [] }">
            <button @click="openGeneralBooking = true"
                class="fixed bottom-6 right-6 bg-green-600 hover:bg-green-500 text-white p-4 rounded-full shadow-2xl z-40 transition transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>

            <!-- Modal -->
            <div x-show="openGeneralBooking" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div class="bg-zinc-900 rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl border border-zinc-800"
                    @click.away="openGeneralBooking = false">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Book a Trip</h3>
                        <button @click="openGeneralBooking = false" class="text-zinc-400 hover:text-white">✕</button>
                    </div>
                    <input type="text" x-model="search"
                        @input.debounce.300ms="fetch('/api/packages/search?q='+search).then(r=>r.json()).then(d=>packages=d)"
                        placeholder="Where do you want to go? (e.g. Maasai Mara)"
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white placeholder-zinc-400 focus:border-green-400 outline-none mb-4">
                    <div class="max-h-64 overflow-y-auto space-y-3">
                        <template x-for="pkg in packages" :key="pkg.id">
                            <div class="flex items-center justify-between p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700 transition cursor-pointer"
                                @click="openGeneralBooking = false; $dispatch('open-booking', { packageId: pkg.id })">
                                <div>
                                    <div x-text="pkg.title" class="font-medium"></div>
                                    <div x-text="pkg.location" class="text-sm text-zinc-400"></div>
                                </div>
                                <span x-text="'KSh ' + Number(pkg.price).toLocaleString()"
                                    class="text-green-400 font-semibold"></span>
                            </div>
                        </template>
                        <div x-show="search && !packages.length" class="text-center text-zinc-400 py-4 text-sm">
                            <p>No packages found for this destination yet.</p>
                            <div class="flex justify-center gap-4 mt-3">
                                <a href="{{ route('contact') }}" data-gtag-event="no_results_quote_click" class="text-amber-400 hover:text-amber-300 font-semibold">Get a custom quote</a>
                                <a :href="'https://wa.me/{{ config('services.whatsapp.number') }}?text=' + encodeURIComponent('Hi Vumbi Ventures, I searched for ' + search + ' and would like help planning a trip.')"
                                    target="_blank" rel="noopener"
                                    data-gtag-event="whatsapp_click" data-gtag-params='{"placement":"discover_modal_no_results"}'
                                    class="text-green-400 hover:text-green-300 font-semibold">WhatsApp us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================= -->
        <!-- 5. BOOKING REQUEST MODAL                       -->
        <!-- ============================================= -->
        <div x-data="bookingModal()" x-show="open" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-zinc-900 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl border border-zinc-800"
                @click.away="open = false">
                <h3 class="text-xl font-bold mb-4">Book This Trip</h3>
                <form @submit.prevent="submitBooking">
                    <input type="hidden" x-model="packageId">
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Your Name *</label>
                        <input type="text" x-model="form.name" required
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Phone Number *</label>
                        <input type="text" x-model="form.phone" required
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3">
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Email (optional)</label>
                        <input type="email" x-model="form.email"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Notes (optional)</label>
                        <textarea x-model="form.notes" rows="2" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="open = false"
                            class="px-6 py-2 border border-zinc-700 rounded-xl">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-500 text-white rounded-xl font-medium">Send Request</button>
                    </div>
                </form>
            </div>
        </div>

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
                    weather: {},
                    partner_packages: []
                },

                track(name, params) {
                    if (typeof gtag === 'function') {
                        gtag('event', name, params || {});
                    }
                },

                async searchPlace() {
                    if (!this.query?.trim()) return;

                    this.track('discover_search', { query: this.query.trim() });
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

                        if (this.result?.city) {
                            this.track('destination_view', { destination: this.result.city, source: 'discover' });
                        } else if (data.status !== 'building') {
                            // Nothing came back for this search: show the fallback instead of a dead end.
                            this.error = 'no_results';
                        }

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
                                if (this.result?.city) {
                                    this.track('destination_view', { destination: this.result.city, source: 'discover' });
                                }
                                this.building = false;
                                clearInterval(interval);
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    }, 4500);
                }
            }));

            Alpine.data('bookingModal', () => ({
                open: false,
                packageId: null,
                form: { name: '', phone: '', email: '', notes: '' },

                openForPackage(pkgId) {
                    this.packageId = pkgId;
                    this.form = { name: '', phone: '', email: '', notes: '' };
                    this.open = true;
                },

                async submitBooking() {
                    try {
                        const res = await fetch('/api/partner-leads', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                customer_name: this.form.name,
                                customer_phone: this.form.phone,
                                customer_email: this.form.email,
                                package_id: this.packageId,
                                notes: this.form.notes
                            })
                        });

                        if (res.ok) {
                            alert('Request sent! We’ll contact you shortly.');
                            this.open = false;
                        } else {
                            const err = await res.json();
                            alert(err.message || 'Something went wrong. Please try again.');
                        }
                    } catch (e) {
                        alert('Network error. Please try again.');
                    }
                }
            }));
        });

        function dispatchBookingEvent(pkgId) {
            window.dispatchEvent(new CustomEvent('open-booking', { detail: { packageId: pkgId } }));
        }

        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('open-booking', (e) => {
                const modal = document.querySelector('[x-data*="bookingModal"]');
                if (modal && modal._x_dataStack) {
                    modal._x_dataStack[0].openForPackage(e.detail.packageId);
                }
            });
        });
    </script>
@endsection