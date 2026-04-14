<?php

use Livewire\Component;
use App\Models\Blog;
use App\Models\AffiliateProgram;
use App\Services\TravelpayoutsService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function mount(Blog $blog)
    {
        $this->blog = $blog->load('author');

        if (!$this->blog) {
            abort(404);
        }

        $this->contentType = $this->detectContentType($this->blog);
        $this->detectedLocation = $this->detectLocation($this->blog);

        // Related Posts
        $this->relatedPosts = Blog::where('category', $this->blog->category)
            ->where('id', '!=', $this->blog->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Static Affiliates (Awin + Travelpayouts)
        $this->recommendedAffiliates = AffiliateProgram::active()
            ->where(function ($query) {
                $terms = collect([
                    $this->blog->category,
                    $this->contentType,
                    $this->blog->title,
                    $this->blog->description ?? '',
                    ...($this->blog->tags ?? [])
                ])->filter()->map(fn($t) => strtolower(trim($t)));

                foreach ($terms as $term) {
                    $query->orWhere('type', 'like', "%{$term}%")
                        ->orWhere('keywords', 'like', "%{$term}%")
                        ->orWhereJsonContains('keywords', $term);
                }
            })
            ->orderBy('priority', 'desc')
            ->limit(6)
            ->get();

        // Dynamic Hotels
        if ($this->detectedLocation && in_array($this->contentType, ['destination', 'culture'])) {
            try {
                $service = app(TravelpayoutsService::class);
                $this->dynamicHotels = $service->searchHotels($this->detectedLocation, limit: 4);
            } catch (\Exception $e) {
                $this->dynamicHotels = [];
            }
        }
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



    public function getFormattedDescriptionAttribute()
    {
        // Convert line breaks to <p> tags
        $paragraphs = explode("\n\n", $this->description);
        $html = '';
        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (!empty($paragraph)) {
                $html .= '<p>' . nl2br(e($paragraph)) . '</p>';
            }
        }
        return $html;
    }
};

?>

<div class="bg-deep-earth" x-data="{ 
        copyMessage: false, 
        shareMessage: false, 
        shareMessageText: '' 
     }" @share.window="
        let platform = $event.detail.platform;
        let url = $event.detail.url;
        let title = $event.detail.title || '';
        let shareUrl = '';
        if(platform === 'twitter') {
            shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
        } else if(platform === 'facebook') {
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
        } else if(platform === 'linkedin') {
            shareUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(url)}`;
        }
        if(shareUrl) window.open(shareUrl, '_blank', 'width=600,height=400');
     " @copy-link.window="
        navigator.clipboard.writeText(window.location.href);
        copyMessage = true;
        setTimeout(() => copyMessage = false, 3000);
     " @comment-submitted.window="
        shareMessageText = $event.detail.message;
        shareMessage = true;
        setTimeout(() => shareMessage = false, 3000);
     " @subscribed.window="
        shareMessageText = $event.detail.message;
        shareMessage = true;
        setTimeout(() => shareMessage = false, 3000);
     ">

    {{-- Hero Section --}}
    <article class="pt-32 pb-12 bg-deep-earth">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-sm text-[#C4B9A6]">
                        <i class="far fa-clock"></i>
                        {{ $blog->reading_time ?? '8 min read' }}
                    </span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-light leading-tight mb-6 text-raw-linen">
                    {{ $blog->title }}
                </h1>

                @if($blog->excerpt)
                    <p
                        class="prose prose-invert text-xl text-[#C4B9A6] leading-relaxed mb-8 border-l-4 border-sunflare pl-6">
                        {{ $blog->description }}
                    </p>
                @endif

                <div
                    class="flex flex-wrap items-center justify-between gap-4 py-6 border-t border-b border-dust-mite mb-8">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-terracotta rounded-full flex items-center justify-center text-raw-linen font-bold text-lg">
                            {{ substr($blog->author->name ?? 'V', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-raw-linen">{{ $blog->author->name ?? 'Vumbi Ventures' }}</div>
                            <div class="text-sm text-[#C4B9A6]">{{ $blog->author->title ?? 'Storyteller' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-[#C4B9A6]">
                        {{-- Date and views can be added if you have those fields --}}
                    </div>
                </div>
            </div>
        </div>
    </article>

    {{-- Featured Media --}}
    @if($blog->media_path && $blog->is_image)
        <div class="container mx-auto px-6 -mt-8 mb-12">
            <div class="max-w-5xl mx-auto">
                <img src="{{ Storage::url($blog->media_path) }}" alt="{{ $blog->title }}"
                    class="w-full rounded-lg shadow-2xl object-cover max-h-[600px]" loading="lazy">
            </div>
        </div>
    @endif

    {{-- Main Content Area --}}
    <div class="container mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-12 gap-8">

            {{-- Sidebar --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-28 space-y-6">
                    {{-- Table of Contents (auto-generated) --}}
                    <div class="bg-indigo-night bg-opacity-30 border border-dust-mite p-6 rounded-lg"
                        x-data="{ tocItems: [] }" x-init="setTimeout(() => {
                             const headings = document.querySelectorAll('.blog-content h2, .blog-content h3');
                             headings.forEach((heading, idx) => {
                                 heading.id = heading.id || `heading-${idx}`;
                                 tocItems.push({
                                     text: heading.textContent,
                                     level: heading.tagName === 'H2' ? 2 : 3,
                                     id: heading.id
                                 });
                             });
                         }, 100)">
                        <h3 class="font-medium text-sunflare mb-4 flex items-center gap-2">
                            <i class="fas fa-list-ul"></i> Table of Contents
                        </h3>
                        <ul class="space-y-2 text-sm text-[#C4B9A6]">
                            <template x-for="item in tocItems">
                                <li>
                                    <a :href="'#' + item.id" x-text="item.text"
                                        :style="item.level === 3 ? 'padding-left: 1rem' : ''"
                                        class="block hover:text-sunflare transition"></a>
                                </li>
                            </template>
                        </ul>
                        <p x-show="tocItems.length === 0" class="text-sm text-[#C4B9A6]">No headings found</p>
                    </div>

                    {{-- Share Section --}}
                    <div class="bg-indigo-night bg-opacity-30 border border-dust-mite p-6 rounded-lg">
                        <h3 class="font-medium text-sunflare mb-4 flex items-center gap-2">
                            <i class="fas fa-share-alt"></i> Share this story
                        </h3>
                        <div class="flex gap-3">
                            <button wire:click="shareOnTwitter"
                                class="w-10 h-10 border border-dust-mite rounded-full flex items-center justify-center hover:bg-sunflare hover:border-sunflare hover:text-deep-earth transition text-[#C4B9A6] hover:text-deep-earth">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button wire:click="shareOnLinkedIn"
                                class="w-10 h-10 border border-dust-mite rounded-full flex items-center justify-center hover:bg-sunflare hover:border-sunflare hover:text-deep-earth transition text-[#C4B9A6] hover:text-deep-earth">
                                <i class="fab fa-linkedin-in"></i>
                            </button>
                            <button wire:click="shareOnFacebook"
                                class="w-10 h-10 border border-dust-mite rounded-full flex items-center justify-center hover:bg-sunflare hover:border-sunflare hover:text-deep-earth transition text-[#C4B9A6] hover:text-deep-earth">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button wire:click="copyToClipboard"
                                class="w-10 h-10 border border-dust-mite rounded-full flex items-center justify-center hover:bg-sunflare hover:border-sunflare hover:text-deep-earth transition text-[#C4B9A6] hover:text-deep-earth">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                        <div x-show="copyMessage" x-cloak class="text-xs text-sunflare mt-2 text-center">
                            Link copied to clipboard!
                        </div>
                        <div x-show="shareMessage" x-cloak class="text-xs text-sunflare mt-2 text-center"
                            x-text="shareMessageText"></div>
                    </div>

                    {{-- Newsletter Signup --}}
                    <div class="bg-terracotta p-6 rounded-lg text-raw-linen">
                        <h3 class="font-medium mb-2">Enjoying Field Notes?</h3>
                        <p class="text-sm text-raw-linen/80 mb-4">Get new stories delivered to your inbox monthly.</p>
                        <form wire:submit.prevent="subscribeFromArticle" class="flex flex-col gap-2">
                            <input type="email" wire:model="email" placeholder="Your email"
                                class="px-3 py-2 rounded-lg bg-raw-linen text-deep-earth focus:outline-none focus:ring-2 focus:ring-sunflare">
                            <button type="submit"
                                class="bg-sunflare text-deep-earth px-4 py-2 rounded-lg font-medium hover:bg-raw-linen transition">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Main Article Content --}}
            <div class="lg:col-span-6">
    <div class="prose prose-lg prose-invert max-w-none 
                prose-headings:text-raw-linen 
                prose-p:text-[#C4B9A6] prose-p:leading-relaxed prose-p:mb-5
                prose-strong:text-sunflare prose-strong:font-semibold
                prose-a:text-sunflare prose-a:no-underline hover:prose-a:underline
                prose-blockquote:border-l-4 prose-blockquote:border-sunflare prose-blockquote:pl-6 prose-blockquote:italic">
        {!! clean($blog->description) !!}
    </div>

                {{-- Author Bio (if available) --}}
                @if($blog->author && $blog->author->bio)
                    <div class="mt-12 pt-8 border-t border-dust-mite">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-16 h-16 bg-terracotta rounded-full flex items-center justify-center text-raw-linen text-2xl font-bold flex-shrink-0">
                                {{ substr($blog->author->name ?? 'V', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-medium text-raw-linen">About {{ $blog->author->name ?? 'the Author' }}</h4>
                                <p class="text-sm text-[#C4B9A6]">{{ $blog->author->bio }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SMART RECOMMENDATIONS SECTION --}}
    @if($dynamicHotels || $recommendedAffiliates->count() > 0)
        <section class="py-12 bg-indigo-night bg-opacity-30 border border-dust-mite rounded-2xl mt-12">
            <div class="container mx-auto px-6">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-light text-raw-linen mb-3">Recommended for This Story</h3>
                    <p class="text-[#C4B9A6]">Tailored experiences and booking options matched to this content</p>
                </div>

                <div class="grid lg:grid-cols-12 gap-8">

                    {{-- Dynamic Hotels from Travelpayouts API --}}
                    @if($dynamicHotels && count($dynamicHotels) > 0)
                        <div class="lg:col-span-7">
                            <h4 class="text-xl text-sunflare mb-6 flex items-center gap-2">
                                <i class="fas fa-hotel"></i> Hotels in {{ $detectedLocation }}
                            </h4>
                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach($dynamicHotels as $hotel)
                                    <div class="bg-deep-earth rounded-xl overflow-hidden border border-dust-mite">
                                        @if($hotel['image'] ?? false)
                                            <img src="{{ $hotel['image'] }}" alt="{{ $hotel['name'] ?? 'Hotel' }}"
                                                class="w-full h-48 object-cover">
                                        @endif
                                        <div class="p-5">
                                            <h5 class="font-medium text-raw-linen">{{ $hotel['name'] ?? 'Luxury Stay' }}</h5>
                                            <p class="text-sm text-[#C4B9A6]">{{ $hotel['price'] ?? 'Best rates available' }}</p>
                                            <a href="{{ $hotel['url'] ?? '#' }}" target="_blank" rel="nofollow sponsored"
                                                class="mt-4 block w-full text-center bg-sunflare text-deep-earth py-3 rounded-lg text-sm font-medium hover:bg-raw-linen transition">
                                                View & Book →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Static Affiliate Recommendations --}}
                    @if($recommendedAffiliates->count() > 0)
                        <div class="{{ $dynamicHotels ? 'lg:col-span-5' : 'lg:col-span-12' }}">
                            <h4 class="text-xl text-sunflare mb-6">More Ways to Explore</h4>
                            <div class="space-y-6">
                                @foreach($recommendedAffiliates as $aff)
                                    <div class="bg-deep-earth p-6 rounded-xl border border-dust-mite">
                                        <span
                                            class="text-xs uppercase tracking-widest text-terracotta">{{ strtoupper($aff->network) }}</span>
                                        <h5 class="font-medium text-raw-linen mt-2">{{ $aff->program_name }}</h5>
                                        <p class="text-sm text-[#C4B9A6] mt-1">{{ $aff->description }}</p>

                                        @if($aff->widget_code)
                                            <div class="mt-4">{!! $aff->widget_code !!}</div>
                                        @elseif($aff->affiliate_link)
                                            <a href="{{ $aff->affiliate_link }}" target="_blank" rel="nofollow sponsored"
                                                class="mt-4 inline-block bg-sunflare text-deep-earth px-6 py-3 rounded-lg text-sm font-medium">
                                                Explore Offers →
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <p class="text-center text-xs text-[#C4B9A6] mt-10">
                    These recommendations are dynamically matched to this story.
                    Affiliate links may earn us a small commission at no extra cost to you.
                </p>
            </div>
        </section>
    @endif

    {{-- Related Posts --}}
    @if($relatedPosts->count() > 0)
        <section class="py-12 bg-deep-earth">
            <div class="container mx-auto px-6">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-light text-raw-linen mb-3">You Might Also Enjoy</h2>
                    <p class="text-[#C4B9A6]">More stories from Vumbi Ventures</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related->id) }}"
                            class="group block bg-indigo-night bg-opacity-30 border border-dust-mite hover:border-sunflare rounded-xl overflow-hidden">
                            @if($related->media_path && $related->is_image)
                                <img src="{{ Storage::url($related->media_path) }}" alt="{{ $related->title }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition" loading="lazy">
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-light text-raw-linen group-hover:text-sunflare line-clamp-2">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-sm text-[#C4B9A6] line-clamp-3 mt-2">
                                    {{ Str::limit(strip_tags($related->excerpt ?? $related->description), 100) }}
                                </p>
                                <div
                                    class="mt-4 text-sunflare text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                                    Read More <span>→</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Comments Section (optional) --}}
    <section class="py-12 bg-indigo-night bg-opacity-20">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl mx-auto">
                <h3 class="text-2xl font-light text-raw-linen mb-6">Comments</h3>

                {{-- Comment Form --}}
                <div class="bg-indigo-night bg-opacity-30 border border-dust-mite p-6 rounded-lg mb-8">
                    <h4 class="font-medium text-sunflare mb-4">Leave a Comment</h4>
                    <form wire:submit.prevent="submitComment" class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" wire:model="commentName" placeholder="Your Name *"
                                    class="w-full bg-transparent border border-dust-mite px-4 py-2 text-raw-linen focus:border-sunflare focus:outline-none rounded">
                                @error('commentName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input type="email" wire:model="commentEmail" placeholder="Your Email *"
                                    class="w-full bg-transparent border border-dust-mite px-4 py-2 text-raw-linen focus:border-sunflare focus:outline-none rounded">
                                @error('commentEmail') <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <textarea wire:model="commentContent" rows="4" placeholder="Your Comment *"
                                class="w-full bg-transparent border border-dust-mite px-4 py-2 text-raw-linen focus:border-sunflare focus:outline-none rounded resize-none"></textarea>
                            @error('commentContent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="save-info" class="border border-dust-mite">
                            <label for="save-info" class="text-sm text-[#C4B9A6]">Save my name and email for next
                                time</label>
                        </div>
                        <button type="submit"
                            class="bg-terracotta text-raw-linen px-6 py-2 rounded hover:bg-sunflare hover:text-deep-earth transition font-medium">
                            Post Comment
                        </button>
                    </form>
                </div>

                <div class="bg-indigo-night bg-opacity-30 border border-dust-mite p-8 rounded-lg text-center">
                    <i class="fas fa-comments text-4xl text-sunflare mb-3"></i>
                    <p class="text-[#C4B9A6]">Be the first to comment on this story.</p>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>