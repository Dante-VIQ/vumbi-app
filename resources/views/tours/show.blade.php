@extends('layouts.app')

@section('title', $package->title . ' | Safari & Tour | Vumbi Ventures')
@section('description', Str::limit($package->short_description, 160))
 
@push('schema')
@php
    $pageSchemas = [];

    // Get itinerary as array (if cast is set, it's already an array)
    $itinerary = $package->itinerary ?? [];
    // If it's still a string (in case cast not applied), decode it
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
                    "name" => $item['title'] ?? 'Day ' . ($index + 1),
                    "description" => $item['description'] ?? ''
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

    // Product with AggregateRating (if reviews exist)
    if ($package->reviews_count > 0) {
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
@endpush
 
@section('title', $package->meta_title ?: $package->title . ' | Vumbi Ventures')
@section('description', $package->meta_description ?: Str::limit($package->short_description ?? $package->description, 160))
@section('keywords', $package->meta_keywords ?: 'safari, tour, Kenya, Tanzania, adventure')

@section('og_title', $package->meta_title ?: $package->title)
@section('og_description', $package->meta_description ?: Str::limit($package->short_description ?? $package->description, 160))
@section('og_image', $package->featured_image ? asset('storage/' . $package->featured_image) : asset('images/og-default.jpg'))

@section('canonical', url()->current())
@section('robots', 'index, follow')

@section('content')
    <div class="min-h-screen bg-zinc-950 text-white">
        <!-- Hero -->
        <div class="h-96 bg-cover bg-center relative"
            style="background-image: url('{{ $package->image ?: 'https://picsum.photos/1200/600?random=' . $package->id }}')">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-8 max-w-4xl mx-auto">
                <span
                    class="text-xs text-green-400 uppercase tracking-wide bg-zinc-900/80 px-3 py-1 rounded-full">{{ $package->type }}</span>
                <h1 class="text-4xl font-bold mt-2">{{ $package->title }}</h1>
                <p class="text-zinc-300 mt-2">{{ $package->location }}</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Quick Facts -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @if ($package->duration_days)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold">{{ $package->duration_days }}D /
                                {{ $package->duration_nights }}N
                            </div>
                            <div class="text-xs text-zinc-400 mt-1">Duration</div>
                        </div>
                    @endif
                    @if ($package->difficulty)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold capitalize">{{ $package->difficulty }}</div>
                            <div class="text-xs text-zinc-400 mt-1">Difficulty</div>
                        </div>
                    @endif
                    @if ($package->group_size_max)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold">{{ $package->group_size_min }}‑{{ $package->group_size_max }}
                            </div>
                            <div class="text-xs text-zinc-400 mt-1">Group Size</div>
                        </div>
                    @endif
                    @if ($package->vehicle_type)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-sm">{{ $package->vehicle_type }}</div>
                            <div class="text-xs text-zinc-400 mt-1">Vehicle</div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Description</h2>
                    <p class="text-zinc-300 leading-relaxed">{{ $package->description }}</p>
                </div>

                <!-- Itinerary -->
                {{-- @if (!empty($package->itinerary))
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Day‑by‑day Itinerary</h2>
                    <div class="space-y-4">
                        @foreach ($package->itinerary as $index => $day)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4">
                            <div class="text-green-400 font-semibold mb-1">Day {{ $index + 1 }}</div>
                            <p class="text-zinc-300">{{ $day }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif --}}

                @if (!empty($package->itinerary))
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Day‑by‑day Itinerary</h2>
                        <div class="space-y-4">
                            @foreach ($package->itinerary as $index => $dayContent)
                                @php
                                    // Split into lines
                                    $lines = explode("\n", trim($dayContent));

                                    // First line is the title
                                    $title = array_shift($lines) ?: 'Day ' . ($index + 1);

                                    // Everything else is the description
                                    $description = implode("\n", $lines);
                                    $description = trim($description);
                                @endphp
                                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4">
                                    <div class="text-zinc-400 font-semibold mb-1">
                                        {{ $title }}
                                    </div>
                                    @if ($description)
                                        <div class="text-gray-300 whitespace-pre-line">{{ $description }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Included / Excluded -->
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                    @if (!empty($package->included))
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-green-400 mb-3">What's Included</h3>
                            <ul class="space-y-2">
                                @foreach ($package->included as $item)
                                    <li class="flex items-center gap-2 text-zinc-300">
                                        <span class="text-green-400">✓</span> {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (!empty($package->excluded))
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-red-400 mb-3">What's Excluded</h3>
                            <ul class="space-y-2">
                                @foreach ($package->excluded as $item)
                                    <li class="flex items-center gap-2 text-zinc-300">
                                        <span class="text-red-400">✗</span> {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Similar Tours -->
                @if ($similar->count())
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">You May Also Like</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($similar as $similarTour)
                                <a href="{{ route('tours.show', $similarTour) }}"
                                    class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-green-400/50 transition">
                                    <div class="h-32 bg-cover bg-center"
                                        style="background-image: url('{{ $similarTour->image ?: 'https://picsum.photos/200/150?random=' . $similarTour->id }}')">
                                    </div>
                                    <div class="p-3">
                                        <h4 class="font-medium text-sm">{{ $similarTour->title }}</h4>
                                        <span class="text-green-400 text-sm font-semibold mt-1 block">KSh
                                            {{ number_format($similarTour->price) }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Card (Sticky Booking) -->
            <div class="lg:sticky lg:top-8 h-fit">
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                    <div class="text-3xl font-bold text-green-400 mb-4">$ {{ number_format($package->price) }}</div>
                    <p class="text-sm text-zinc-400 mb-6">Per person</p>

                    <a href="{{ route('booking', $package->slug) }}"
                        class="w-full bg-green-600 hover:bg-green-500 text-white py-3 rounded-xl font-medium mb-6 transition p-4 flex justify-center items-center gap-2">
                        Book This Safari
                    </a>
                    {{-- tours/show.blade.php --}}
                    <div class="tour-cta-block">
                        @if ($package->isAffiliate())
                            <a href="{{ route('affiliate.redirect', [$package->affiliate_source, packaget->id]) }}"
                                target="_blank" class="btn-primary btn-large">
                                Book This Trip Now →
                            </a>
                        @else
                            <button onclick="window.dispatchEvent(new CustomEvent('open-booking-modal'))"
                                class="btn-primary btn-large">
                                Request to Book →
                            </button>

                            {{-- Modal, hidden until triggered --}}
                            <div x-data="{ open: false }" x-on:open-booking-modal.window="open = true" x-show="open"
                                x-cloak>
                                <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50"
                                    x-on:click.self="open = false">
                                    @livewire('booking-modal', ['package' => $package])
                                </div>
                            </div>
                        @endif
                    </div>
                    <a href="https://wa.me/254734591543?text=I'm%20interested%20in%20{{ urlencode($package->title) }}"
                        target="_blank"
                        class="w-full bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 py-3 rounded-xl font-medium transition flex justify-center items-center gap-2 mt-6">
                        💬 Ask on WhatsApp
                    </a>


                    <div class="border-t border-zinc-800 mt-6 pt-4 text-sm text-zinc-500 space-y-2">
                        @if ($package->duration_days)
                            <p>Duration: {{ $package->duration_days }}D / {{ $package->duration_nights }}N</p>
                        @endif
                        @if ($package->difficulty)
                            <p>Difficulty: {{ ucfirst($package->difficulty) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function dispatchBookingEvent(packageId) {
            document.dispatchEvent(new CustomEvent('open-booking', {
                detail: {
                    packageId
                }
            }));
        }
    </script>
@endsection
