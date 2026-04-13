@extends('layouts.app')

@section('title', $package->meta_title ?: $package->title . ' | Vumbi Ventures')
@section('description', $package->meta_description ?: Str::limit($package->short_description ?? $package->description, 160))
@section('keywords', $package->meta_keywords ?: 'safari, tour, Kenya, Tanzania, adventure')

@section('og_title', $package->meta_title ?: $package->title)
@section('og_description', $package->meta_description ?: Str::limit($package->short_description ?? $package->description, 160))
@section('og_image', $package->featured_image ? asset('storage/' . $package->featured_image) : asset('images/og-default.jpg'))

@section('canonical', url()->current())
@section('robots', 'index, follow')

@push('schema')
@php
    $pageSchemas = [];

    $itinerary = $package->itinerary ?? [];
    if (is_string($itinerary)) {
        $itinerary = json_decode($itinerary, true) ?? [];
    }

    $pageSchemas[] = [
        "@context" => "https://schema.org",
        "@type" => "TouristTrip",
        "name" => $package->title,
        "description" => Str::limit($package->description, 160),
        "image" => $package->featured_image ? asset('storage/' . $package->featured_image) : asset('images/og-default.jpg'),
        "itinerary" => [
            "@type" => "ItemList",
            "itemListElement" => collect($itinerary)->map(function ($item, $index) {
                return [
                    "@type" => "ListItem",
                    "position" => $index + 1,
                    "name" => is_array($item) ? ($item['title'] ?? 'Day ' . ($index + 1)) : 'Day ' . ($index + 1),
                    "description" => is_array($item) ? ($item['description'] ?? '') : (string)$item
                ];
            })->toArray()
        ],
        "touristType" => implode(', ', $package->tourist_types ?? ['Adventure', 'Culture', 'Wildlife']),
        "offers" => [
            "@type" => "Offer",
            "price" => $package->price,
            "priceCurrency" => "USD",
            "availability" => $package->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            "url" => url()->current(),
            "validFrom" => now()->toIso8601String(),
            "priceValidUntil" => now()->addYear()->toIso8601String()
        ],
        "@id" => url()->current(),
        "url" => url()->current()
    ];

    if (($package->reviews_count ?? 0) > 0) {
        $pageSchemas[] = [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "name" => $package->title,
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => $package->average_rating,
                "reviewCount" => $package->reviews_count,
                "bestRating" => "5"
            ]
        ];
    }
@endphp
<script type="application/ld+json">
    {!! json_encode($pageSchemas, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <div class="min-h-screen bg-zinc-950 text-white">
        <!-- Hero Header -->
        <div class="h-96 bg-cover bg-center relative"
            style="background-image: url('{{ $package->image ? asset('storage/' . $package->image) : 'https://picsum.photos/1200/600?random=' . $package->id }}')">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-8 max-w-5xl mx-auto">
                <span class="text-xs font-semibold text-green-400 uppercase tracking-widest bg-zinc-900/90 backdrop-blur border border-zinc-800 px-3 py-1 rounded-full">
                    {{ $package->type ?? 'Safari' }}
                </span>
                <h1 class="text-3xl md:text-5xl font-extrabold mt-3 tracking-tight">{{ $package->title }}</h1>
                <p class="text-zinc-300 mt-2 flex items-center gap-2 text-sm md:text-base">
                    <span>📍</span> {{ $package->location }}
                </p>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Quick Key Metrics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @if ($package->duration_days)
                        <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-4 text-center">
                            <div class="text-xl font-bold text-white">{{ $package->duration_days }}D / {{ $package->duration_nights }}N</div>
                            <div class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">Duration</div>
                        </div>
                    @endif
                    @if ($package->difficulty)
                        <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-4 text-center">
                            <div class="text-xl font-bold text-white capitalize">{{ $package->difficulty }}</div>
                            <div class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">Difficulty</div>
                        </div>
                    @endif
                    @if ($package->group_size_max)
                        <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-4 text-center">
                            <div class="text-xl font-bold text-white">{{ $package->group_size_min }}‑{{ $package->group_size_max }}</div>
                            <div class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">Group Size</div>
                        </div>
                    @endif
                    @if ($package->vehicle_type)
                        <div class="bg-zinc-900/80 border border-zinc-800/80 rounded-2xl p-4 text-center">
                            <div class="text-xl font-bold text-white text-sm truncate">{{ $package->vehicle_type }}</div>
                            <div class="text-xs text-zinc-400 mt-1 uppercase tracking-wider">Vehicle</div>
                        </div>
                    @endif
                </div>

                <!-- Overview / Description -->
                <div>
                    <h2 class="text-2xl font-bold text-white mb-4">Tour Overview</h2>
                    <p class="text-zinc-300 leading-relaxed whitespace-pre-line">{{ $package->description }}</p>
                </div>

                <!-- Day-by-Day Itinerary -->
                @if (!empty($package->itinerary))
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6">Day‑by‑day Itinerary</h2>
                        <div class="space-y-4">
                            @foreach ($package->itinerary as $index => $dayContent)
                                @php
                                    $lines = explode("\n", trim($dayContent));
                                    $title = array_shift($lines) ?: 'Day ' . ($index + 1);
                                    $description = trim(implode("\n", $lines));
                                @endphp
                                <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-5 relative pl-6 border-l-4 border-l-green-500">
                                    <div class="text-green-400 font-semibold text-lg mb-1">
                                        {{ $title }}
                                    </div>
                                    @if ($description)
                                        <div class="text-zinc-300 text-sm leading-relaxed whitespace-pre-line mt-2">{{ $description }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Included / Excluded Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if (!empty($package->included))
                        <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-green-400 mb-4 flex items-center gap-2">
                                <span>✓</span> What's Included
                            </h3>
                            <ul class="space-y-2.5">
                                @foreach ($package->included as $item)
                                    <li class="flex items-start gap-2.5 text-sm text-zinc-300">
                                        <span class="text-green-400 font-bold shrink-0">✓</span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (!empty($package->excluded))
                        <div class="bg-zinc-900/80 border border-zinc-800 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-rose-400 mb-4 flex items-center gap-2">
                                <span>✗</span> What's Excluded
                            </h3>
                            <ul class="space-y-2.5">
                                @foreach ($package->excluded as $item)
                                    <li class="flex items-start gap-2.5 text-sm text-zinc-300">
                                        <span class="text-rose-400 font-bold shrink-0">✗</span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Similar Tour Packages -->
                @if (isset($similar) && $similar->count())
                    <div class="pt-6 border-t border-zinc-900">
                        <h2 class="text-2xl font-bold text-white mb-6">You May Also Like</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($similar as $similarTour)
                                <a href="{{ route('tours.show', $similarTour) }}"
                                    class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-green-500/50 transition duration-300">
                                    <div class="h-32 bg-cover bg-center group-hover:scale-105 transition duration-500"
                                        style="background-image: url('{{ $similarTour->image ? asset('storage/' . $similarTour->image) : 'https://picsum.photos/200/150?random=' . $similarTour->id }}')">
                                    </div>
                                    <div class="p-3">
                                        <h4 class="font-medium text-sm text-zinc-200 line-clamp-1 group-hover:text-green-400 transition">{{ $similarTour->title }}</h4>
                                        <span class="text-green-400 text-sm font-bold mt-1 block">
                                            $ {{ number_format($similarTour->price) }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Sticky Booking Box -->
            <div class="lg:sticky lg:top-8 h-fit space-y-4">
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl">
                    <div class="flex items-baseline justify-between mb-1">
                        <span class="text-sm text-zinc-400">Starting from</span>
                        <div class="text-3xl font-extrabold text-green-400">$ {{ number_format($package->price) }}</div>
                    </div>
                    <p class="text-xs text-zinc-500 text-right mb-6">Per person</p>

                    <div x-data="{ openModal: false }" class="space-y-3">
                        @if (method_exists($package, 'isAffiliate') && $package->isAffiliate())
                            <a href="{{ route('affiliate.redirect', [$package->affiliate_source, $package->id]) }}"
                                target="_blank"
                                class="w-full bg-green-600 hover:bg-green-500 text-white py-3.5 px-4 rounded-xl font-bold transition flex justify-center items-center gap-2 shadow-lg shadow-green-900/20">
                                Book This Trip Now →
                            </a>
                        @else
                            <button @click="openModal = true"
                                class="w-full bg-green-600 hover:bg-green-500 text-white py-3.5 px-4 rounded-xl font-bold transition flex justify-center items-center gap-2 shadow-lg shadow-green-900/20">
                                Request to Book →
                            </button>

                            <!-- Livewire Booking Modal Wrapper -->
                            <div x-show="openModal"
                                x-on:open-booking-modal.window="openModal = true"
                                x-cloak
                                class="fixed inset-0 z-50 overflow-y-auto"
                                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="openModal" 
                                         x-transition:enter="ease-out duration-300" 
                                         x-transition:enter-start="opacity-0" 
                                         x-transition:enter-end="opacity-100" 
                                         x-transition:leave="ease-in duration-200" 
                                         x-transition:leave-start="opacity-100" 
                                         x-transition:leave-end="opacity-0" 
                                         class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" 
                                         @click="openModal = false"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                    <div x-show="openModal" 
                                         x-transition:enter="ease-out duration-300" 
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                         x-transition:leave="ease-in duration-200" 
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                         class="inline-block align-bottom bg-zinc-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-zinc-800">
                                        <livewire:booking-modal :partner-package-id="$package->id" />
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <a href="https://wa.me/254745781236?text=I'm%20interested%20in%20{{ urlencode($package->title) }}"
                        target="_blank"
                        class="w-full bg-zinc-800/80 hover:bg-zinc-800 border border-zinc-700/80 text-zinc-200 py-3 rounded-xl font-semibold transition flex justify-center items-center gap-2 mt-3 text-sm">
                        💬 Ask on WhatsApp
                    </a>

                    <div class="border-t border-zinc-800 mt-6 pt-4 text-xs text-zinc-400 space-y-2">
                        @if ($package->duration_days)
                            <p class="flex justify-between">
                                <span>Duration:</span>
                                <span class="text-zinc-200 font-medium">{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</span>
                            </p>
                        @endif
                        @if ($package->difficulty)
                            <p class="flex justify-between">
                                <span>Difficulty:</span>
                                <span class="text-zinc-200 font-medium capitalize">{{ $package->difficulty }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection