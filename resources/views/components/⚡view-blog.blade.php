<?php

use Livewire\Component;
use App\Models\Blog;
use App\Models\AffiliateProgram;
use App\Services\TravelpayoutsService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\AffiliateMatcher;
use App\Services\AffiliateExecutionService;

new class extends Component {
    public $blog;
    public $relatedPosts = [];
    public $recommendedAffiliates = [];
    public $dynamicHotels = [];
    public $detectedLocation = null;
    public $contentType = 'general';

    // Form fields
    public $commentName = '';
    public $commentEmail = '';
    public $commentContent = '';
    public $email = ''; // newsletter
    public $contentBlocks = [];
    public $affiliateResults = [];

public function mount(Blog $blog)
{
    $this->blog = $blog->load('author');

    if (!$this->blog) {
        abort(404);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Content Intelligence Layer
    |--------------------------------------------------------------------------
    */
    $this->contentType = $this->detectContentType($this->blog);
    $this->detectedLocation = $this->detectLocation($this->blog);
    $this->contentBlocks = $this->splitContentIntoBlocks();


    /*
    |--------------------------------------------------------------------------
    | 2. Affiliate Matching Engine (CORE MONETIZATION)
    |--------------------------------------------------------------------------
    */
        $cacheKey = 'affiliate_results_' . $this->blog->id;
        $this->affiliateResults = Cache::remember($cacheKey, now()->addHours(12), function () {
            $plan = app(AffiliateMatcher::class)->buildPlan($this->blog);
            return app(AffiliateExecutionService::class)->execute($plan, $this->detectedLocation);
        });


    /*
    |--------------------------------------------------------------------------
    | 3. Related Posts (Engagement Layer)
    |--------------------------------------------------------------------------
    */
    $this->relatedPosts = Blog::where('category', $this->blog->category)
        ->where('id', '!=', $this->blog->id)
        ->latest()
        ->limit(3)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | 4. Static Affiliate Programs (Backup Widgets)
    |--------------------------------------------------------------------------
    */
    $this->recommendedAffiliates = AffiliateProgram::active()
        ->where(function ($query) {

            $terms = collect([
                $this->blog->category,
                $this->contentType,
                $this->blog->title,
                $this->blog->description ?? '',
                ...($this->blog->tags ?? [])
            ])
            ->filter()
            ->map(fn ($t) => strtolower(trim($t)));

            foreach ($terms as $term) {
                $query->orWhere('type', 'like', "%{$term}%")
                    ->orWhere('keywords', 'like', "%{$term}%")
                    ->orWhereJsonContains('keywords', $term);
            }
        })
        ->orderBy('priority','desc')
        ->limit(6)
        ->get();
}

    // ==================== SEO: Dynamic Meta ===================
    // ==================== Content Analysis ====================
    public function layoutData(): array
    {
        $description = $this->blog->excerpt
            ? Str::limit(strip_tags($this->blog->excerpt), 155)
            : Str::limit(strip_tags($this->blog->description ?? ''), 155);

        return [
            'title' => $this->blog->title . ' | Vumbi Ventures',
            'description' => $description,
            'ogImage' => $this->blog->media_path ? Storage::url($this->blog->media_path) : asset('images/default-og.jpg'),
            'canonical' => url()->current(),
        ];
    }

    private function detectContentType(Blog $blog): string
    {
        $text = strtolower($blog->category . ' ' . $blog->title . ' ' . ($blog->excerpt ?? ''));
        if (str_contains($text, 'safari') || str_contains($text, 'beach') || str_contains($text, 'lodge'))
            return 'destination';
        if (str_contains($text, 'culture') || str_contains($text, 'history') || str_contains($text, 'abubakari'))
            return 'culture';
        return $blog->category ?? 'general';
    }

    private function detectLocation(Blog $blog): ?string
    {
        $text = strtolower($blog->title . ' ' . ($blog->excerpt ?? '') . ' ' . implode(' ', $blog->tags ?? []));
        $map = ['maasai mara' => 'Maasai Mara', 'diani' => 'Diani Beach', 'nakuru' => 'Nakuru', 'lamu' => 'Lamu', 'kenya' => 'Kenya'];
        foreach ($map as $key => $name) {
            if (str_contains($text, $key))
                return $name;
        }
        return $this->contentType === 'destination' ? 'Kenya' : null;
    }

    // ==================== Actions ====================
    public function shareOnTwitter()
    {
        $this->dispatch('share', [
            'platform' => 'twitter',
            'url' => url()->current(),
            'title' => $this->blog->title
        ]);
    }

    public function shareOnLinkedIn()
    {
        $this->dispatch('share', ['platform' => 'linkedin', 'url' => url()->current()]);
    }

    public function shareOnFacebook()
    {
        $this->dispatch('share', ['platform' => 'facebook', 'url' => url()->current()]);
    }

    public function copyToClipboard()
    {
        $this->dispatch('copy-link');
    }

    public function submitComment()
    {
        $this->validate([
            'commentName' => 'required|string|max:255',
            'commentEmail' => 'required|email|max:255',
            'commentContent' => 'required|string|min:3',
        ]);

        // Add comment logic later
        $this->dispatch('comment-submitted', ['message' => 'Your comment has been submitted for moderation.']);
        $this->reset(['commentName', 'commentEmail', 'commentContent']);
    }

    public function subscribeFromArticle()
    {
        $this->validate(['email' => 'required|email']);
        $this->dispatch('subscribed', ['message' => 'Thank you for subscribing!']);
        $this->email = '';
    }

    public function blog()
    {
        return $this->blog;
    }
    public function with()
    {
        return [
            'blog' => $this->blog(),
            // 'categories' => $this->categories,
        ];
    }

private function splitContentIntoBlocks(): array
{
    if (!$this->blog || !$this->blog->formatted_description) {
        return [];
    }

    $content = $this->blog->formatted_description;

    /*
    Split content while KEEPING H2 tags as separate blocks.
    This lets us inject affiliate widgets between sections later.
    */
    $blocks = preg_split(
        "/(<h2[^>]*>.*?<\/h2>)/i",
        $content,
        -1,
        PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
    );

    return $blocks ?: [];
}

private function shouldInsertWidget($blockIndex): bool
{
    return $blockIndex > 0 && $blockIndex % 2 === 0;
}
};

?>

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">
    {{-- Dynamic SEO Meta (handled via layoutData in component) --}}
    @php
        $seo = $this->layoutData();
    @endphp
    @section('title', $seo['title'])
    @section('meta_description', $seo['description'])
    @push('meta')
        <meta property="og:title" content="{{ $seo['title'] }}">
        <meta property="og:description" content="{{ $seo['description'] }}">
        <meta property="og:image" content="{{ $seo['ogImage'] }}">
        <meta property="og:type" content="article">
        <meta property="article:published_time" content="{{ $blog->created_at->toIso8601String() }}">
        <meta property="article:author" content="{{ $blog->author->name ?? 'Vumbi Ventures' }}">
        <link rel="canonical" href="{{ $seo['canonical'] }}">
    @endpush

    {{-- @push('structured-data')
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Article",
          "headline": "{{ $blog->title }}",
          "image": "{{ $seo['ogImage'] }}",
          "author": {
            "@type": "Person",
            "name": "{{ $blog->author->name ?? 'Vumbi Ventures' }}"
          },
          "publisher": {
            "@type": "Organization",
            "name": "Vumbi Ventures",
            "logo": {
              "@type": "ImageObject",
              "url": "{{ asset('images/vumbi-logo.png') }}"
            }
          },
          "datePublished": "{{ $blog->created_at->toIso8601String() }}",
          "description": "{{ $seo['description'] }}"
        }
        </script>
    @endpush --}}

    {{-- ===================== HERO HEADER ===================== --}}
    <section class="relative overflow-hidden pt-24 pb-8 md:pt-32 md:pb-12">
        <div class="absolute inset-0 grain opacity-[0.03]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(139,90,43,0.04),transparent_50%)]"></div>

        <div class="relative container mx-auto px-6 max-w-4xl">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-sm text-[#5C5C5C] mb-6">
                <a href="{{ url('/') }}" class="hover:text-[#8B5A2B]">Home</a>
                <span>/</span>
                <a href="{{ url('/field-notes') }}" class="hover:text-[#8B5A2B]">Field Notes</a>
                <span>/</span>
                <span class="text-[#8B5A2B] truncate">{{ $blog->category ?? 'Story' }}</span>
            </nav>

            <div class="inline-flex items-center gap-2 text-sm bg-white/70 backdrop-blur-sm border border-white/40 px-4 py-2 rounded-full shadow-sm mb-6">
                <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
                <span class="uppercase tracking-wider text-xs font-medium">{{ $blog->category ?? 'Field Notes' }}</span>
                <span class="text-[#5C5C5C]">·</span>
                <span>{{ $blog->reading_time ?? '8 min read' }}</span>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold leading-tight tracking-tight text-[#1A1A1A] mb-6">
                {{ $blog->title }}
            </h1>

            {{-- @if($blog->description)
                <p class="text-xl text-[#5C5C5C] leading-relaxed border-l-4 border-[#8B5A2B] pl-6">
                    {{ $blog->description }}
                </p>
            @endif --}}

            {{-- Author & Meta --}}
            <div class="flex flex-wrap items-center justify-between gap-4 py-6 border-t border-b border-black/5 mt-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#F5EFE6] rounded-full flex items-center justify-center text-[#8B5A2B] font-bold text-lg border-2 border-white shadow-sm">
                        {{ substr($blog->author->name ?? 'V', 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-[#1A1A1A]">{{ $blog->author->name ?? 'Vumbi Ventures' }}</div>
                        <div class="text-sm text-[#5C5C5C]">{{ $blog->author->title ?? 'Field Writer' }}</div>
                    </div>
                </div>
                <div class="text-sm text-[#5C5C5C]">
                    {{ $blog->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FEATURED MEDIA ===================== --}}
    @if($blog->media_path)
        <div class="container mx-auto px-6 mb-12">
            <div class="max-w-5xl mx-auto">
                <img src="{{ asset($blog->media_path) }}" alt="{{ $blog->title }}"
                    class="w-full rounded-3xl shadow-xl object-cover max-h-[600px] border border-black/5"
                    loading="eager">
            </div>
        </div>
    @endif

    {{-- ===================== MAIN CONTENT + SIDEBAR ===================== --}}
    <div class="container mx-auto px-6 py-8">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
            {{-- MAIN ARTICLE CONTENT --}}
            <div class="lg:col-span-8">
                <article class="prose prose-lg max-w-none
                    prose-headings:text-[#1A1A1A] prose-headings:font-semibold prose-headings:tracking-tight
                    prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-6
                    prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-4
                    prose-p:text-[#3A3A3A] prose-p:leading-relaxed prose-p:mb-6
                    prose-a:text-[#8B5A2B] prose-a:font-medium prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-[#1A1A1A] prose-strong:font-semibold
                    prose-blockquote:border-l-4 prose-blockquote:border-[#8B5A2B] prose-blockquote:bg-[#F5EFE6]/50 prose-blockquote:py-1 prose-blockquote:px-6 prose-blockquote:rounded-r-lg prose-blockquote:italic prose-blockquote:text-[#5C5C5C]
                    prose-ul:my-6 prose-li:text-[#3A3A3A]
                    prose-img:rounded-2xl prose-img:shadow-md">

                    {{-- Render content blocks with inline affiliate widgets --}}
                    @if(!empty($contentBlocks))
                        @foreach($contentBlocks as $index => $block)
                            {!! $block !!}

                            @if($this->shouldInsertWidget($index))
                                {{-- Inline Monetization Widget --}}
                                <div class="not-prose my-8 p-6 bg-white rounded-2xl border border-[#8B5A2B]/20 shadow-sm">
                                    <p class="text-sm font-medium text-[#8B5A2B] uppercase tracking-wider mb-3">📍 While You're Here</p>
                                    <div class="grid sm:grid-cols-2 gap-4">
                                        @forelse($affiliateResults['inline'] ?? [] as $offer)
                                            <a href="{{ $offer['url'] }}" target="_blank" rel="nofollow sponsored"
                                               class="block p-4 bg-[#FCFAF7] rounded-xl border border-black/5 hover:border-[#8B5A2B]/30 hover:shadow-md transition group">
                                                @if(!empty($offer['image']))
                                                    <img src="{{ $offer['image'] }}" alt="{{ $offer['title'] }}" class="w-full h-32 object-cover rounded-lg mb-3">
                                                @endif
                                                <h4 class="font-semibold text-[#1A1A1A] group-hover:text-[#8B5A2B]">{{ $offer['title'] }}</h4>
                                                <p class="text-sm text-[#5C5C5C] mt-1">{{ $offer['description'] ?? '' }}</p>
                                                <span class="inline-block mt-2 text-sm font-medium text-[#8B5A2B]">View Deal →</span>
                                            </a>
                                        @empty
                                            {{-- Fallback static affiliate (e.g., Booking.com) --}}
                                            <a href="#" class="block p-4 bg-[#FCFAF7] rounded-xl border border-black/5 hover:border-[#8B5A2B]/30 hover:shadow-md transition">
                                                <h4 class="font-semibold">Find Hotels in {{ $detectedLocation ?? 'Kenya' }}</h4>
                                                <p class="text-sm text-[#5C5C5C] mt-1">Search and compare prices from top booking sites.</p>
                                                <span class="inline-block mt-2 text-sm font-medium text-[#8B5A2B]">Search Now →</span>
                                            </a>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                     {!! nl2br(e($blog->formatted_description)) !!}
                    @endif
                </article>

                {{-- Author Bio --}}
                @if($blog->author && $blog->author->bio)
                    <div class="mt-12 pt-8 border-t border-black/5">
                        <div class="flex items-start gap-5">
                            <div class="w-16 h-16 bg-[#F5EFE6] rounded-full flex items-center justify-center text-[#8B5A2B] text-2xl font-bold flex-shrink-0 border-2 border-white shadow-sm">
                                {{ substr($blog->author->name ?? 'V', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-[#1A1A1A]">{{ $blog->author->name }}</h4>
                                <p class="text-sm text-[#5C5C5C]">{{ $blog->author->bio }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Share Section (Mobile friendly) --}}
                <div class="mt-10 pt-6 border-t border-black/5 flex items-center justify-between">
                    <span class="text-sm font-medium text-[#5C5C5C]">Share this story:</span>
                    <div class="flex gap-3">
                        <button wire:click="shareOnTwitter" class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button wire:click="shareOnLinkedIn" class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            <i class="fab fa-linkedin-in"></i>
                        </button>
                        <button wire:click="shareOnFacebook" class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button wire:click="copyToClipboard" class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
                <div x-show="copyMessage" x-cloak class="text-sm text-green-600 mt-2 text-right">Link copied!</div>
            </div>

            {{-- ===================== SIDEBAR ===================== --}}
            <aside class="lg:col-span-4 space-y-8">
                {{-- Primary CTA: Travel Booking --}}
                <div class="bg-gradient-to-br from-[#8B5A2B] to-[#5C3A1E] p-6 rounded-2xl text-white shadow-lg">
                    <h3 class="text-xl font-semibold mb-2">Discover {{ $detectedLocation ?? 'Africa' }}</h3>
                    <p class="text-white/80 text-sm mb-4">Book handpicked hotels, safaris & experiences.</p>
                    <a href="{{ url('/discover?search='.urlencode($detectedLocation ?? '')) }}"
                       class="inline-block w-full bg-white text-[#8B5A2B] text-center py-3 rounded-xl font-medium hover:bg-[#F5EFE6] transition">
                        Explore Destinations →
                    </a>
                </div>

                {{-- Affiliate Offers Widget --}}
                @if(!empty($affiliateResults['sidebar']) || $recommendedAffiliates->count())
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                            <span>🎯</span> Recommended for You
                        </h3>
                        <div class="space-y-4">
                            @php $sidebarOffers = $affiliateResults['sidebar'] ?? $recommendedAffiliates; @endphp
                            @foreach($sidebarOffers->take(3) as $offer)
                                <a href="{{ $offer['url'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                   class="flex gap-3 group">
                                    @if(!empty($offer['image']))
                                        <img src="{{ $offer['image'] }}" class="w-16 h-16 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <h4 class="font-medium text-[#1A1A1A] group-hover:text-[#8B5A2B] text-sm">{{ $offer['title'] ?? $offer->name }}</h4>
                                        <p class="text-xs text-[#5C5C5C] mt-0.5">{{ Str::limit($offer['description'] ?? '', 40) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Table of Contents (if headings exist) --}}
                <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm" x-data="{ tocItems: [] }" x-init="
                    setTimeout(() => {
                        const headings = document.querySelectorAll('.prose h2, .prose h3');
                        headings.forEach((h, i) => {
                            h.id = h.id || `heading-${i}`;
                            tocItems.push({ text: h.textContent, level: h.tagName, id: h.id });
                        });
                    }, 200)
                ">
                    <h3 class="font-semibold text-lg mb-3">On This Page</h3>
                    <ul class="space-y-1 text-sm">
                        <template x-for="item in tocItems">
                            <li>
                                <a :href="'#' + item.id" x-text="item.text"
                                   :class="item.level === 'H3' ? 'pl-4 text-[#5C5C5C]' : 'font-medium'"
                                   class="block py-1 hover:text-[#8B5A2B] transition"></a>
                            </li>
                        </template>
                    </ul>
                    <p x-show="tocItems.length === 0" class="text-sm text-[#5C5C5C]">No headings</p>
                </div>

                {{-- Newsletter --}}
                <div class="bg-[#F5EFE6] p-6 rounded-2xl border border-[#8B5A2B]/10">
                    <h3 class="font-semibold text-lg text-[#1A1A1A] mb-2">Field Notes Dispatch</h3>
                    <p class="text-sm text-[#5C5C5C] mb-4">Get travel stories and insider tips in your inbox.</p>
                    <form wire:submit.prevent="subscribeFromArticle">
                        <input type="email" wire:model="email" placeholder="Your email"
                            class="w-full px-4 py-3 rounded-xl border border-black/10 bg-white mb-3 focus:outline-none focus:border-[#8B5A2B]">
                        <button type="submit"
                            class="w-full bg-[#8B5A2B] text-white py-3 rounded-xl font-medium hover:bg-[#5C3A1E] transition">
                            Subscribe
                        </button>
                    </form>
                    <div x-show="shareMessage" x-cloak x-text="shareMessageText" class="text-sm text-green-600 mt-2"></div>
                </div>

                {{-- Related Posts (if not enough in main section) --}}
                @if($relatedPosts->count() > 0)
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4">Related Stories</h3>
                        <div class="space-y-4">
                            @foreach($relatedPosts as $post)
                                <a href="{{ route('blog.show', $post->id) }}" class="flex gap-3 group">
                                    @if($post->media_path)
                                        <img src="{{ Storage::url($post->media_path) }}" class="w-16 h-16 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <h4 class="font-medium text-[#1A1A1A] group-hover:text-[#8B5A2B] text-sm line-clamp-2">{{ $post->title }}</h4>
                                        <p class="text-xs text-[#5C5C5C] mt-1">{{ $post->created_at->format('M d') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>

    {{-- ===================== FULL-WIDTH RELATED POSTS ===================== --}}
    @if($relatedPosts->count() > 0)
        <section class="py-16 bg-white mt-12">
            <div class="container mx-auto px-6">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-semibold text-[#1A1A1A] mb-3">More Field Notes</h2>
                    <p class="text-[#5C5C5C]">Stories from overlooked places</p>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedPosts->take(3) as $post)
                        <a href="{{ route('blog.show', $post->id) }}" class="group block bg-[#FCFAF7] rounded-2xl overflow-hidden border border-black/5 hover:shadow-lg transition">
                            @if($post->media_path)
                                <div class="aspect-[16/9] overflow-hidden">
                                    <img src="{{ Storage::url($post->media_path) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="text-xs uppercase tracking-wider text-[#8B5A2B] mb-2">{{ $post->category }}</div>
                                <h3 class="text-xl font-semibold group-hover:text-[#8B5A2B] transition line-clamp-2">{{ $post->title }}</h3>
                                <p class="text-sm text-[#5C5C5C] mt-2 line-clamp-2">{{ Str::limit(strip_tags($post->description), 100) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== COMMENTS SECTION ===================== --}}
    {{-- <section class="py-16 bg-[#FCFAF7]">
        <div class="container mx-auto px-6 max-w-3xl">
            <h3 class="text-2xl font-semibold mb-8">Join the Conversation</h3>

            Comment Form
            <div class="bg-white rounded-2xl border border-black/5 p-6 shadow-sm mb-8">
                <form wire:submit.prevent="submitComment" class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <input type="text" wire:model="commentName" placeholder="Your Name *"
                            class="w-full px-4 py-3 rounded-xl border border-black/10 focus:outline-none focus:border-[#8B5A2B]">
                        <input type="email" wire:model="commentEmail" placeholder="Your Email *"
                            class="w-full px-4 py-3 rounded-xl border border-black/10 focus:outline-none focus:border-[#8B5A2B]">
                    </div>
                    <textarea wire:model="commentContent" rows="4" placeholder="Share your thoughts..."
                        class="w-full px-4 py-3 rounded-xl border border-black/10 focus:outline-none focus:border-[#8B5A2B] resize-none"></textarea>
                    <button type="submit"
                        class="bg-[#8B5A2B] text-white px-6 py-3 rounded-xl font-medium hover:bg-[#5C3A1E] transition">
                        Post Comment
                    </button>
                </form>
                @error('commentName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @error('commentEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @error('commentContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            Comments List (Placeholder)
            <div class="bg-white rounded-2xl border border-black/5 p-8 text-center">
                <i class="far fa-comments text-4xl text-[#8B5A2B]/30 mb-3"></i>
                <p class="text-[#5C5C5C]">No comments yet. Be the first to share your thoughts!</p>
            </div>
        </div>
    </section> --}}

    {{-- Notification Toasts --}}
    <div x-show="copyMessage || shareMessage" x-cloak
         class="fixed bottom-6 right-6 bg-white shadow-xl rounded-xl px-5 py-3 text-sm border border-black/5 z-50"
         x-text="copyMessage ? 'Link copied!' : shareMessageText">
    </div>
</div>

@push('styles')
<style>
    .grain { background-image: url("https://grainy-gradients.vercel.app/noise.svg"); opacity: 0.03; pointer-events: none; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    [x-cloak] { display: none !important; }
</style>
@endpush