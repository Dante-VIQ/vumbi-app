<?php

use Livewire\Component;
use App\Models\Blog;
use App\Models\Doctor;
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
        $this->placeName = ucwords($this->search);

        $this->stories = $this->fetchStories();
        $this->hotels = $this->fetchHotels();
        $this->tours = $this->fetchTours();
        $this->flights = $this->fetchFlights();
        $this->awinOffers = $this->fetchAwinOffers();

        $this->loading = false;
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
<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">
    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden pt-24 pb-12 md:pt-32 md:pb-16">
        <div class="absolute inset-0 grain opacity-[0.03]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(139,90,43,0.04),transparent_50%)]"></div>

        <div class="relative container mx-auto px-6 text-center max-w-4xl">
            <span class="inline-flex items-center gap-2 text-sm bg-white/70 backdrop-blur-sm border border-white/40 px-4 py-2 rounded-full shadow-sm">
                <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
                Vumbi Discovery Engine
            </span>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold mt-6 leading-tight tracking-tight">
                Discover <span class="text-[#8B5A2B] relative inline-block">
                    Africa
                    <span class="absolute -bottom-1 left-0 w-full h-1 bg-[#D98C5F]/30 rounded-full"></span>
                </span>
            </h1>

            <p class="mt-4 text-[#5C5C5C] text-lg max-w-2xl mx-auto">
                Search destinations, hotels, tours, and stories — all in one place.
            </p>

            <form wire:submit.prevent="searchPlace" class="mt-8 flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto">
                <input type="text"
                    wire:model="search"
                    placeholder="Search Maasai Mara, Diani, Lamu..."
                    class="flex-1 px-5 py-4 rounded-xl border border-black/10 bg-white shadow-sm focus:outline-none focus:border-[#8B5A2B] focus:ring-1 focus:ring-[#8B5A2B]/20 transition">

                <button type="submit"
                    class="btn-primary px-8 py-4 rounded-xl font-medium inline-flex items-center justify-center gap-2 shadow-md">
                    <span>Explore</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
            </form>
        </div>
    </section>

    {{-- ===================== LOADING STATE ===================== --}}
    @if($loading)
        <div class="container mx-auto px-6 py-16">
            <div class="text-center mb-8">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-[#8B5A2B] border-r-transparent"></div>
                <p class="mt-4 text-[#5C5C5C]">Discovering the best of {{ $placeName ?: 'Africa' }}...</p>
            </div>

            {{-- Skeleton Grid --}}
            <div class="grid md:grid-cols-3 gap-6">
                @for($i = 0; $i < 6; $i++)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-black/5">
                        <div class="h-40 bg-[#E8DFD5] rounded-xl animate-pulse"></div>
                        <div class="mt-4 h-4 bg-[#E8DFD5] rounded animate-pulse"></div>
                        <div class="mt-2 h-3 w-2/3 bg-[#E8DFD5] rounded animate-pulse"></div>
                    </div>
                @endfor
            </div>
        </div>

    {{-- ===================== INITIAL STATE (Before Search) ===================== --}}
    @elseif(empty($placeName))

        {{-- Curated Destinations --}}
        <section class="container mx-auto px-6 py-12">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-semibold">Handpicked Destinations</h2>
                    <p class="text-[#5C5C5C] mt-1">Start your journey with our curated picks</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($destinations as $destination)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-lg transition duration-300">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ Storage::url($destination->media_path) }}"
                                alt="{{ $destination->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                loading="lazy">
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-lg">{{ $destination->name }}</h3>
                            <p class="text-sm text-[#5C5C5C] mt-1 line-clamp-2">{{ Str::limit($destination->detail, 90) }}</p>
                            <button wire:click="exploreDestination('{{ $destination->name }}')"
                                class="mt-4 w-full btn-primary py-2.5 rounded-lg text-sm font-medium">
                                Explore {{ $destination->name }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-[#6B6B6B]">No destinations yet.</div>
                @endforelse
            </div>
        </section>

        {{-- Cultural Stories --}}
        <section class="container mx-auto px-6 py-12">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-semibold">Cultural Encounters</h2>
                    <p class="text-[#5C5C5C] mt-1">Stories that shape the continent</p>
                </div>
                <a href="/field-notes" class="text-[#8B5A2B] font-medium hover:underline">View all →</a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($cultureEntries as $culture)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-lg transition duration-300">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ Storage::url($culture->image) }}"
                                alt="{{ $culture->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                loading="lazy">
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-lg">{{ $culture->name }}</h3>
                            <p class="text-sm text-[#5C5C5C] mt-1 line-clamp-2">{{ Str::limit($culture->detail, 90) }}</p>
                            <a href="{{ route('culture.show', $culture->id) }}" class="inline-block mt-3 text-sm font-medium text-[#8B5A2B] hover:underline">
                                Read more →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-[#6B6B6B]">No culture stories yet.</div>
                @endforelse
            </div>
        </section>

    {{-- ===================== SEARCH RESULTS ===================== --}}
    @else
        <div class="container mx-auto px-6 py-8">
            <div class="mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold">
                    Results for <span class="text-[#8B5A2B]">"{{ $placeName }}"</span>
                </h2>
                <p class="text-[#5C5C5C] mt-1">
                    {{ count($hotels) + count($tours) + count($flights) + count($stories) + count($awinOffers) }} discoveries found
                </p>
            </div>

            {{-- Hotels Section --}}
            @if(count($hotels))
                <section class="mb-12">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <span>🏨 Hotels & Stays</span>
                        <span class="text-sm font-normal text-[#5C5C5C]">{{ count($hotels) }} options</span>
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($hotels as $hotel)
                            <div class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-lg transition">
                                @if(!empty($hotel['image']))
                                    <div class="aspect-[4/3] overflow-hidden">
                                        <img src="{{ $hotel['image'] }}"
                                            alt="{{ $hotel['name'] }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                            loading="lazy">
                                    </div>
                                @endif
                                <div class="p-5">
                                    <h4 class="font-semibold text-lg">{{ $hotel['name'] }}</h4>
                                    @if(!empty($hotel['price']))
                                        <p class="text-[#8B5A2B] font-medium mt-1">{{ $hotel['price'] }}</p>
                                    @endif
                                    @if(!empty($hotel['rating']))
                                        <div class="flex items-center gap-1 mt-2 text-sm">
                                            <span class="text-yellow-500">★</span>
                                            <span>{{ $hotel['rating'] }}</span>
                                        </div>
                                    @endif
                                    <a href="{{ $hotel['url'] ?? '#' }}"
                                        target="_blank"
                                        rel="nofollow sponsored"
                                        class="block w-full mt-4 btn-primary py-2.5 rounded-lg text-center text-sm font-medium">
                                        View Deal →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Tours & Experiences --}}
            @if(count($tours))
                <section class="mb-12">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <span>🎯 Tours & Experiences</span>
                        <span class="text-sm font-normal text-[#5C5C5C]">{{ count($tours) }} options</span>
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tours as $tour)
                            <div class="bg-white rounded-2xl p-5 border border-black/5 shadow-sm hover:shadow-lg transition">
                                <h4 class="font-semibold text-lg">{{ $tour['name'] }}</h4>
                                @if(!empty($tour['price']))
                                    <p class="text-[#8B5A2B] font-medium mt-1">{{ $tour['price'] }}</p>
                                @endif
                                @if(!empty($tour['duration']))
                                    <p class="text-sm text-[#5C5C5C] mt-2">{{ $tour['duration'] }}</p>
                                @endif
                                <a href="{{ $tour['url'] ?? '#' }}"
                                    target="_blank"
                                    rel="nofollow sponsored"
                                    class="inline-block mt-4 text-sm font-medium text-[#8B5A2B] hover:underline">
                                    Book Experience →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Flights --}}
            @if(count($flights))
                <section class="mb-12">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <span>✈️ Flights</span>
                        <span class="text-sm font-normal text-[#5C5C5C]">{{ count($flights) }} options</span>
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($flights as $flight)
                            <div class="bg-white rounded-2xl p-5 border border-black/5 shadow-sm hover:shadow-lg transition">
                                <h4 class="font-semibold">{{ $flight['airline'] ?? 'Flight' }}</h4>
                                <p class="text-sm text-[#5C5C5C] mt-1">
                                    {{ $flight['from'] ?? '' }} → {{ $flight['to'] ?? '' }}
                                </p>
                                @if(!empty($flight['price']))
                                    <p class="text-[#8B5A2B] font-semibold mt-2">{{ $flight['price'] }}</p>
                                @endif
                                @if(!empty($flight['url']))
                                    <a href="{{ $flight['url'] }}"
                                        target="_blank"
                                        rel="nofollow sponsored"
                                        class="inline-block mt-3 text-sm font-medium text-[#8B5A2B] hover:underline">
                                        Check Flights →
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Awin Offers (General Deals) --}}
            @if(count($awinOffers))
                <section class="mb-12">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <span>🎁 Special Offers</span>
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($awinOffers as $offer)
                            <div class="bg-white rounded-2xl p-5 border border-black/5 shadow-sm hover:shadow-lg transition">
                                <h4 class="font-semibold">{{ $offer['title'] ?? 'Limited Deal' }}</h4>
                                <p class="text-sm text-[#5C5C5C] mt-1">{{ $offer['description'] ?? '' }}</p>
                                <a href="{{ $offer['url'] ?? '#' }}"
                                    target="_blank"
                                    rel="nofollow sponsored"
                                    class="inline-block mt-3 text-sm font-medium text-[#8B5A2B] hover:underline">
                                    Get Offer →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Stories --}}
            @if(count($stories))
                <section class="mb-12">
                    <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <span>📖 Related Stories</span>
                    </h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($stories as $story)
                            <a href="{{ route('blog.show', $story->id) }}"
                                class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-lg transition block">
                                @if($story->media_path)
                                    <div class="aspect-[4/3] overflow-hidden">
                                        <img src="{{ Storage::url($story->media_path) }}"
                                            alt="{{ $story->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                            loading="lazy">
                                    </div>
                                @endif
                                <div class="p-5">
                                    <h4 class="font-semibold text-lg group-hover:text-[#8B5A2B] transition">{{ $story->title }}</h4>
                                    <p class="text-sm text-[#5C5C5C] mt-2 line-clamp-2">{{ Str::limit(strip_tags($story->description), 100) }}</p>
                                    <span class="inline-block mt-3 text-sm font-medium text-[#8B5A2B]">Read story →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- No Results --}}
            @if(empty($hotels) && empty($tours) && empty($flights) && empty($stories) && empty($awinOffers))
                <div class="text-center py-16">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold">No results found</h3>
                    <p class="text-[#5C5C5C] mt-2">Try a different search term or browse our curated picks above.</p>
                    <button wire:click="$set('placeName', '')" class="mt-6 text-[#8B5A2B] font-medium hover:underline">
                        ← Back to all destinations
                    </button>
                </div>
            @endif
        </div>
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