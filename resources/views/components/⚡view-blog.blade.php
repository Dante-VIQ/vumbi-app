<?php

use Livewire\Component;
use App\Models\Blog;
use App\Models\AffiliateProgram;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\AffiliateMatcher;
use App\Services\AffiliateExecutionService;

new class extends Component
{
    public Blog $blog;                        // ✅ typed model property
    public $relatedPosts = [];
    public $recommendedAffiliates = [];
    public $dynamicHotels = [];
    public ?string $detectedLocation = null;
    public string $contentType = 'general';

    public string $commentName = '';
    public string $commentEmail = '';
    public string $commentContent = '';
    public string $email = '';
    public array $contentBlocks = [];
    public array $affiliateResults = [];

    public function mount(Blog $blog)
    {
        $this->blog = $blog->load('author');

        $this->contentType      = $this->detectContentType($this->blog);
        $this->detectedLocation = $this->detectLocation($this->blog);
        $this->contentBlocks    = $this->splitContentIntoBlocks();

        $cacheKey = 'affiliate_results_' . $this->blog->id;
        $this->affiliateResults = Cache::remember($cacheKey, now()->addHours(12), function () {
            if (class_exists(AffiliateMatcher::class) && class_exists(AffiliateExecutionService::class)) {
                $plan = app(AffiliateMatcher::class)->buildPlan($this->blog);
                return app(AffiliateExecutionService::class)->execute($plan, $this->detectedLocation);
            }
            return [];
        });

        $this->relatedPosts = Blog::where('category', $this->blog->category)
            ->where('id', '!=', $this->blog->id)
            ->latest()
            ->limit(3)
            ->get();

        if (class_exists(AffiliateProgram::class)) {
            $this->recommendedAffiliates = AffiliateProgram::active()
                ->where(function ($query) {
                    $terms = collect([
                        $this->blog->category,
                        $this->contentType,
                        $this->blog->title,
                        $this->blog->description ?? '',
                        ...($this->blog->tags ?? [])   // safe fallback since tags may not exist
                    ])
                    ->filter()
                    ->map(fn($t) => strtolower(trim($t)));

                    foreach ($terms as $term) {
                        $query->orWhere('type', 'like', "%{$term}%")
                              ->orWhere('keywords', 'like', "%{$term}%")
                              ->orWhereJsonContains('keywords', $term);
                    }
                })
                ->orderBy('priority', 'desc')
                ->limit(6)
                ->get();
        }
    }

    private function detectContentType(Blog $blog): string
    {
        $text = strtolower($blog->category . ' ' . $blog->title . ' ' . ($blog->excerpt ?? $blog->description ?? ''));
        if (str_contains($text, 'safari') || str_contains($text, 'beach') || str_contains($text, 'lodge') || str_contains($text, 'hotel')) {
            return 'destination';
        }
        if (str_contains($text, 'culture') || str_contains($text, 'history')) {
            return 'culture';
        }
        return $blog->category ?? 'general';
    }

    private function detectLocation(Blog $blog): ?string
    {
        // ⚠️ 'tags' and 'location' columns do not exist on Blog.
        // Use only title + description for now, or add the columns.
        $text = strtolower($blog->title . ' ' . ($blog->excerpt ?? $blog->description ?? ''));

        $map = [
            'maasai mara' => 'Maasai Mara',
            'diani'       => 'Diani Beach',
            'nakuru'      => 'Nakuru',
            'lamu'        => 'Lamu',
            'kenya'       => 'Kenya',
        ];

        foreach ($map as $key => $name) {
            if (str_contains($text, $key)) {
                return $name;
            }
        }
        return $this->contentType === 'destination' ? 'Kenya' : null;
    }

    // ==================== Actions ====================
    // ✅ Livewire 3: dispatch() replaces emit()

    public function shareOnTwitter()
    {
        $this->dispatch('share', [
            'platform' => 'twitter',
            'url'      => url()->current(),
            'title'    => $this->blog->title,
        ]);
    }

    public function shareOnLinkedIn()
    {
        $this->dispatch('share', [
            'platform' => 'linkedin',
            'url'      => url()->current(),
            'title'    => $this->blog->title,
        ]);
    }

    public function shareOnFacebook()
    {
        $this->dispatch('share', [
            'platform' => 'facebook',
            'url'      => url()->current(),
            'title'    => $this->blog->title,
        ]);
    }

    public function copyToClipboard()
    {
        $this->dispatch('copy-link', ['url' => url()->current()]);
    }

    public function submitComment()
    {
        $this->validate([
            'commentName'    => 'required|string|max:255',
            'commentEmail'   => 'required|email|max:255',
            'commentContent' => 'required|string|min:3',
        ]);

        $this->dispatch('comment-submitted', ['message' => 'Your comment has been submitted for moderation.']);
        $this->reset(['commentName', 'commentEmail', 'commentContent']);
    }

    public function subscribeFromArticle()
    {
        $this->validate(['email' => 'required|email']);
        $this->dispatch('subscribed', ['message' => 'Thank you for subscribing to Field Notes!']);
        $this->email = '';
    }

    private function splitContentIntoBlocks(): array
    {
        $rawContent = trim($this->blog->description ?? '');
        if (empty($rawContent)) {
            return [];
        }

        $html = $this->normalizeHeadings($rawContent);

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<meta charset="utf-8"><div>' . $html . '</div>';
        @$dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        if (!$dom->documentElement) {
            return [$html];
        }

        $wrapper = $dom->documentElement;
        $blocks = [];
        $currentBlock = '';

        foreach ($wrapper->childNodes as $node) {
            $nodeHtml = $dom->saveHTML($node);

            if ($node instanceof \DOMElement && strtolower($node->nodeName) === 'h2') {
                if (trim($currentBlock) !== '') {
                    $blocks[] = $currentBlock;
                    $currentBlock = '';
                }
                $blocks[] = $nodeHtml;
                continue;
            }

            $currentBlock .= $nodeHtml;
        }

        if (trim($currentBlock) !== '') {
            $blocks[] = $currentBlock;
        }

        return $blocks;
    }

    private function normalizeHeadings(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
       $wrappedHtml = '<meta charset="utf-8"><div>' . $html . '</div>';;
        @$dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        if (!$dom->documentElement) {
            return $html;
        }

        $wrapper = $dom->documentElement;
        $nodes = iterator_to_array($wrapper->childNodes);

        foreach ($nodes as $node) {
            if (!$this->looksLikeFakeHeading($node)) {
                continue;
            }

            $heading = $dom->createElement('h2', htmlspecialchars(trim($node->textContent), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
            $wrapper->replaceChild($heading, $node);
        }

        $resultHtml = '';
        foreach ($wrapper->childNodes as $child) {
            $resultHtml .= $dom->saveHTML($child);
        }

        return $resultHtml;
    }

    private function looksLikeFakeHeading(\DOMNode $node): bool
    {
        if (!($node instanceof \DOMElement) || strtolower($node->nodeName) !== 'p') {
            return false;
        }

        $text = trim($node->textContent);
        if ($text === '' || str_word_count($text) > 12) {
            return false;
        }

        $boldText = '';
        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMText) {
                if (trim($child->textContent) !== '') {
                    return false;
                }
                continue;
            }

            if ($child instanceof \DOMElement && in_array(strtolower($child->nodeName), ['strong', 'b'], true)) {
                $boldText .= $child->textContent;
                continue;
            }

            return false;
        }

        return $text !== '' && trim($boldText) === $text;
    }
};
?>

<div class="min-h-screen bg-[#FCFAF7] text-[#1A1A1A]">

    {{-- ===================== HERO HEADER ===================== --}}
    <section class="relative overflow-hidden pt-24 pb-8 md:pt-32 md:pb-12">
        <div class="absolute inset-0 grain opacity-[0.03] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(139,90,43,0.04),transparent_50%)] pointer-events-none"></div>

        <div class="relative container mx-auto px-6 max-w-4xl">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-sm text-[#5C5C5C] mb-6">
                <a href="{{ url('/') }}" class="hover:text-[#8B5A2B] transition">Home</a>
                <span>/</span>
                <a href="{{ url('/blog') }}" class="hover:text-[#8B5A2B] transition">Field Notes</a>
                <span>/</span>
                <span class="text-[#8B5A2B] truncate font-medium">{{ $blog->category ?? 'Story' }}</span>
            </nav>

            <div class="inline-flex items-center gap-2 text-sm bg-white/70 backdrop-blur-sm border border-black/5 px-4 py-2 rounded-full shadow-sm mb-6">
                <span class="w-2 h-2 bg-[#8B5A2B] rounded-full"></span>
                <span class="uppercase tracking-wider text-xs font-semibold text-[#8B5A2B]">{{ $blog->category ?? 'Field Notes' }}</span>
                <span class="text-[#5C5C5C]">·</span>
                <span class="text-xs text-[#5C5C5C]">{{ $blog->reading_time ?? '8 min read' }}</span>
            </div>

            <h1 class="text-3xl md:text-5xl lg:text-6xl font-semibold leading-tight tracking-tight text-[#1A1A1A] mb-6">
                {{ $blog->title }}
            </h1>

            {{-- Author & Meta --}}
            <div class="flex flex-wrap items-center justify-between gap-4 py-6 border-t border-b border-black/5 mt-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#F5EFE6] rounded-full flex items-center justify-center text-[#8B5A2B] font-bold text-lg border-2 border-white shadow-sm uppercase">
                        {{ substr($blog->author->name ?? 'V', 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-[#1A1A1A]">{{ $blog->author->name ?? 'Vumbi Ventures' }}</div>
                        <div class="text-xs text-[#5C5C5C]">{{ $blog->author->title ?? 'Field Writer' }}</div>
                    </div>
                </div>
                <div class="text-sm text-[#5C5C5C] font-medium">
                    {{ $blog->created_at ? $blog->created_at->format('M d, Y') : '' }}
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FEATURED MEDIA ===================== --}}
    @if ($blog->media_path)
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
                    prose-blockquote:border-l-4 prose-blockquote:border-[#8B5A2B] prose-blockquote:bg-[#F5EFE6]/50 prose-blockquote:py-2 prose-blockquote:px-6 prose-blockquote:rounded-r-lg prose-blockquote:italic prose-blockquote:text-[#5C5C5C]
                    prose-ul:my-6 prose-li:text-[#3A3A3A]
                    prose-img:rounded-2xl prose-img:shadow-md">

                    @if (!empty($contentBlocks))
                        @foreach ($contentBlocks as $block)
                            {!! $block !!}
                        @endforeach
                    @else
                        {!! nl2br(e($blog->description ?? $blog->content ?? '')) !!}
                    @endif
                </article>

                {{-- Author Bio --}}
                @if ($blog->author && $blog->author->bio)
                    <div class="mt-12 pt-8 border-t border-black/5">
                        <div class="flex items-start gap-5 bg-white p-6 rounded-2xl border border-black/5 shadow-sm">
                            <div class="w-14 h-14 bg-[#F5EFE6] rounded-full flex items-center justify-center text-[#8B5A2B] text-xl font-bold flex-shrink-0 border-2 border-white shadow-sm uppercase">
                                {{ substr($blog->author->name ?? 'V', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-[#1A1A1A]">{{ $blog->author->name }}</h4>
                                <p class="text-sm text-[#5C5C5C] mt-1 leading-relaxed">{{ $blog->author->bio }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Share Section --}}
                <div class="mt-10 pt-6 border-t border-black/5 flex items-center justify-between flex-wrap gap-4">
                    <span class="text-sm font-semibold text-[#5C5C5C]">Share this story:</span>
                    <div class="flex gap-3">
                        <button wire:click="shareOnTwitter" type="button" title="Share on Twitter"
                            class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            🐦
                        </button>
                        <button wire:click="shareOnLinkedIn" type="button" title="Share on LinkedIn"
                            class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            💼
                        </button>
                        <button wire:click="shareOnFacebook" type="button" title="Share on Facebook"
                            class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            👍
                        </button>
                        <button wire:click="copyToClipboard" type="button" title="Copy Link"
                            class="w-10 h-10 border border-black/10 rounded-full flex items-center justify-center hover:bg-[#8B5A2B] hover:text-white hover:border-[#8B5A2B] transition text-[#5C5C5C]">
                            🔗
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===================== SIDEBAR --}}
            <aside class="lg:col-span-4 space-y-8">
                
                {{-- Travel Booking Widget --}}
                @if ($blog->title)
                    @livewire('related-tours', ['blogTitle' => $blog->title, 'location' => $blog->location])
                @endif

                {{-- Affiliate Offers Widget --}}
                @if (!empty($affiliateResults['sidebar']) || (isset($recommendedAffiliates) && count($recommendedAffiliates)))
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4 flex items-center gap-2 text-zinc-900">
                            <span>🎯</span> Recommended
                        </h3>
                        <div class="space-y-4">
                            @php $sidebarOffers = $affiliateResults['sidebar'] ?? $recommendedAffiliates; @endphp
                            @foreach (collect($sidebarOffers)->take(3) as $offer)
                                <a href="{{ $offer['url'] ?? $offer->url ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                    class="flex gap-3 group items-center">
                                    @if (!empty($offer['image'] ?? $offer->image ?? null))
                                        <img src="{{ $offer['image'] ?? $offer->image }}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-[#1A1A1A] group-hover:text-[#8B5A2B] text-sm leading-snug transition">
                                            {{ $offer['title'] ?? $offer->name }}
                                        </h4>
                                        <p class="text-xs text-[#5C5C5C] mt-0.5 line-clamp-1">
                                            {{ $offer['description'] ?? $offer->description ?? 'Learn more about this experience' }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Table of Contents Widget --}}
                <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm" x-data="{ tocItems: [] }"
                    x-init="const buildToc = () => {
                        const headings = document.querySelectorAll('.prose h2, .prose h3');
                        const items = [];
                        headings.forEach((h, i) => {
                            h.id = h.id || `heading-${i}`;
                            items.push({ text: h.textContent, level: h.tagName, id: h.id });
                        });
                        tocItems = items;
                    };
                    buildToc();
                    setTimeout(buildToc, 400);
                    Livewire.hook('morph.updated', buildToc);">
                    
                    <h3 class="font-semibold text-base mb-3 text-zinc-900 border-b border-zinc-100 pb-2">On This Page</h3>
                    <ul class="space-y-1.5 text-sm">
                        <template x-for="item in tocItems" :key="item.id">
                            <li>
                                <a :href="'#' + item.id" x-text="item.text"
                                    :class="item.level === 'H3' ? 'pl-4 text-[#5C5C5C]' : 'font-medium text-zinc-800'"
                                    class="block py-0.5 hover:text-[#8B5A2B] transition line-clamp-1"></a>
                            </li>
                        </template>
                    </ul>
                    <p x-show="tocItems.length === 0" class="text-xs text-[#5C5C5C] italic">No main sections</p>
                </div>

                {{-- Newsletter Card --}}
                <div class="bg-[#F5EFE6] p-6 rounded-2xl border border-[#8B5A2B]/15">
                    <h3 class="font-semibold text-lg text-[#1A1A1A] mb-1">Field Notes Dispatch</h3>
                    <p class="text-xs text-[#5C5C5C] mb-4 leading-relaxed">Receive untold travel stories and insider African history directly in your inbox.</p>
                    
                    <form wire:submit.prevent="subscribeFromArticle">
                        <input type="email" wire:model="email" placeholder="Enter your email address"
                            class="w-full px-4 py-3 rounded-xl border border-black/10 bg-white mb-3 text-sm focus:outline-none focus:border-[#8B5A2B] transition">
                        @error('email') <span class="text-xs text-rose-500 mb-2 block">{{ $message }}</span> @enderror
                        
                        <button type="submit"
                            class="w-full bg-[#8B5A2B] text-white py-3 rounded-xl font-medium text-sm hover:bg-[#5C3A1E] transition shadow-md">
                            Subscribe
                        </button>
                    </form>
                </div>

                {{-- Related Posts (Sidebar fallback) --}}
                @if (count($relatedPosts) > 0)
                    <div class="bg-white rounded-2xl border border-black/5 p-5 shadow-sm">
                        <h3 class="font-semibold text-base mb-4 text-zinc-900">Related Stories</h3>
                        <div class="space-y-4">
                            @foreach ($relatedPosts as $post)
                                <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="flex gap-3 group items-center">
                                    @if ($post->media_path)
                                        <img src="{{ asset('storage/' . $post->media_path) }}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                                    @endif
                                    <div>
                                        <h4 class="font-medium text-[#1A1A1A] group-hover:text-[#8B5A2B] text-sm leading-snug line-clamp-2 transition">
                                            {{ $post->title }}
                                        </h4>
                                        <p class="text-[11px] text-[#5C5C5C] mt-1">{{ $post->created_at ? $post->created_at->format('M d, Y') : '' }}</p>
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
    @if (count($relatedPosts) > 0)
        <section class="py-16 bg-white mt-16 border-t border-black/5">
            <div class="container mx-auto px-6">
                <div class="text-center mb-10 max-w-xl mx-auto">
                    <h2 class="text-3xl font-semibold text-[#1A1A1A] mb-2">More Field Notes</h2>
                    <p class="text-sm text-[#5C5C5C]">Continue exploring narratives from overlooked destinations</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach ($relatedPosts->take(3) as $post)
                        <a href="{{ route('blog.show', $post->slug ?? $post->id) }}"
                            class="group block bg-[#FCFAF7] rounded-2xl overflow-hidden border border-black/5 hover:shadow-xl transition duration-300">
                            @if ($post->media_path)
                                <div class="aspect-[16/9] overflow-hidden bg-zinc-200">
                                    <img src="{{ asset('storage/' . $post->media_path) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="text-[10px] uppercase font-bold tracking-wider text-[#8B5A2B] mb-2">
                                    {{ $post->category }}
                                </div>
                                <h3 class="text-lg font-semibold text-zinc-900 group-hover:text-[#8B5A2B] transition line-clamp-2 leading-snug">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-xs text-[#5C5C5C] mt-2 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($post->description ?? $post->content ?? ''), 100) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Toast Notification Window --}}
    <div x-data x-show="$store.shareToast && $store.shareToast.visible" x-cloak
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 right-6 bg-zinc-900 text-white shadow-2xl rounded-xl px-5 py-3 text-sm border border-zinc-800 z-50 flex items-center gap-2">
        <span class="text-green-400">✓</span>
        <span x-text="$store.shareToast.message"></span>
    </div>

</div>

@push('styles')
    <style>
        .grain {
            background-image: url("https://grainy-gradients.vercel.app/noise.svg");
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('shareToast', {
                visible: false,
                message: '',
                show(msg) {
                    this.message = msg;
                    this.visible = true;
                    setTimeout(() => { this.visible = false; }, 3500);
                }
            });
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('copy-link', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                const shareUrl = data?.url ?? window.location.href;
                navigator.clipboard.writeText(shareUrl);
                if (window.Alpine && Alpine.store('shareToast')) {
                    Alpine.store('shareToast').show('Link copied to clipboard!');
                }
            });

            Livewire.on('share', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                const shareUrl = data.url ?? window.location.href;
                const shareLinks = {
                    twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(data.title ?? '')}`,
                    linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`,
                    facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
                };
                if (shareLinks[data.platform]) {
                    window.open(shareLinks[data.platform], '_blank', 'noopener,width=600,height=500');
                }
            });

            Livewire.on('subscribed', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                if (window.Alpine && Alpine.store('shareToast')) {
                    Alpine.store('shareToast').show(data.message ?? 'Thank you for subscribing!');
                }
            });

            Livewire.on('comment-submitted', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                if (window.Alpine && Alpine.store('shareToast')) {
                    Alpine.store('shareToast').show(data.message ?? 'Comment submitted.');
                }
            });
        });
    </script>
@endpush
