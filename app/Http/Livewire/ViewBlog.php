<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Blog;
use App\Models\AffiliateProgram;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Services\AffiliateMatcher;
use App\Services\AffiliateExecutionService;

class ViewBlog extends Component
{
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
    public $email = ''; // newsletter subscription
    public $contentBlocks = [];
    public $affiliateResults = [];

    public function mount(Blog $blog)
    {
        $this->blog = $blog->load('author');

        $this->contentType = $this->detectContentType($this->blog);
        $this->detectedLocation = $this->detectLocation($this->blog);
        $this->contentBlocks = $this->splitContentIntoBlocks();

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
                    $terms = collect([$this->blog->category, $this->contentType, $this->blog->title, $this->blog->description ?? '', ...($this->blog->tags ?? [])])
                        ->filter()
                        ->map(fn($t) => strtolower(trim($t)));

                    foreach ($terms as $term) {
                        $query
                            ->orWhere('type', 'like', "%{$term}%")
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
        if (str_contains($text, 'culture') || str_contains($text, 'history') || str_contains($text, 'abubakari')) {
            return 'culture';
        }
        return $blog->category ?? 'general';
    }

    private function detectLocation(Blog $blog): ?string
    {
        $text = strtolower($blog->title . ' ' . ($blog->excerpt ?? $blog->description ?? '') . ' ' . implode(' ', $blog->tags ?? []));
        $map = [
            'maasai mara' => 'Maasai Mara',
            'diani' => 'Diani Beach',
            'nakuru' => 'Nakuru',
            'lamu' => 'Lamu',
            'kenya' => 'Kenya'
        ];

        foreach ($map as $key => $name) {
            if (str_contains($text, $key)) {
                return $name;
            }
        }
        return $this->contentType === 'destination' ? 'Kenya' : null;
    }

    // ==================== Actions ====================
    public function shareOnTwitter()
    {
        $this->emit('share', [
            'platform' => 'twitter',
            'url' => url()->current(),
            'title' => $this->blog->title,
        ]);
    }

    public function shareOnLinkedIn()
    {
        $this->emit('share', [
            'platform' => 'linkedin',
            'url' => url()->current(),
            'title' => $this->blog->title,
        ]);
    }

    public function shareOnFacebook()
    {
        $this->emit('share', [
            'platform' => 'facebook',
            'url' => url()->current(),
            'title' => $this->blog->title,
        ]);
    }

    public function copyToClipboard()
    {
        $this->emit('copy-link', ['url' => url()->current()]);
    }

    public function submitComment()
    {
        $this->validate([
            'commentName' => 'required|string|max:255',
            'commentEmail' => 'required|email|max:255',
            'commentContent' => 'required|string|min:3',
        ]);

        $this->emit('comment-submitted', ['message' => 'Your comment has been submitted for moderation.']);
        $this->reset(['commentName', 'commentEmail', 'commentContent']);
    }

    public function subscribeFromArticle()
    {
        $this->validate(['email' => 'required|email']);
        $this->emit('subscribed', ['message' => 'Thank you for subscribing to Field Notes!']);
        $this->email = '';
    }

    public function with(): array
    {
        return [
            'blog' => $this->blog,
        ];
    }

private function splitContentIntoBlocks(): array
{
    $rawContent = trim($this->blog->description ?? $this->blog->content ?? '');
    if (empty($rawContent)) {
        return [];
    }

    $html = $this->normalizeHeadings($rawContent);

    libxml_use_internal_errors(true);
    $dom = new \DOMDocument('1.0', 'UTF-8');
    
    // Wrap in container div with explicit UTF-8 encoding declaration
    $wrappedHtml = '<?xml encoding="utf-8" ?><div>' . $html . '</div>';
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
    $wrappedHtml = '<?xml encoding="utf-8" ?><div>' . $html . '</div>';
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
 
    public function render()
    {
        return view('livewire.view-blog');
    }
}
