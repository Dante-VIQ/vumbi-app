<?php

use Livewire\Component;
use App\Models\Blog;
use App\Models\Doctor;
use App\Models\Culture;
use App\Services\TravelPayoutsService;
use App\Services\BonusArriveService;
use App\Services\AwinService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

new class extends Component {

    public $search = '';
    public $placeName = '';
    public $loading = false;

    public $stories = [];
    public $hotels = [];
    public $tours = [];
    public $flights = [];
    public $awinOffers = [];
    public $recommended = [];

    // For initial browse sections
    public $destinations = [];
    public $cultureEntries = [];

    protected $rules = ['search' => 'required|string|min:2'];

    public function mount()
    {
        $this->destinations = Doctor::latest()->limit(6)->get();
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
        $this->resetResults();

        $this->placeName = ucwords(trim($this->search));

        // Run all APIs in parallel (faster)
        $this->stories = $this->fetchStories();
        $this->hotels = $this->fetchHotels();
        $this->tours = $this->fetchTours();
        $this->flights = $this->fetchFlights();
        $this->awinOffers = $this->fetchAwinOffers();

        // Combine and rank recommendations
        $this->recommended = $this->combineRecommendations();

        $this->loading = false;
    }

    private function resetResults()
    {
        $this->stories = [];
        $this->hotels = [];
        $this->tours = [];
        $this->flights = [];
        $this->awinOffers = [];
        $this->recommended = [];
    }

    private function fetchStories()
    {
        return Blog::where('title', 'like', "%{$this->search}%")
            ->orWhere('description', 'like', "%{$this->search}%")
            ->latest()->limit(4)->get();
    }

    private function fetchHotels()
    {
        try {
            $service = app(TravelPayoutsService::class);
            return $service->searchHotels($this->search, 5);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchTours()
    {
        try {
            $service = app(TravelPayoutsService::class);
            return $service->searchTours($this->search, 5);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchFlights()
    {
        try {
            $service = app(BonusArriveService::class);
            return $service->searchFlights($this->search);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchAwinOffers()
    {
        try {
            $service = app(AwinService::class);
            return $service->searchOffers($this->search);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function combineRecommendations()
    {
        $combined = collect();

        // Add hotels
        foreach ($this->hotels as $item)
            $combined->push(['type' => 'hotel', 'data' => $item]);
        // Add tours
        foreach ($this->tours as $item)
            $combined->push(['type' => 'tour', 'data' => $item]);
        // Add flights
        foreach ($this->flights as $item)
            $combined->push(['type' => 'flight', 'data' => $item]);
        // Add Awin offers
        foreach ($this->awinOffers as $item)
            $combined->push(['type' => 'awin', 'data' => $item]);

        return $combined->take(8);
    }


};
?>

<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-light text-raw-linen mb-4">Discover <span
                class="text-sunflare">Africa</span></h1>
        <p class="text-xl text-[#C4B9A6]">Search for a place – get stories, hotels, and things to do.</p>
    </div>

    <!-- Search Bar -->
    <div class="max-w-2xl mx-auto mb-16">
        <form wire:submit.prevent="searchPlace" class="flex gap-3">
            <input type="text" wire:model="search"
                placeholder="Search any place... (e.g. Maasai Mara, Diani, Nakuru, Lamu)"
                class="flex-1 bg-transparent border border-dust-mite px-6 py-5 text-raw-linen rounded-2xl focus:border-sunflare focus:outline-none text-lg">
            <button type="submit"
                class="bg-terracotta hover:bg-sunflare px-10 py-5 rounded-2xl text-raw-linen font-medium transition flex items-center gap-2">
                <i class="fas fa-search"></i>
                Explore
            </button>
        </form>
    </div>

    @if($loading)
        <div class="text-center py-12">
            <i class="fas fa-spinner fa-spin text-4xl text-sunflare"></i>
            <p class="text-[#C4B9A6] mt-4">Discovering...</p>
        </div>
    @endif

    {{-- Before search: show destinations and culture --}}
    @if(empty($placeName) && !$loading)
        @if($destinations->count())
            <section class="mb-16">
                <h2 class="text-3xl font-light text-raw-linen mb-8 text-center">Explore <span
                        class="text-sunflare">Destinations</span></h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($destinations as $dest)
                        <div
                            class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-lg overflow-hidden hover:border-sunflare transition">
                            @if($dest->media_path)
                                <img src="{{ Storage::url($dest->media_path) }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-light text-raw-linen">{{ $dest->name }}</h3>
                                <p class="text-sm text-[#C4B9A6] mt-2">{{ Str::limit($dest->detail, 100) }}</p>
                                <button wire:click="exploreDestination('{{ addslashes($dest->name) }}')"
                                    class="mt-4 bg-terracotta text-raw-linen px-4 py-2 rounded text-sm hover:bg-sunflare hover:text-deep-earth transition">
                                    Explore →
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($cultureEntries->count())
            <section class="mb-16">
                <h2 class="text-3xl font-light text-raw-linen mb-8 text-center"><span class="text-sunflare">Culture</span>
                    Stories</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($cultureEntries as $culture)
                        <a href="#"
                            class="group block border border-dust-mite hover:border-sunflare rounded-lg overflow-hidden bg-indigo-night bg-opacity-30 transition">
                            @if($culture->image)
                                <img src="{{ Storage::url($culture->image) }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <h4 class="text-xl font-light text-raw-linen group-hover:text-sunflare">{{ $culture->name }}</h4>
                                @if($culture->location)
                                    <p class="text-sm text-[#C4B9A6] mt-1"><i class="fas fa-map-pin"></i> {{ $culture->location }}</p>
                                @endif
                                <p class="text-sm text-[#C4B9A6] mt-2">{{ Str::limit($culture->detail, 100) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endif

    {{-- After search: show results --}}
    @if(!empty($placeName) && !$loading)
        <div class="mb-8">
            <h2 class="text-2xl text-sunflare mb-2">Results for: {{ $placeName }}</h2>
        </div>

        <!-- Hotels -->
        @if(count($hotels) > 0)
            <section class="mb-16">
                <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                    <i class="fas fa-hotel"></i> Hotels & Lodges
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($hotels as $hotel)
                        <div
                            class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                            @if($hotel['image'] ?? false)
                                <img src="{{ $hotel['image'] }}" class="w-full h-52 object-cover">
                            @endif
                            <div class="p-6">
                                <h4 class="font-medium text-raw-linen">{{ $hotel['name'] ?? 'Luxury Stay' }}</h4>
                                <p class="text-sm text-[#C4B9A6]">{{ $hotel['price'] ?? 'Best rates' }}</p>
                                <a href="{{ $hotel['url'] ?? '#' }}" target="_blank"
                                    class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium">
                                    View & Book →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Tours & Activities -->
        @if(count($tours) > 0)
            <section class="mb-16">
                <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                    <i class="fas fa-compass"></i> Tours & Experiences
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tours as $tour)
                        <div
                            class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                            <div class="p-6">
                                <h4 class="font-medium text-raw-linen">{{ $tour['name'] ?? 'Guided Tour' }}</h4>
                                <p class="text-sm text-[#C4B9A6]">{{ $tour['price'] ?? '' }}</p>
                                <a href="{{ $tour['url'] ?? '#' }}" target="_blank"
                                    class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium">
                                    Book Experience →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Flights (Bonus Arrive) -->
        @if(count($flights) > 0)
            <section class="mb-16">
                <h3 class="text-2xl text-sunflare mb-6 flex items-center gap-3">
                    <i class="fas fa-plane"></i> Flight Deals
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($flights as $flight)
                        <div
                            class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl overflow-hidden hover:border-sunflare transition">
                            <div class="p-6">
                                <h4 class="font-medium text-raw-linen">{{ $flight['airline'] ?? 'Flight Deal' }}</h4>
                                <p class="text-sm text-[#C4B9A6]">
                                    {{ $flight['from'] ?? '' }} → {{ $flight['to'] ?? $placeName }}
                                </p>
                                <p class="text-lg font-medium text-sunflare mt-1">{{ $flight['price'] ?? 'Best price' }}</p>
                                <a href="{{ $flight['link'] ?? '#' }}" target="_blank"
                                    class="mt-5 block text-center bg-sunflare text-deep-earth py-3 rounded-2xl text-sm font-medium">
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
                        <div
                            class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-3xl p-6 hover:border-sunflare transition">
                            <span class="text-xs uppercase tracking-widest text-terracotta">Awin</span>
                            <h4 class="font-medium text-raw-linen mt-2">{{ $offer['title'] }}</h4>
                            <p class="text-sm text-[#C4B9A6] mt-2">{{ $offer['description'] }}</p>
                            <a href="{{ $offer['link'] }}" target="_blank"
                                class="mt-5 inline-block bg-sunflare text-deep-earth px-6 py-3 rounded-2xl text-sm font-medium">
                                Claim Offer →
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
                            class="group bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-3xl overflow-hidden">
                            @if($story->media_path)
                                <img src="{{ Storage::url($story->media_path) }}" class="w-full h-52 object-cover">
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
    @endif
</div>