<?php

use Livewire\Component;
use App\Models\Blog;
use App\Models\Destination;
use App\Models\Culture;
use App\Services\TravelPayoutsService;
use App\Services\BonusArriveService;
use App\Services\AwinService;

new class extends Component {

    public $search = '';
    public $placeName = '';
    public $loading = false;

    public $stories = [];
    public $hotels = [];
    public $tours = [];
    public $flights = [];
    public $awinOffers = [];

    public $destinations = [];
    public $cultureEntries = [];
    public $placeInfo = null;
    public $attractions = [];

    public function mount()
    {
        $this->destinations = Destination::latest()->limit(6)->get();
        $this->cultureEntries = Culture::latest()->limit(6)->get();
    }

    public function exploreDestination($name)
    {
        $this->search = $name;
        $this->searchPlace();
    }

    public function searchPlace()
    {
        $this->validate(['search' => 'required|string|min:2']);

        $this->loading = true;
        $this->placeName = ucwords($this->search);

        $this->stories = $this->fetchStories();
        $this->hotels = $this->fetchHotels();
        $this->tours = $this->fetchTours();
        $this->flights = $this->fetchFlights();
        $this->awinOffers = $this->fetchAwinOffers();
        $this->placeInfo = $this->fetchPlaceInfo();
        $this->attractions = $this->fetchAttractions();
        $this->loading = false;
    }

    private function fetchPlaceInfo()
    {
        try {
            return app(\App\Services\PlaceDiscoveryService::class)
                ->getPlaceSummary($this->search);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function fetchAttractions()
    {
        try {
            return app(\App\Services\PlaceDiscoveryService::class)
                ->getAttractions($this->search, 6);
        } catch (\Exception $e) {
            return [];
        }
    }
    private function fetchStories()
    {
        return Blog::where('title', 'like', "%{$this->search}%")
            ->orWhere('description', 'like', "%{$this->search}%")
            ->latest()->limit(6)->get();
    }

    private function fetchHotels()
    {
        try {
            return app(TravelPayoutsService::class)->searchHotels($this->search, 6);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchTours()
    {
        try {
            return app(TravelPayoutsService::class)->searchTours($this->search, 6);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchFlights()
    {
        try {
            return app(BonusArriveService::class)->searchFlights($this->search, 5);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchAwinOffers()
    {
        try {
            return app(AwinService::class)->searchOffers($this->search, 6);
        } catch (\Exception $e) {
            return [];
        }
    }
};
?>
<div class="bg-deep-earth min-h-screen">
    
    <!-- Hero Section -->
    <section class="pt-24 pb-16 border-b border-dust-mite">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-light leading-tight text-raw-linen mb-6">
                    Discover <span class="text-sunflare">Africa</span>
                </h1>
                <p class="text-xl text-[#C4B9A6] max-w-2xl mx-auto">
                    Search any destination and instantly get real stories, hotels, tours, flights, and exclusive offers.
                </p>
            </div>

            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mt-12">
                <form wire:submit.prevent="searchPlace" class="flex gap-3">
                    <input type="text" 
                           wire:model="search"
                           placeholder="Where are you exploring? (Maasai Mara, Diani, Nakuru, Lamu...)"
                           class="flex-1 bg-transparent border border-dust-mite focus:border-sunflare rounded-2xl px-8 py-6 text-lg text-raw-linen placeholder-[#8B7A6A] focus:outline-none">
                    <button type="submit"
                            class="bg-terracotta hover:bg-sunflare px-10 py-6 rounded-2xl text-raw-linen font-medium transition flex items-center gap-3 whitespace-nowrap">
                        <i class="fas fa-search"></i>
                        <span>Explore</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Loading State --}}
    @if($loading)
        <div class="container mx-auto px-6 py-20">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-night bg-opacity-30 mb-6">
                    <i class="fas fa-spinner fa-spin text-4xl text-sunflare"></i>
                </div>
                <p class="text-[#C4B9A6] text-lg">Discovering the best of {{ $placeName ?: 'Africa' }}...</p>
            </div>
        </div>
    @else

        {{-- Browse Mode (No Search Yet) --}}
        @if(empty($placeName))
            <div class="container mx-auto px-6 py-16">

                <!-- Popular Destinations -->
                <section class="mb-20">
                    <h2 class="text-3xl font-light text-raw-linen mb-10 text-center">Popular Destinations</h2>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($destinations as $dest)
                            <button wire:click="exploreDestination('{{ addslashes($dest->name) }}')"
                                    class="group bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden transition-all">
                                @if($dest->media_path)
                                    <img src="{{ Storage::url($dest->media_path) }}" 
                                         alt="{{ $dest->name }}"
                                         class="w-full h-56 object-cover group-hover:scale-105 transition">
                                @endif
                                <div class="p-6">
                                    <h3 class="text-2xl font-light text-raw-linen group-hover:text-sunflare">{{ $dest->name }}</h3>
                                    <p class="text-[#C4B9A6] mt-1">{{ $dest->location ?? 'Kenya' }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </section>

                <!-- Culture & Stories -->
                <section>
                    <h2 class="text-3xl font-light text-raw-linen mb-10 text-center">Culture & Stories</h2>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($cultureEntries as $culture)
                            <a href="{{ route('blog.show', $culture->id) }}" 
                               class="group bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden transition-all">
                                @if($culture->media_path)
                                    <img src="{{ Storage::url($culture->media_path) }}" 
                                         alt="{{ $culture->title ?? $culture->name }}"
                                         class="w-full h-56 object-cover group-hover:scale-105 transition">
                                @endif
                                <div class="p-6">
                                    <h3 class="text-xl font-light text-raw-linen group-hover:text-sunflare">{{ $culture->title ?? $culture->name }}</h3>
                                    <p class="text-[#C4B9A6] mt-2 line-clamp-3">{{ Str::limit(strip_tags($culture->description ?? $culture->detail), 110) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>
        @endif

        {{-- Search Results --}}
        @if(!empty($placeName))
            <div class="container mx-auto px-6 py-12">
                <div class="mb-10">
                    <h2 class="text-3xl font-light text-raw-linen">
                        Discoveries in <span class="text-sunflare">{{ $placeName }}</span>
                    </h2>
                    <p class="text-[#C4B9A6] mt-1">
                        {{ count($hotels) + count($tours) + count($flights) + count($stories) + count($awinOffers) }} results found
                    </p>
                </div>

                <!-- Hotels -->
                @if(count($hotels) > 0)
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-hotel"></i> Hotels & Lodges
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($hotels as $hotel)
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                                @if($hotel['image'])
                                    <img src="{{ $hotel['image'] }}" alt="{{ $hotel['name'] }}" class="w-full h-52 object-cover">
                                @endif
                                <div class="p-6">
                                    <h4 class="font-medium text-raw-linen">{{ $hotel['name'] }}</h4>
                                    <p class="text-sm text-[#C4B9A6]">{{ $hotel['price'] ?? 'Best rates' }}</p>
                                    <a href="{{ $hotel['url'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                       class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium hover:bg-raw-linen">
                                        View & Book →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Tours -->
                @if(count($tours) > 0)
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-compass"></i> Tours & Experiences
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tours as $tour)
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                                <div class="p-6">
                                    <h4 class="font-medium text-raw-linen">{{ $tour['name'] ?? 'Guided Experience' }}</h4>
                                    <p class="text-sm text-[#C4B9A6]">{{ $tour['price'] ?? '' }}</p>
                                    <a href="{{ $tour['url'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                       class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium hover:bg-raw-linen">
                                        Book Experience →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Flights -->
                @if(count($flights) > 0)
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-plane"></i> Flight Deals
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($flights as $flight)
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                                <div class="p-6">
                                    <h4 class="font-medium text-raw-linen">{{ $flight['airline'] ?? 'Flight Deal' }}</h4>
                                    <p class="text-sm text-[#C4B9A6]">
                                        {{ $flight['from'] ?? '' }} → {{ $flight['to'] ?? $placeName }}
                                    </p>
                                    <p class="text-lg font-medium text-sunflare mt-1">{{ $flight['price'] ?? 'Best price' }}</p>
                                    <a href="{{ $flight['link'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                       class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium hover:bg-raw-linen">
                                        Book Flight →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Awin Offers -->
                @if(count($awinOffers) > 0)
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-tag"></i> Special Offers
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($awinOffers as $offer)
                            <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl p-6 hover:border-sunflare transition">
                                <span class="text-xs uppercase tracking-widest text-terracotta">Awin</span>
                                <h4 class="font-medium text-raw-linen mt-2">{{ $offer['title'] }}</h4>
                                <p class="text-sm text-[#C4B9A6] mt-1">{{ $offer['description'] }}</p>
                                <a href="{{ $offer['link'] }}" target="_blank" rel="nofollow sponsored"
                                   class="mt-5 inline-block bg-sunflare text-deep-earth px-6 py-3 rounded-2xl text-sm font-medium">
                                    Get Offer →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Stories -->
                @if(count($stories) > 0)
                <section class="mb-16">
                    <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                        <i class="fas fa-book-open"></i> Related Stories
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($stories as $story)
                            <a href="{{ route('blog.show', $story->id) }}" 
                               class="group bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden transition-all">
                                @if($story->media_path)
                                    <img src="{{ Storage::url($story->media_path) }}" 
                                         alt="{{ $story->title }}"
                                         class="w-full h-52 object-cover">
                                @endif
                                <div class="p-6">
                                    <h4 class="text-xl font-light text-raw-linen group-hover:text-sunflare">{{ $story->title }}</h4>
                                    <p class="text-sm text-[#C4B9A6] mt-2 line-clamp-3">
                                        {{ Str::limit(strip_tags($story->description ?? $story->excerpt), 110) }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- No Results -->
                @if(empty($hotels) && empty($tours) && empty($flights) && empty($stories) && empty($awinOffers))
                    <div class="text-center py-20">
                        <div class="text-6xl mb-6">🔍</div>
                        <h3 class="text-2xl font-light text-raw-linen mb-3">No results found</h3>
                        <p class="text-[#C4B9A6]">Try searching for a different destination or browse our curated picks above.</p>
                        <button wire:click="$set('placeName', '')" 
                                class="mt-8 text-sunflare hover:text-white transition">
                            ← Back to Discover Africa
                        </button>
                    </div>
                @endif
            </div>
        @endif

    @endif
</div>

@push('styles')
    <style>
        .btn-primary {
            background: #8B5A2B;
            color: white;
            border: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1);
            box-shadow: 0 6px 14px rgba(139, 90, 43, 0.12);
        }

        .btn-primary:hover {
            background: #5C3A1E;
            transform: translateY(-2px);
            box-shadow: 0 14px 24px rgba(92, 58, 30, 0.18);
        }

        .grain {
            background-image: url("https://grainy-gradients.vercel.app/noise.svg");
            opacity: 0.035;
            pointer-events: none;
        }
    </style>
@endpush