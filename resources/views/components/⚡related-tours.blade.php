<?php

use Livewire\Component;
use App\Models\PartnerPackage;

new class extends Component
{
    public string $blogTitle;
    public ?string $location = null;
    public int $limit = 3;
    public ?int $excludeTourId = null;

    public $tours;

    private const STOPWORDS = [
        'the','and','for','with','from','that','this','your','you','are','was','were',
        'how','why','what','when','where','who','which','into','onto','over','under',
        'about','after','before','between','through','during','without','within',
        'guide','travel','trip','story','stories','notes','field','blog','part',
        'complete','ultimate','essential','everything','need','know','actual',
        'actually','really','just','more','most','best','top','2025','2026',
        'to','of','in','on','at','by','is','it','its','as','or','be','has','have',
        'not','no','can','will','one','all','out','up','down','off',
    ];

    public function mount(string $blogTitle, ?string $location = null, int $limit = 3, ?int $excludeTourId = null)
    {
        $this->blogTitle = $blogTitle;
        $this->location = $location;
        $this->limit = $limit;
        $this->excludeTourId = $excludeTourId;

        $keywords = $this->extractKeywords($blogTitle);

        if (empty($keywords) && !$location) {
            $this->tours = collect();
            return;
        }

        $query = PartnerPackage::query()->where('active', true);

        if ($this->excludeTourId) {
            $query->where('id', '!=', $this->excludeTourId);
        }

        // Score each tour: title matches count double weight, region matches
        // count once. Proper-noun-looking keywords (Chale, Wote, Kilimanjaro,
        // Zanzibar...) are themselves weighted 2x over generic words, since
        // those are the ones most likely to also appear in a tour's title.
        $scoreParts = [];
        $bindings = [];

        foreach ($keywords as $word => $weight) {
            $like = '%' . $word . '%';

            $scoreParts[] = "(CASE WHEN title LIKE ? THEN ? ELSE 0 END)";
            $bindings[] = $like;
            $bindings[] = $weight * 2;

            $scoreParts[] = "(CASE WHEN region LIKE ? THEN ? ELSE 0 END)";
            $bindings[] = $like;
            $bindings[] = $weight;
        }

        if ($location) {
            $scoreParts[] = "(CASE WHEN region LIKE ? THEN 3 ELSE 0 END)";
            $bindings[] = '%' . $location . '%';
        }

        $scoreSql = implode(' + ', $scoreParts) ?: '0';

        $query->selectRaw("*, ({$scoreSql}) as match_score", $bindings)
            ->havingRaw('match_score > 0');

        $this->tours = $query
            ->orderByDesc('match_score')
            ->orderByDesc('location')
            ->take($this->limit)
            ->get();
    }

    private function extractKeywords(string $title): array
    {
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $title);
        $words = preg_split('/\s+/', trim($clean));

        $keywords = [];
        foreach ($words as $word) {
            if ($word === '' || mb_strlen($word) < 4 || ctype_digit($word)) {
                continue;
            }

            $lower = mb_strtolower($word);
            if (in_array($lower, self::STOPWORDS, true)) {
                continue;
            }

            $isProperNoun = mb_substr($word, 0, 1) === mb_strtoupper(mb_substr($word, 0, 1));
            $weight = $isProperNoun ? 2 : 1;

            $keywords[$lower] = max($keywords[$lower] ?? 0, $weight);
        }

        arsort($keywords);
        return array_slice($keywords, 0, 8, true);
    }
};
?>

@if($tours->isNotEmpty())
<div class="related-tours-block my-12">
    <h3 class="text-xl font-semibold text-white mb-1">Turn This Story Into a Trip</h3>
    <p class="text-zinc-500 text-sm mb-6">Trips related to this story.</p>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-5">
        @foreach($tours as $tour)
            <div class="tour-card bg-zinc-950 border border-zinc-800 rounded-2xl overflow-hidden flex flex-col">
                @if($tour->image_url)
                    <img src="{{ $tour->image_url }}" alt="{{ $tour->title }}" class="w-full h-40 object-cover">
                @endif

                <div class="p-4 flex flex-col flex-1">
                    <h4 class="text-white font-medium mb-1">{{ $tour->title }}</h4>

                    @if($tour->price_from)
                        <p class="text-green-400 text-sm mb-3">From {{ $tour->price_from }}</p>
                    @endif

                    <div class="mt-auto">
                        @if($tour->isAffiliate())
                            <a href="{{ route('affiliate.redirect', [$tour->affiliate_source, $tour->id]) }}"
                               target="_blank"
                               class="block text-center bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 rounded-xl transition">
                                Book This Trip →
                            </a>
                        @else
                            <button
                                onclick="window.dispatchEvent(new CustomEvent('open-booking-modal', { detail: { tourId: {{ $tour->id }} } }))"
                                class="block w-full text-center bg-zinc-800 hover:bg-zinc-700 text-white text-sm font-medium py-2 rounded-xl transition">
                                Request to Book →
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif