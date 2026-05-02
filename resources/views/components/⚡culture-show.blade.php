<?php

use Livewire\Component;
use App\Models\Culture;
use App\Models\Destination;
use App\Services\AffiliateMatcher;
use App\Services\AffiliateExecutionService;

new class extends Component
{
    public Culture $culture;
    public $relatedCultures = [];
    public $nearbyDestinations = [];
    public $affiliateOffers = [];

    public function mount(Culture $culture)
    {
        $this->culture = $culture;

        // Related cultural stories (same category or location)
        $this->relatedCultures = Culture::where('id', '!=', $culture->id)
            ->where(function ($q) use ($culture) {
                $q->where('location', $culture->location)
                  ->orWhere('name', 'like', '%' . $culture->location . '%');
            })
            ->latest()
            ->limit(3)
            ->get();

        // Nearby destinations for travel CTA
        $this->nearbyDestinations = Destination::where('location', 'like', '%' . $culture->location . '%')
            ->orWhere('name', 'like', '%' . $culture->location . '%')
            ->limit(3)
            ->get();

        // Affiliate offers based on location
        $this->affiliateOffers = $this->fetchAffiliateOffers($culture->location);
    }

    private function fetchAffiliateOffers($location)
    {
        // Use your existing affiliate services
        try {
            $matcher = app(AffiliateMatcher::class);
            $plan = method_exists($matcher, 'match') ? $matcher->match($location) : [];
            if (empty($plan)) {
                return [];
            }
            return app(AffiliateExecutionService::class)->execute($plan, $location);
        } catch (\Exception $e) {
            return [];
        }
    }
};
?>

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">
    {{-- SEO Meta --}}
    @section('title', $culture->name . ' — Cultural Story | Vumbi Ventures')
    @section('meta_description', Str::limit(strip_tags($culture->detail), 155))

    @push('meta')
        <meta property="og:title" content="{{ $culture->name }} — Vumbi Ventures">
        <meta property="og:description" content="{{ Str::limit(strip_tags($culture->detail), 155) }}">
        <meta property="og:image" content="{{ Storage::url($culture->image) }}">
        <meta property="og:type" content="article">
        <link rel="canonical" href="{{ url()->current() }}">
    @endpush

    {{-- @push('structured-data')
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Article",
          "headline": "{{ $culture->name }}",
          "image": "{{ Storage::url($culture->image) }}",
          "description": "{{ Str::limit(strip_tags($culture->detail), 155) }}",
          "author": { "@type": "Organization", "name": "Vumbi Ventures" },
          "publisher": { "@type": "Organization", "name": "Vumbi Ventures", "logo": { "@type": "ImageObject", "url": "{{ asset('images/logo.png') }}" } },
          "datePublished": "{{ $culture->created_at->toIso8601String() }}"
        }
        </script>
    @endpush --}}

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-24 pb-8 md:pt-32 md:pb-12">
        <div class="absolute inset-0 grain opacity-[0.03]"></div>
        <div class="container mx-auto px-6 max-w-4xl relative">
            <nav class="flex items-center gap-2 text-sm text-[#5C5C5C] mb-6">
                <a href="{{ url('/') }}" class="hover:text-[#8B5A2B]">Home</a>
                <span>/</span>
                <a href="{{ url('/discover') }}" class="hover:text-[#8B5A2B]">Discover</a>
                <span>/</span>
                <span class="text-[#8B5A2B]">Culture</span>
            </nav>

            <span class="inline-block text-sm bg-white/70 backdrop-blur-sm border border-white/40 px-4 py-2 rounded-full shadow-sm mb-6">
                📍 {{ $culture->location ?? 'Africa' }}
            </span>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold leading-tight tracking-tight text-[#1A1A1A] mb-6">
                {{ $culture->name }}
            </h1>
        </div>
    </section>

    {{-- Featured Image --}}
    @if($culture->image)
        <div class="container mx-auto px-6 mb-12">
            <div class="max-w-5xl mx-auto">
                <img src="{{ Storage::url($culture->image) }}" alt="{{ $culture->name }}"
                    class="w-full rounded-3xl shadow-xl object-cover max-h-[500px] border border-black/5">
            </div>
        </div>
    @endif

    {{-- Content + Sidebar --}}
    <div class="container mx-auto px-6 py-8">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
            {{-- Main Content --}}
            <div class="lg:col-span-8">
                <article class="prose prose-lg max-w-none
                    prose-p:text-[#3A3A3A] prose-p:leading-relaxed
                    prose-a:text-[#8B5A2B] hover:prose-a:underline">
                    {!! nl2br(e($culture->detail)) !!}
                </article>

                {{-- Share --}}
                <div class="mt-10 pt-6 border-t border-black/5 flex items-center gap-4">
                    <span class="text-sm text-[#5C5C5C]">Share this story:</span>
                    <div class="flex gap-2">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($culture->name) }}&url={{ url()->current() }}"
                           target="_blank" class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                           target="_blank" class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-8">
                {{-- Primary CTA: Book Travel to this Location --}}
                <div class="bg-gradient-to-br from-[#8B5A2B] to-[#5C3A1E] p-6 rounded-2xl text-white shadow-lg">
                    <h3 class="text-xl font-semibold mb-2">Experience {{ $culture->location ?? 'Africa' }}</h3>
                    <p class="text-white/80 text-sm mb-4">Discover hotels, tours, and cultural experiences in this region.</p>
                    <a href="{{ url('/discover?search='.urlencode($culture->location)) }}"
                       class="inline-block w-full bg-white text-[#8B5A2B] text-center py-3 rounded-xl font-medium hover:bg-[#F5EFE6] transition">
                        Explore {{ $culture->location }} →
                    </a>
                </div>

                {{-- Nearby Destinations --}}
                @if($nearbyDestinations->count())
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4">Nearby Destinations</h3>
                        <div class="space-y-4">
                            @foreach($nearbyDestinations as $dest)
                                <a href="{{ route('destination.show', $dest->id) }}" class="flex gap-3 group">
                                    @if($dest->media_path)
                                        <img src="{{ Storage::url($dest->media_path) }}" class="w-16 h-16 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <h4 class="font-medium group-hover:text-[#8B5A2B]">{{ $dest->name }}</h4>
                                        <p class="text-xs text-[#5C5C5C]">{{ $dest->location }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Affiliate Offers --}}
                @if(!empty($affiliateOffers))
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4">Travel Deals</h3>
                        @foreach($affiliateOffers as $offer)
                            <a href="{{ $offer['url'] }}" target="_blank" class="block p-3 bg-[#FCFAF7] rounded-xl mb-2 hover:shadow">
                                <p class="font-medium">{{ $offer['title'] }}</p>
                                <p class="text-xs text-[#5C5C5C]">{{ $offer['price'] ?? '' }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Related Cultural Stories --}}
                @if($relatedCultures->count())
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4">More Cultural Stories</h3>
                        @foreach($relatedCultures as $related)
                            <a href="{{ route('culture.show', $related->id) }}" class="block mb-3 group">
                                <h4 class="font-medium group-hover:text-[#8B5A2B]">{{ $related->name }}</h4>
                                <p class="text-xs text-[#5C5C5C]">{{ $related->location }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>

@push('styles')
<style>
    .grain { background-image: url("https://grainy-gradients.vercel.app/noise.svg"); opacity: 0.03; }
</style>
@endpush