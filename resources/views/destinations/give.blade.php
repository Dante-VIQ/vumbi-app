@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-zinc-950 text-white selection:bg-[#8B5A2B] selection:text-white">
    
    {{-- Header / Hero Section --}}
    <section class="relative border-b border-zinc-800/80 bg-zinc-900/40 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <span class="text-xs font-semibold uppercase tracking-widest text-[#8B5A2B] bg-[#8B5A2B]/10 px-3 py-1 rounded-full border border-[#8B5A2B]/20 inline-block mb-3">
                    Explore Kenya & Beyond
                </span>
                <h1 class="text-3xl sm:text-5xl font-bold tracking-tight text-white">
                    Destinations
                </h1>
                <p class="mt-4 text-zinc-400 text-base sm:text-lg leading-relaxed">
                    Discover handpicked travel locations, wildlife sanctuaries, and coastal retreats curated for unforgettable journeys.
                </p>
            </div>
        </div>
    </section>

    {{-- Destinations Grid Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(isset($destinations) && $destinations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach ($destinations as $destination)
                    @php
                        $imageSrc = $destination->image 
                            ? (Str::startsWith($destination->image, ['http://', 'https://']) 
                                ? $destination->image 
                                : asset('uploads/' . $destination->image))
                            : 'https://picsum.photos/600/400?random=' . $destination->id;
                    @endphp

                    <a href="{{ route('pages.destination', $destination->slug ?? $destination) }}"
                        class="group flex flex-col justify-between bg-zinc-900/80 border border-zinc-800/80 rounded-2xl overflow-hidden hover:border-[#8B5A2B]/50 hover:shadow-2xl hover:shadow-[#8B5A2B]/10 transition-all duration-300 hover:-translate-y-1">
                        
                        <div>
                            {{-- Card Cover Image --}}
                            <div class="h-52 w-full overflow-hidden relative bg-zinc-800">
                                <img src="{{ $imageSrc }}" 
                                     alt="{{ $destination->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                     loading="lazy">
                                
                                @if ($destination->location)
                                    <span class="absolute top-3 left-3 bg-zinc-950/80 backdrop-blur-md text-zinc-200 text-xs font-medium px-3 py-1 rounded-full border border-white/10 shadow-sm">
                                        📍 {{ $destination->location }}
                                    </span>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6">
                                <h3 class="font-semibold text-xl text-white group-hover:text-[#8B5A2B] transition-colors leading-snug">
                                    {{ $destination->name }}
                                </h3>

                                <p class="text-sm text-zinc-400 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $destination->detail ?? $destination->description ?? 'Explore highlights, accommodations, and unique travel experiences.' }}
                                </p>
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="px-6 pb-6 pt-2 border-t border-zinc-800/40 flex items-center justify-between text-xs font-medium text-zinc-400 group-hover:text-[#8B5A2B]">
                            <span>Explore Destination</span>
                            <span class="group-hover:translate-x-1 transition-transform duration-200">→</span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if(method_exists($destinations, 'links'))
                <div class="mt-12 flex justify-center">
                    {{ $destinations->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="text-center py-20 bg-zinc-900/50 rounded-3xl border border-dashed border-zinc-800 max-w-lg mx-auto my-8 p-8">
                <div class="text-4xl mb-3">📍</div>
                <h3 class="text-lg font-semibold text-white">No destinations found</h3>
                <p class="text-sm text-zinc-400 mt-2">
                    We haven't added any destinations matching this category yet. Check back soon for updates!
                </p>
                <a href="{{ url('/') }}" class="mt-6 inline-block px-5 py-2.5 rounded-xl bg-[#8B5A2B] text-white text-sm font-medium hover:bg-[#5C3A1E] transition">
                    Return to Home
                </a>
            </div>
        @endif
    </div>
</div>
@endsection