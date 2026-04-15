<?php

use Livewire\Component;
use App\Models\Blog;
use App\Models\Doctor;
use App\Models\Culture;
use App\Services\TravelPayoutsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

new class extends Component {

   public $search = '';
    public $location = null;
    public $placeName = '';
    public $stories = [];
    public $hotels = [];
    public $attractions = [];
    public $nearbyGems = [];
    public $loading = false;

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
        $this->validate();
        $this->loading = true;
        $this->resetResults();

        // Geocode
        $geo = $this->geocode($this->search);
        if (!$geo) {
            $this->loading = false;
            $this->dispatch('notify', ['message' => 'Location not found.']);
            return;
        }

        $this->location = $geo['coordinates'];
        $this->placeName = $geo['formatted_address'];

        // Fetch data
        $this->stories = $this->fetchStories();
        $this->hotels = $this->fetchHotels();
        $this->attractions = $this->fetchAttractions(5000, false);
        $this->nearbyGems = $this->fetchAttractions(30000, true);

        $this->loading = false;
    }

    private function resetResults()
    {
        $this->stories = [];
        $this->hotels = [];
        $this->attractions = [];
        $this->nearbyGems = [];
    }

    private function geocode($place)
    {
        $apiKey = config('services.google.maps_api_key');
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $place,
            'key' => $apiKey,
        ]);
        if ($response->successful() && count($response['results']) > 0) {
            $result = $response['results'][0];
            return [
                'coordinates' => [
                    'lat' => $result['geometry']['location']['lat'],
                    'lng' => $result['geometry']['location']['lng'],
                ],
                'formatted_address' => $result['formatted_address'],
            ];
        }
        return null;
    }

    private function fetchStories()
    {
        $search = $this->search;
        $stories = Blog::where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhere('category', 'like', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        // add recommended flag from config if needed
        $recommendedIds = config('vumbi.recommended_story_ids', []);
        foreach ($stories as $story) {
            $story->is_recommended = in_array($story->id, $recommendedIds);
        }
        return $stories;
    }

    private function fetchHotels()
    {
        $service = app(TravelPayoutsService::class);
        return $service->searchHotels($this->placeName, limit: 4);
    }

    private function fetchAttractions($radius = 5000, $onlyOverlooked = false)
    {
        $apiKey = config('services.google.maps_api_key');
        $types = 'tourist_attraction|point_of_interest|natural_feature';
        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
            'location' => $this->location['lat'] . ',' . $this->location['lng'],
            'radius' => $radius,
            'type' => $types,
            'key' => $apiKey,
        ]);
        if (!$response->successful()) return [];

        $places = collect($response['results']);
        if ($onlyOverlooked) {
            $places = $places->filter(function ($place) {
                $totalRatings = $place['user_ratings_total'] ?? 0;
                $rating = $place['rating'] ?? 0;
                return $totalRatings < 10 || $rating < 3.5;
            });
        }
        $recommendedIds = config('vumbi.recommended_place_ids', []);
        return $places->take(6)->map(function ($place) use ($recommendedIds) {
            return [
                'name' => $place['name'],
                'address' => $place['vicinity'] ?? '',
                'rating' => $place['rating'] ?? null,
                'total_ratings' => $place['user_ratings_total'] ?? 0,
                'is_recommended' => in_array($place['place_id'], $recommendedIds),
                'photo' => isset($place['photos'][0]['photo_reference'])
                    ? 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' . $place['photos'][0]['photo_reference'] . '&key=' . config('services.google.maps_api_key')
                    : null,
            ];
        });
    }


};
?>

<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-light text-raw-linen mb-4">Discover <span class="text-sunflare">Africa</span></h1>
        <p class="text-xl text-[#C4B9A6]">Search for a place – get stories, hotels, and things to do.</p>
    </div>

    <div class="max-w-2xl mx-auto mb-12">
        <form wire:submit.prevent="searchPlace" class="flex gap-3">
            <input type="text" wire:model="search" placeholder="e.g., Nakuru, Masai Mara, Zanzibar"
                class="flex-1 bg-transparent border border-dust-mite p-4 text-raw-linen focus:border-sunflare focus:outline-none rounded-lg">
            <button type="submit" class="bg-terracotta px-8 py-4 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition rounded-lg">
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
            <h2 class="text-3xl font-light text-raw-linen mb-8 text-center">Explore <span class="text-sunflare">Destinations</span></h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($destinations as $dest)
                <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-lg overflow-hidden hover:border-sunflare transition">
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
            <h2 class="text-3xl font-light text-raw-linen mb-8 text-center"><span class="text-sunflare">Culture</span> Stories</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cultureEntries as $culture)
                <a href="{{ route('culture.show', $culture->id) }}" class="group block border border-dust-mite hover:border-sunflare rounded-lg overflow-hidden bg-indigo-night bg-opacity-30 transition">
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

        @if(count($stories) > 0)
        <section class="mb-16">
            <h3 class="text-2xl font-light text-raw-linen mb-6 flex items-center gap-2"><i class="fas fa-book-open text-sunflare"></i> Related Stories</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stories as $story)
                <a href="{{ route('blog.show', $story->id) }}" class="group block border border-dust-mite hover:border-sunflare rounded-lg overflow-hidden bg-indigo-night bg-opacity-30 transition">
                    @if($story->media_path && $story->media_type === 'image')
                        <img src="{{ Storage::url($story->media_path) }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-xl font-light text-raw-linen group-hover:text-sunflare">{{ $story->title }}</h4>
                            @if($story->is_recommended)
                                <span class="bg-sunflare text-deep-earth text-xs px-2 py-1 rounded-full"><i class="fas fa-star"></i> Vumbi Pick</span>
                            @endif
                        </div>
                        <p class="text-sm text-[#C4B9A6]">{{ Str::limit(strip_tags($story->description), 100) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        @if(count($hotels) > 0)
        <section class="mb-16">
            <h3 class="text-2xl font-light text-raw-linen mb-6 flex items-center gap-2"><i class="fas fa-hotel text-sunflare"></i> Places to Stay</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hotels as $hotel)
                <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-lg overflow-hidden">
                    @if($hotel['image'])
                        <img src="{{ $hotel['image'] }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <h4 class="text-xl font-light text-raw-linen">{{ $hotel['name'] }}</h4>
                        <p class="text-sm text-[#C4B9A6]">{{ $hotel['price'] }}</p>
                        <a href="{{ $hotel['url'] }}" target="_blank" rel="nofollow sponsored" class="mt-3 inline-block bg-sunflare text-deep-earth px-4 py-2 rounded text-sm font-medium hover:bg-raw-linen transition">
                            View & Book →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if(count($attractions) > 0)
        <section class="mb-16">
            <h3 class="text-2xl font-light text-raw-linen mb-6 flex items-center gap-2"><i class="fas fa-map-marker-alt text-sunflare"></i> Things to Do</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($attractions as $attraction)
                <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-lg overflow-hidden">
                    @if($attraction['photo'])
                        <img src="{{ $attraction['photo'] }}" class="w-full h-40 object-cover">
                    @endif
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="text-lg font-medium text-raw-linen">{{ $attraction['name'] }}</h4>
                            @if($attraction['is_recommended'])
                                <span class="bg-sunflare text-deep-earth text-xs px-2 py-1 rounded-full">Vumbi Pick</span>
                            @endif
                        </div>
                        <p class="text-sm text-[#C4B9A6]">{{ $attraction['address'] }}</p>
                        @if($attraction['rating'])
                            <div class="flex items-center gap-1 mt-2">
                                <i class="fas fa-star text-sunflare text-sm"></i>
                                <span class="text-sm text-raw-linen">{{ $attraction['rating'] }} ({{ $attraction['total_ratings'] }} reviews)</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if(count($nearbyGems) > 0)
        <section class="mb-16 border-t border-dust-mite pt-12">
            <h3 class="text-2xl font-light text-raw-linen mb-2 flex items-center gap-2"><i class="fas fa-gem text-sunflare"></i> Hidden Gems Nearby</h3>
            <p class="text-[#C4B9A6] mb-6">Off-the-beaten-path places within 30km of {{ $placeName }}</p>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($nearbyGems as $gem)
                <div class="bg-indigo-night bg-opacity-30 border border-dust-mite rounded-lg overflow-hidden hover:border-sunflare transition">
                    @if($gem['photo'])
                        <img src="{{ $gem['photo'] }}" class="w-full h-40 object-cover">
                    @endif
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="text-lg font-medium text-raw-linen">{{ $gem['name'] }}</h4>
                            @if($gem['is_recommended'])
                                <span class="bg-sunflare text-deep-earth text-xs px-2 py-1 rounded-full">Vumbi Pick</span>
                            @endif
                        </div>
                        <p class="text-sm text-[#C4B9A6]">{{ $gem['address'] }}</p>
                        @if($gem['rating'])
                            <div class="flex items-center gap-1 mt-2">
                                <i class="fas fa-star text-sunflare text-sm"></i>
                                <span class="text-sm text-raw-linen">{{ $gem['rating'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    @endif
</div>