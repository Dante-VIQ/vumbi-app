<?php

use Livewire\Component;
use App\Models\Destination;
use App\Models\Blog;
use App\Models\Culture;
use App\Services\AffiliateMatcher;
use App\Services\AffiliateExecutionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

new class extends Component
{
    public Destination $destination;
    public $relatedDestinations = [];
    public $relatedStories = [];
    public $nearbyCultures = [];
    public $affiliateOffers = [];
    public $hotelDeals = [];
    public $tourDeals = [];

    public function mount($destination = null, $id = null, $slug = null)
    {
        if ($destination instanceof Destination && $destination->exists) {
            $this->destination = $destination;
        } elseif ($slug) {
            $this->destination = Destination::where('slug', $slug)->firstOrFail();
        } elseif ($id) {
            $this->destination = Destination::findOrFail($id);
        } elseif (is_numeric($destination)) {
            $this->destination = Destination::findOrFail($destination);
        } elseif (is_string($destination)) {
            $this->destination = Destination::where('slug', $destination)->firstOrFail();
        } else {
            abort(404);
        }

        // Load related content and affiliate offers
        $this->loadRelatedContent();
    }

    private function loadRelatedContent(): void
    {
        $location = $this->destination->location ?? $this->destination->name;
        $name = $this->destination->name;

        // Related destinations (same location or similar region)
        $this->relatedDestinations = Destination::where('id', '!=', $this->destination->id)
            ->where(function ($query) use ($location) {
                if ($location) {
                    $query->where('location', 'like', "%{$location}%")
                          ->orWhere('name', 'like', "%{$location}%");
                }
            })
            ->latest()
            ->limit(3)
            ->get();

        // Related blog stories mentioning this destination
        if (class_exists(Blog::class)) {
            $this->relatedStories = Blog::where('title', 'like', "%{$name}%")
                ->orWhere('description', 'like', "%{$name}%")
                ->when($location, fn($q) => $q->orWhere('category', 'like', "%{$location}%"))
                ->latest()
                ->limit(3)
                ->get();
        }

        // Nearby cultural entries
        if (class_exists(Culture::class) && $location) {
            $this->nearbyCultures = Culture::where('location', 'like', "%{$location}%")
                ->limit(3)
                ->get();
        }

        // Fetch affiliate offers with caching (12 hours)
        $cacheKey = "destination_offers_{$this->destination->id}";
        $offers = Cache::remember($cacheKey, now()->addHours(12), function () use ($location) {
            return $this->fetchAffiliateOffers($location ?? $this->destination->name);
        });

        $this->affiliateOffers = $offers['general'] ?? [];
        $this->hotelDeals = $offers['hotels'] ?? [];
        $this->tourDeals = $offers['tours'] ?? [];
    }

    private function fetchAffiliateOffers(string $location): array
    {
        try {
            if (class_exists(AffiliateMatcher::class) && class_exists(AffiliateExecutionService::class)) {
                $matcher = app(AffiliateMatcher::class);
                $executor = app(AffiliateExecutionService::class);

                $plan = $matcher->buildPlanForLocation($location, 'destination');
                return $executor->execute($plan, $location);
            }
            return [];
        } catch (\Throwable $e) {
            report($e);
            return [];
        }
    }

    /**
     * Navigate to discover page with destination pre-filled
     */
    public function searchTravel()
    {
        return redirect()->to('/discover?search=' . urlencode($this->destination->name));
    }
};
?>

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">
    @section('title', $destination->name . ' — Travel Guide | Vumbi Ventures')
    @section('meta_description', Str::limit(strip_tags($destination->detail ?? $destination->description ?? ''), 155))

    @push('meta')
        <meta property="og:title" content="{{ $destination->name }} — Vumbi Ventures">
        <meta property="og:description" content="{{ Str::limit(strip_tags($destination->detail ?? $destination->description ?? ''), 155) }}">
        @if($destination->media_path || $destination->image)
            <meta property="og:image" content="{{ $destination->media_path ? Storage::url($destination->media_path) : $destination->image }}">
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
          "description": "{{ Str::limit(strip_tags($destination->detail ?? $destination->description ?? ''), 155) }}",
          "image": "{{ $destination->media_path ? Storage::url($destination->media_path) : ($destination->image ?? '') }}",
          "address": { 
              "@type": "PostalAddress", 
              "addressLocality": "{{ $destination->location }}" 
          }
        }
        </script>
    @endpush

    {{-- Hero Section --}}
    @php
        $heroImage = $destination->media_path 
            ? Storage::url($destination->media_path) 
            : ($destination->image ?: null);
    @endphp

    <section class="relative h-[65vh] min-h-[480px] max-h-[650px] flex items-end bg-zinc-900 overflow-hidden">
        @if($heroImage)
            <div class="absolute inset-0">
                <img src="{{ $heroImage }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-[#8B5A2B]/30 to-zinc-950"></div>
        @endif

        <div class="relative container mx-auto px-6 pb-12 text-white max-w-7xl">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-sm text-white/70 mb-4 font-medium">
                <a href="{{ url('/') }}" class="hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ url('/discover') }}" class="hover:text-white transition">Destinations</a>
                <span>/</span>
                <span class="text-white">{{ $destination->name }}</span>
            </nav>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white drop-shadow-md">
                {{ $destination->name }}
            </h1>

            @if($destination->location)
                <p class="text-lg sm:text-xl text-white/90 mt-2 font-medium flex items-center gap-2">
                    <span>📍</span> {{ $destination->location }}
                </p>
            @endif
        </div>
    </section>

    {{-- Quick Action Sticky Bar --}}
    <div class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-30 border-b border-black/5">
        <div class="container mx-auto px-6 py-3.5 max-w-7xl flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="font-semibold text-[#1A1A1A] text-sm sm:text-base">Planning a visit to {{ $destination->name }}?</span>
                <span class="hidden sm:inline-block text-xs bg-[#8B5A2B]/10 text-[#8B5A2B] font-semibold px-2.5 py-1 rounded-full border border-[#8B5A2B]/20">Verified Guide</span>
            </div>
            <button wire:click="searchTravel"
               class="bg-[#8B5A2B] text-white px-5 py-2.5 rounded-xl font-medium text-sm hover:bg-[#5C3A1E] transition shadow-sm flex items-center gap-2">
                <span>Find Stays & Safaris</span>
                <span>→</span>
            </button>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="container mx-auto px-6 py-12 max-w-7xl">
        <div class="grid lg:grid-cols-12 gap-10">
            
            {{-- Content Column --}}
            <div class="lg:col-span-8">
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-black/5 shadow-sm">
                    <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">About {{ $destination->name }}</h2>
                    
                    <div class="prose prose-lg max-w-none prose-p:text-[#3A3A3A] prose-p:leading-relaxed prose-headings:text-[#1A1A1A]">
                        {!! nl2br(e($destination->detail ?? $destination->description ?? 'Destination details coming soon.')) !!}
                    </div>

                    {{-- Quick Travel Highlights --}}
                    @if($destination->best_time_to_visit || $destination->price_range)
                        <div class="mt-10 pt-8 border-t border-black/5 grid sm:grid-cols-2 gap-4">
                            @if($destination->best_time_to_visit)
                                <div class="bg-[#F5EFE6] p-5 rounded-2xl border border-[#8B5A2B]/10">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-[#8B5A2B]">Best Time to Visit</span>
                                    <p class="font-bold text-[#1A1A1A] mt-1 text-lg">{{ $destination->best_time_to_visit }}</p>
                                </div>
                            @endif
                            @if($destination->price_range)
                                <div class="bg-[#F5EFE6] p-5 rounded-2xl border border-[#8B5A2B]/10">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-[#8B5A2B]">Estimated Budget</span>
                                    <p class="font-bold text-[#1A1A1A] mt-1 text-lg">{{ $destination->price_range }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Hotel & Accommodation Offers --}}
                @if(!empty($hotelDeals) || !empty($affiliateOffers))
                    <div class="mt-10 bg-white p-8 rounded-3xl border border-black/5 shadow-sm">
                        <h3 class="text-xl font-bold text-[#1A1A1A] mb-6 flex items-center gap-2">
                            <span>🏨</span> Stays Near {{ $destination->name }}
                        </h3>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach(collect($hotelDeals)->merge($affiliateOffers)->take(4) as $offer)
                                <a href="{{ $offer['url'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                   class="group border border-black/5 rounded-2xl p-4 hover:border-[#8B5A2B] transition bg-[#FCFAF7] flex gap-4">
                                    @if(!empty($offer['image']))
                                        <img src="{{ $offer['image'] }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-sm text-[#1A1A1A] group-hover:text-[#8B5A2B] transition line-clamp-1">
                                            {{ $offer['title'] ?? $offer['name'] ?? 'Recommended Stay' }}
                                        </h4>
                                        <p class="text-xs text-[#5C5C5C] mt-1 line-clamp-2">
                                            {{ $offer['description'] ?? 'Explore prices and availability.' }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar Column --}}
            <aside class="lg:col-span-4 space-y-8">
                
                {{-- Travel Search / Tour Widget --}}
                <div class="bg-white rounded-3xl border border-black/5 p-6 shadow-sm">
                    <h3 class="font-bold text-xl text-[#1A1A1A] mb-2">Book Expeditions</h3>
                    <p class="text-xs text-[#5C5C5C] mb-6 leading-relaxed">Search curated tour packages and transport services for this location.</p>
                    
                    <button wire:click="searchTravel"
                            class="w-full bg-[#8B5A2B] text-white py-3.5 rounded-xl font-semibold text-sm hover:bg-[#5C3A1E] transition shadow-md">
                        Search Destinations & Stays
                    </button>
                    <p class="text-[11px] text-center text-[#5C5C5C] mt-3">Verified partner rates & bookings</p>
                </div>

                {{-- Map Location Card --}}
                <div class="bg-white rounded-3xl border border-black/5 p-6 shadow-sm">
                    <h3 class="font-bold text-lg text-[#1A1A1A] mb-3">Map & Location</h3>
                    <div class="h-44 bg-[#F5EFE6] rounded-2xl flex flex-col items-center justify-center p-4 text-center border border-black/5">
                        <span class="text-3xl mb-2">📍</span>
                        <p class="font-semibold text-sm text-[#1A1A1A]">{{ $destination->name }}</p>
                        <p class="text-xs text-[#5C5C5C] mb-3">{{ $destination->location }}</p>
                        <a href="https://www.google.com/maps/search/{{ urlencode($destination->name . ' ' . $destination->location) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="text-xs font-semibold text-[#8B5A2B] hover:underline">
                            Open in Google Maps →
                        </a>
                    </div>
                </div>

                {{-- Related Blog Stories --}}
                @if(isset($relatedStories) && count($relatedStories) > 0)
                    <div class="bg-white rounded-3xl border border-black/5 p-6 shadow-sm">
                        <h3 class="font-bold text-lg text-[#1A1A1A] mb-4">Field Notes & Stories</h3>
                        <div class="space-y-4">
                            @foreach($relatedStories as $story)
                                <a href="{{ route('blog.show', $story->slug ?? $story->id) }}" class="block group">
                                    <h4 class="font-semibold text-sm text-[#1A1A1A] group-hover:text-[#8B5A2B] transition line-clamp-2">
                                        {{ $story->title }}
                                    </h4>
                                    <p class="text-xs text-[#5C5C5C] mt-1">
                                        {{ $story->created_at ? $story->created_at->format('M d, Y') : 'Read article' }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Nearby Destinations --}}
                @if(isset($relatedDestinations) && count($relatedDestinations) > 0)
                    <div class="bg-white rounded-3xl border border-black/5 p-6 shadow-sm">
                        <h3 class="font-bold text-lg text-[#1A1A1A] mb-4">Similar Destinations</h3>
                        <div class="space-y-4">
                            @foreach($relatedDestinations as $item)
                                <a href="{{ route('destination.show', $item->slug ?? $item->id) }}" class="flex gap-3 group items-center">
                                    <div class="w-12 h-12 rounded-xl bg-zinc-200 overflow-hidden shrink-0">
                                        @if($item->media_path)
                                            <img src="{{ Storage::url($item->media_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-[#F5EFE6] flex items-center justify-center text-xs">📍</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-sm text-[#1A1A1A] group-hover:text-[#8B5A2B] transition line-clamp-1">
                                            {{ $item->name }}
                                        </h4>
                                        <p class="text-xs text-[#5C5C5C]">{{ $item->location }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>