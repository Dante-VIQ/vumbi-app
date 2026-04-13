<?php
// resources/views/livewire/culture-show.blade.php

use Livewire\Component;
use App\Models\Culture;
use App\Models\Destination;
use App\Services\AffiliateMatcher;
use App\Services\AffiliateExecutionService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    public Culture $culture;
    public array $relatedCultures = [];
    public array $nearbyDestinations = [];
    public array $affiliateOffers = [];

    public function mount(Culture $culture, $id = null, $slug = null)
    {
        if ($culture->exists) {
            $this->culture = $culture;
        } elseif ($slug) {
            $this->culture = Culture::where('slug', $slug)->firstOrFail();
        } elseif ($id) {
            $this->culture = Culture::findOrFail($id);
        } else {
            abort(404);
        }

        $location = $this->culture->location;

        $this->relatedCultures = Culture::where('id', '!=', $this->culture->id)
            ->when($location, function ($q) use ($location) {
                $q->where('location', 'like', "%{$location}%")
                  ->orWhere('name', 'like', "%{$location}%");
            })
            ->latest()
            ->limit(3)
            ->get()
            ->all();

        $this->nearbyDestinations = Destination::when($location, function ($q) use ($location) {
                $q->where('location', 'like', "%{$location}%")
                  ->orWhere('name', 'like', "%{$location}%");
            })
            ->limit(3)
            ->get()
            ->all();

        if ($location) {
            $this->affiliateOffers = $this->fetchAffiliateOffers($location);
        }
    }

    private function fetchAffiliateOffers(string $location): array
    {
        try {
            if (class_exists(AffiliateMatcher::class) && class_exists(AffiliateExecutionService::class)) {
                $matcher = app(AffiliateMatcher::class);
                $plan = method_exists($matcher, 'match') ? $matcher->match($location) : [];
                if (empty($plan)) {
                    return [];
                }
                return app(AffiliateExecutionService::class)->execute($plan, $location);
            }
            return [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Compute parsed DOM blocks for structured article rendering
     */
    public function getContentBlocksProperty(): array
    {
        $detail = $this->culture->detail ?? $this->culture->description ?? '';
        if (empty(trim($detail))) {
            return [];
        }

        // If simple prose without HTML headings, return as single block
        if (!Str::contains($detail, ['<h2', '<h3', '<p>'])) {
            return [nl2br(e($detail))];
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        
        $encodedHtml = mb_convert_encoding($detail, 'HTML-ENTITIES', 'UTF-8');
        $dom->loadHTML($encodedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $blocks = [];
        $currentBlock = '';

        if ($dom->documentElement) {
            foreach ($dom->documentElement->childNodes as $node) {
                $nodeHtml = $dom->saveHTML($node);

                if ($node->nodeName === 'h2') {
                    if (trim($currentBlock) !== '') {
                        $blocks[] = $currentBlock;
                        $currentBlock = '';
                    }
                    $blocks[] = $nodeHtml;
                    continue;
                }

                $currentBlock .= $nodeHtml;
            }
        }

        if (trim($currentBlock) !== '') {
            $blocks[] = $currentBlock;
        }

        return $blocks;
    }

    public function resolveImageUrl(?string $path): string
    {
        if (!$path) {
            return asset('images/default-culture.jpg');
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return Storage::disk('public')->exists($path) ? Storage::url($path) : asset($path);
    }
};
?>

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">
    @push('structured-data')
        @php
            $articleSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $culture->name ?? $culture->title,
                'image' => $this->resolveImageUrl($culture->image ?? $culture->media_path ?? null),
                'description' => Str::limit(strip_tags($culture->detail ?? $culture->description ?? ''), 155),
                'author' => ['@type' => 'Organization', 'name' => 'Vumbi Ventures'],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Vumbi Ventures',
                    'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
                ],
                'datePublished' => optional($culture->created_at)->toIso8601String() ?? now()->toIso8601String(),
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($articleSchema) !!}</script>
    @endpush

    {{-- Hero Section --}}
    <section class="relative overflow-hidden pt-20 pb-8 md:pt-28 md:pb-12">
        <div class="absolute inset-0 grain opacity-[0.03]"></div>
        <div class="container mx-auto px-6 max-w-4xl relative">
            <nav class="flex items-center gap-2 text-sm text-[#5C5C5C] mb-6 font-medium">
                <a href="{{ url('/') }}" class="hover:text-[#8B5A2B] transition">Home</a>
                <span>/</span>
                <a href="{{ route('cultures.index') }}" class="hover:text-[#8B5A2B] transition">Culture & Stories</a>
                <span>/</span>
                <span class="text-[#8B5A2B]">{{ $culture->name ?? $culture->title }}</span>
            </nav>

            @if($culture->location)
                <span class="inline-block text-xs font-semibold bg-white/80 backdrop-blur-sm border border-black/5 px-3.5 py-1.5 rounded-full shadow-sm mb-4 text-[#8B5A2B]">
                    📍 {{ $culture->location }}
                </span>
            @endif

            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight text-[#1A1A1A] mb-4">
                {{ $culture->name ?? $culture->title }}
            </h1>
        </div>
    </section>

    {{-- Cover Image --}}
    @php
        $coverImage = $this->resolveImageUrl($culture->image ?? $culture->media_path ?? null);
    @endphp
    @if ($coverImage)
        <div class="container mx-auto px-6 mb-10">
            <div class="max-w-4xl mx-auto">
                <img src="{{ $coverImage }}" alt="{{ $culture->name ?? $culture->title }}"
                    class="w-full rounded-3xl shadow-xl object-cover max-h-[520px] border border-black/5">
            </div>
        </div>
    @endif

    {{-- Content Body & Sidebar Grid --}}
    <div class="container mx-auto px-6 py-4 max-w-7xl">
        <div class="grid lg:grid-cols-12 gap-10">
            
            {{-- Main Story Article --}}
            <div class="lg:col-span-8">
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-black/5 shadow-sm">
                    <article class="prose prose-lg max-w-none prose-p:text-[#3A3A3A] prose-p:leading-relaxed prose-headings:text-[#1A1A1A] prose-a:text-[#8B5A2B]">
                        @if (!empty($this->contentBlocks))
                            @foreach ($this->contentBlocks as $block)
                                {!! $block !!}
                            @endforeach
                        @else
                            {!! nl2br(e($culture->detail ?? $culture->description ?? 'No narrative content available.')) !!}
                        @endif
                    </article>

                    {{-- Social Share Footer --}}
                    <div class="mt-10 pt-6 border-t border-black/5 flex items-center justify-between">
                        <span class="text-sm font-medium text-[#5C5C5C]">Share this story:</span>
                        <div class="flex gap-3">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($culture->name ?? $culture->title) }}&url={{ url()->current() }}"
                                target="_blank" rel="noopener noreferrer"
                                class="w-9 h-9 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white transition">
                                <span class="text-xs font-bold">X</span>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                                target="_blank" rel="noopener noreferrer"
                                class="w-9 h-9 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white transition">
                                <span class="text-xs font-bold">f</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-8">
                {{-- Region Banner --}}
                <div class="bg-gradient-to-br from-[#8B5A2B] to-[#5C3A1E] p-6 rounded-3xl text-white shadow-md">
                    <h3 class="text-xl font-bold mb-2">Explore {{ $culture->location ?? 'East Africa' }}</h3>
                    <p class="text-white/80 text-sm mb-5 leading-relaxed">
                        Discover authentic stays, guided heritage tours, and wildlife safaris across this region.
                    </p>
                    <a href="{{ url('/discover?search=' . urlencode($culture->location ?? 'Kenya')) }}"
                        class="block w-full bg-white text-[#8B5A2B] text-center py-3 rounded-xl font-semibold text-sm hover:bg-[#F5EFE6] transition shadow-sm">
                        Explore Destinations →
                    </a>
                </div>

                {{-- Nearby Destinations --}}
                @if (count($nearbyDestinations) > 0)
                    <div class="bg-white rounded-3xl border border-black/5 p-6 shadow-sm">
                        <h3 class="font-bold text-lg text-[#1A1A1A] mb-4">Nearby Destinations</h3>
                        <div class="space-y-4">
                            @foreach ($nearbyDestinations as $dest)
                                <a href="{{ route('destination.show', $dest['slug'] ?? $dest['id']) }}" class="flex gap-3 group items-center">
                                    <div class="w-12 h-12 rounded-xl bg-zinc-100 overflow-hidden shrink-0 border border-black/5">
                                        <img src="{{ $this->resolveImageUrl($dest['media_path'] ?? $dest['image'] ?? null) }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-sm text-[#1A1A1A] group-hover:text-[#8B5A2B] transition line-clamp-1">
                                            {{ $dest['name'] }}
                                        </h4>
                                        <p class="text-xs text-[#5C5C5C]">{{ $dest['location'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Affiliate Travel Deals --}}
                @if (!empty($affiliateOffers))
                    <div class="bg-white rounded-3xl border border-black/5 p-6 shadow-sm">
                        <h3 class="font-bold text-lg text-[#1A1A1A] mb-4">Travel Deals</h3>
                        <div class="space-y-3">
                            @foreach ($affiliateOffers as $offer)
                                <a href="{{ $offer['url'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                    class="block p-3.5 bg-[#FCFAF7] border border-black/5 rounded-2xl hover:border-[#8B5A2B] transition">
                                    <p class="font-semibold text-sm text-[#1A1A1A]">{{ $offer['title'] ?? 'Special Offer' }}</p>
                                    <p class="text-xs text-[#8B5A2B] font-medium mt-1">{{ $offer['price'] ?? 'Check Availability' }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Related Cultural Stories --}}
                @if (count($relatedCultures) > 0)
                    <div class="bg-white rounded-3xl border border-black/5 p-6 shadow-sm">
                        <h3 class="font-bold text-lg text-[#1A1A1A] mb-4">More Stories</h3>
                        <div class="space-y-4">
                            @foreach ($relatedCultures as $related)
                                <a href="{{ route('cultures.show', $related['slug'] ?? $related['id']) }}" class="block group">
                                    <h4 class="font-semibold text-sm text-[#1A1A1A] group-hover:text-[#8B5A2B] transition line-clamp-2">
                                        {{ $related['name'] ?? $related['title'] }}
                                    </h4>
                                    <p class="text-xs text-[#5C5C5C] mt-1">📍 {{ $related['location'] ?? 'Regional' }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>