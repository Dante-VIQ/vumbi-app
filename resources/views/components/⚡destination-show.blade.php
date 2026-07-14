<?php

use Livewire\Component;
use App\Models\Doctor;
use App\Models\Destination;
use App\Models\Blog;
use App\Models\Culture;
use App\Services\AffiliateMatcher;
use App\Services\AffiliateExecutionService;
use Illuminate\Support\Facades\Cache;

new class extends Component
{
    public Doctor $doctor;
    public $relatedDestinations = [];
    public $relatedStories = [];
    public $nearbyCultures = [];
    public $affiliateOffers = [];
    public $hotelDeals = [];
    public $tourDeals = [];
    public $destination; // For backward compatibility with existing views

    public function mount(Destination $destination, $id = null, $slug = null)
    {

        $this->destination = $destination;
        // Load doctor (supports ID or slug-based routing)
        if ($id) {
            $this->destination = Doctor::findOrFail($id);
        } elseif ($slug) {
            $this->destination = Doctor::where('slug', $slug)->firstOrFail();
        } else {
            abort(404);
        }

        // Load related data in parallel using cache
        $this->loadRelatedContent();
    }

    private function loadRelatedContent(): void
    {
        $location = $this->destination->location;
        $name = $this->destination->name;

        // Related destinations (same location or similar name)
        $this->relatedDestinations = Doctor::where('id', '!=', $this->destination->id)
            ->where(function ($query) use ($location, $name) {
                $query->where('location', 'like', "%{$location}%")
                      ->orWhere('name', 'like', "%{$location}%");
            })
            ->latest()
            ->limit(3)
            ->get();

        // Related blog stories mentioning this destination
        $this->relatedStories = Blog::where('title', 'like', "%{$name}%")
            ->orWhere('description', 'like', "%{$name}%")
            ->orWhere('category', 'like', "%{$location}%")
            ->latest()
            ->limit(3)
            ->get();

        // Nearby cultural entries
        $this->nearbyCultures = Culture::where('location', 'like', "%{$location}%")
            ->limit(3)
            ->get();

        // Fetch affiliate offers with caching (12 hours)
        $cacheKey = "destination_offers_{$this->destination->id}";
        $offers = Cache::remember($cacheKey, now()->addHours(12), function () use ($location) {
            return $this->fetchAffiliateOffers($location);
        });

        $this->affiliateOffers = $offers['general'] ?? [];
        $this->hotelDeals = $offers['hotels'] ?? [];
        $this->tourDeals = $offers['tours'] ?? [];
    }

    private function fetchAffiliateOffers(string $location): array
    {
        try {
            $matcher = app(AffiliateMatcher::class);
            $executor = app(AffiliateExecutionService::class);

            // Build plan specifically for this location/destination
            $plan = $matcher->buildPlanForLocation($location, 'destination');

            return $executor->execute($plan, $location);
        } catch (\Exception $e) {
            report($e);
            return [];
        }
    }

    /**
     * Provide SEO metadata to the layout
     */
    public function layoutData(): array
    {
        $description = $this->destination->detail
            ? \Str::limit(strip_tags($this->destination->detail), 155)
            : "Discover {$this->destination->name} in {$this->destination->location}. Plan your trip with Vumbi Ventures.";

        return [
            'title' => "{$this->destination->name} — Travel Guide | Vumbi Ventures",
            'description' => $description,
            'ogImage' => $this->destination->media_path
                ? \Storage::url($this->destination->media_path)
                : asset('images/default-destination.jpg'),
            'canonical' => url()->current(),
        ];
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
    @section('meta_description', Str::limit(strip_tags($destination->detail), 155))

    @push('meta')
        <meta property="og:title" content="{{ $destination->name }} — Vumbi Ventures">
        <meta property="og:description" content="{{ Str::limit(strip_tags($destination->detail), 155) }}">
        <meta property="og:image" content="{{ Storage::url($destination->media_path) }}">
        <meta property="og:type" content="place">
        <link rel="canonical" href="{{ url()->current() }}">
    @endpush

    @push('structured-data')
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "TouristDestination",
          "name": "{{ $destination->name }}",
          "description": "{{ Str::limit(strip_tags($destination->detail), 155) }}",
          "image": "{{ Storage::url($destination->media_path) }}",
          "address": { "@type": "PostalAddress", "addressLocality": "{{ $destination->location }}" }
        }
        </script>
    @endpush

    {{-- Hero with Image Background --}}
    <section class="relative h-[70vh] min-h-[500px] flex items-end">
        @if($destination->media_path)
            <div class="absolute inset-0">
                <img src="{{ Storage::url($destination->media_path) }}" alt="{{ $destination->name }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            </div>
        @endif
        <div class="relative container mx-auto px-6 pb-12 text-white">
            <nav class="flex items-center gap-2 text-sm text-white/80 mb-4">
                <a href="{{ url('/') }}" class="hover:text-white">Home</a> /
                <a href="{{ url('/discover') }}" class="hover:text-white">Discover</a> /
                <span>{{ $destination->name }}</span>
            </nav>
            <h1 class="text-4xl md:text-6xl font-bold">{{ $destination->name }}</h1>
            <p class="text-xl text-white/90 mt-2">{{ $destination->location }}</p>
        </div>
    </section>

    {{-- Quick Booking Bar --}}
    <div class="bg-white shadow-md sticky top-0 z-30 border-b border-black/5">
        <div class="container mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="font-semibold">Ready to visit?</span>
                <span class="text-[#5C5C5C] text-sm ml-2">Best price guarantee</span>
            </div>
            <a href="{{ url('/discover?search='.urlencode($destination->name)) }}"
               class="bg-[#8B5A2B] text-white px-6 py-3 rounded-xl font-medium hover:bg-[#5C3A1E] transition">
                Find Hotels & Tours →
            </a>
        </div>
    </div>

    {{-- Content + Sidebar --}}
    <div class="container mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                <div class="prose prose-lg max-w-none">
                    {!! nl2br(e($destination->detail)) !!}
                </div>

                {{-- Quick Facts --}}
                @if($destination->best_time_to_visit || $destination->price_range)
                    <div class="mt-8 grid sm:grid-cols-2 gap-4 not-prose">
                        @if($destination->best_time_to_visit)
                            <div class="bg-[#F5EFE6] p-4 rounded-xl">
                                <span class="text-sm text-[#5C5C5C]">Best Time to Visit</span>
                                <p class="font-semibold">{{ $destination->best_time_to_visit }}</p>
                            </div>
                        @endif
                        @if($destination->price_range)
                            <div class="bg-[#F5EFE6] p-4 rounded-xl">
                                <span class="text-sm text-[#5C5C5C]">Price Range</span>
                                <p class="font-semibold">{{ $destination->price_range }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                {{-- Booking Widget --}}
                <div class="bg-white rounded-2xl border border-black/5 p-6 shadow-sm">
                    <h3 class="font-semibold text-xl mb-4">Book Your Trip</h3>
                    <form action="{{ url('/discover') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="search" value="{{ $destination->name }}">
                        <button class="w-full bg-[#8B5A2B] text-white py-3 rounded-xl font-medium">Search Hotels</button>
                    </form>
                    <p class="text-xs text-center text-[#5C5C5C] mt-3">Powered by our travel partners</p>
                </div>

                {{-- Map / Location --}}
                <div class="bg-white rounded-2xl border border-black/5 p-5">
                    <h3 class="font-semibold mb-3">Location</h3>
                    <div class="h-40 bg-[#E8DFD5] rounded-xl flex items-center justify-center text-[#5C5C5C]">
                        <a href="https://www.google.com/maps/search/{{ urlencode($destination->name) }}" target="_blank"
                           class="text-[#8B5A2B] underline">View on Google Maps →</a>
                    </div>
                </div>

                {{-- Related Stories --}}
                @if(isset($relatedStories) && $relatedStories->count())
                    <div class="bg-white rounded-2xl border border-black/5 p-5">
                        <h3 class="font-semibold mb-3">Stories from {{ $destination->name }}</h3>
                        @foreach($relatedStories as $story)
                            <a href="{{ route('blog.show', $story->id) }}" class="block mb-2 hover:text-[#8B5A2B]">
                                {{ $story->title }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>