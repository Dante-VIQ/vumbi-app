@extends('layouts.app')

@section('title', $destination->name . ' — Travel Guide | Vumbi Ventures')
@section('meta_description', Str::limit(strip_tags($destination->detail ?? ''), 155))

@section('content')

@push('meta')
    <meta property="og:title" content="{{ $destination->name }} — Vumbi Ventures">
    <meta property="og:description" content="{{ Str::limit(strip_tags($destination->detail ?? ''), 155) }}">
    @if($destination->media_path)
    <meta property="og:image" content="{{ Storage::url($destination->media_path) }}">
    @endif
    <meta property="og:type" content="place">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('structured-data')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TouristDestination",
      "name": "{{ $destination->name }}",
      "description": "{{ Str::limit(strip_tags($destination->detail ?? ''), 155) }}",
      @if($destination->media_path)
      "image": "{{ Storage::url($destination->media_path) }}",
      @endif
      "address": { 
        "@type": "PostalAddress", 
        "addressLocality": "{{ $destination->location }}",
        "addressCountry": "{{ $destination->country ?? 'Kenya' }}"
      }
    }
    </script>
@endpush

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">
    {{-- Hero with Image Background --}}
    <section class="relative h-[60vh] md:h-[70vh] min-h-[400px] md:min-h-[500px] flex items-end">
        @if($destination->media_path)
            <div class="absolute inset-0">
                <img src="{{ Storage::url($destination->media_path) }}" alt="{{ $destination->name }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[#8B5A2B] to-[#5C3A1E]"></div>
        @endif
        
        <div class="relative container mx-auto px-6 pb-10 md:pb-14 text-white">
            <nav class="flex items-center gap-2 text-sm text-white/80 mb-4" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ route('destinations.give') }}" class="hover:text-white transition">Destinations</a>
                <span>/</span>
                <span class="text-white">{{ $destination->name }}</span>
            </nav>
            
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold leading-tight">{{ $destination->name }}</h1>
            <p class="text-lg md:text-xl text-white/90 mt-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $destination->location }}
            </p>
        </div>
    </section>

    {{-- Quick Booking Bar --}}
    <div class="bg-white shadow-md sticky top-16 md:top-20 z-30 border-b border-black/5">
        <div class="container mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="font-semibold">Ready to visit {{ $destination->name }}?</span>
                <span class="text-[#5C5C5C] text-sm ml-2 hidden sm:inline">Best price guarantee</span>
            </div>
            <a href="{{ url('/discover?search='.urlencode($destination->name)) }}"
               class="bg-[#8B5A2B] text-white px-6 py-3 rounded-xl font-medium hover:bg-[#5C3A1E] transition shadow-sm hover:shadow-md">
                Find Hotels & Tours →
            </a>
        </div>
    </div>

    {{-- Content + Sidebar --}}
    <div class="container mx-auto px-6 py-10 md:py-14">
        <div class="grid lg:grid-cols-3 gap-10">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                <div class="prose prose-lg max-w-none">
                    {!! nl2br(e($destination->detail ?? '')) !!}
                </div>

                {{-- Quick Facts --}}
                @if($destination->best_time_to_visit || $destination->price_range)
                    <div class="mt-8 grid sm:grid-cols-2 gap-4">
                        @if($destination->best_time_to_visit)
                            <div class="bg-[#F5EFE6] p-5 rounded-xl border border-[#8B5A2B]/10">
                                <span class="text-sm text-[#5C5C5C]">Best Time to Visit</span>
                                <p class="font-semibold text-lg mt-1">{{ $destination->best_time_to_visit }}</p>
                            </div>
                        @endif
                        @if($destination->price_range)
                            <div class="bg-[#F5EFE6] p-5 rounded-xl border border-[#8B5A2B]/10">
                                <span class="text-sm text-[#5C5C5C]">Price Range</span>
                                <p class="font-semibold text-lg mt-1">{{ $destination->price_range }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                {{-- Booking Widget --}}
                <div class="bg-white rounded-2xl border border-black/5 p-6 shadow-sm">
                    <h3 class="font-semibold text-xl mb-4">Book Your Trip</h3>
                    <form action="{{ url('/discover') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="search" value="{{ $destination->name }}">
                        <button type="submit" class="w-full bg-[#8B5A2B] text-white py-3 rounded-xl font-medium hover:bg-[#5C3A1E] transition">
                            Search Hotels
                        </button>
                    </form>
                    <a href="{{ url('/discover?search='.urlencode($destination->location)) }}"
                       class="block w-full text-center border border-[#8B5A2B]/20 text-[#8B5A2B] py-3 rounded-xl font-medium mt-3 hover:bg-[#F5EFE6] transition">
                        Browse Tours
                    </a>
                    <p class="text-xs text-center text-[#5C5C5C] mt-3">Powered by our travel partners</p>
                </div>

                {{-- Map / Location --}}
                <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                    <h3 class="font-semibold mb-3">Location</h3>
                    <div class="h-40 bg-[#E8DFD5] rounded-xl flex items-center justify-center text-[#5C5C5C] overflow-hidden">
                        <a href="https://www.google.com/maps/search/{{ urlencode($destination->name . ' ' . $destination->location) }}" 
                           target="_blank" rel="noopener"
                           class="text-[#8B5A2B] underline hover:text-[#5C3A1E] transition text-center px-4">
                            View on Google Maps →
                        </a>
                    </div>
                </div>

                {{-- Tours Available --}}
                @if($destination->tours && $destination->tours->count() > 0)
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold mb-4">Tours in {{ $destination->name }}</h3>
                        <div class="space-y-3">
                            @foreach($destination->tours->take(5) as $tour)
                                <a href="{{ route('tours.show', $tour) }}" 
                                   class="flex items-center justify-between p-3 bg-[#FCFAF7] rounded-xl hover:bg-[#F5EFE6] transition group">
                                    <span class="font-medium group-hover:text-[#8B5A2B]">{{ $tour->title }}</span>
                                    <span class="text-sm text-[#8B5A2B] font-medium">KSh {{ number_format($tour->price) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>

    {{-- Related Destinations --}}
    @if(isset($relatedDestinations) && $relatedDestinations->count() > 0)
        <section class="py-12 bg-white border-t border-black/5">
            <div class="container mx-auto px-6">
                <h2 class="text-2xl font-semibold mb-6">More Destinations</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedDestinations as $related)
                        <a href="{{ route('pages.destination', $related) }}" 
                           class="group block bg-[#FCFAF7] rounded-2xl overflow-hidden border border-black/5 hover:shadow-lg transition">
                            @if($related->media_path)
                                <div class="aspect-[16/9] overflow-hidden">
                                    <img src="{{ Storage::url($related->media_path) }}" 
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                            @endif
                            <div class="p-5">
                                <h3 class="font-semibold group-hover:text-[#8B5A2B]">{{ $related->name }}</h3>
                                <p class="text-sm text-[#5C5C5C] mt-1">{{ $related->location }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>

@endsection