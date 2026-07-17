<?php

use Livewire\Component;

new class extends Component
{
    public string $title;
    public int $limit = 3;
    public ?int $excludeTourId = null; // avoid showing a tour on its own page if reused there

    public function mount()
    {
        $tours = App\Models\PartnerPackage::where('region', $this->title)
            ->where('is_active', true)
            ->when($this->excludeTourId, fn ($q) => $q->where('id', '!=', $this->excludeTourId))
            ->orderByDesc('popularity_score')
            ->take($this->limit)
            ->get();
    }
};
?>

@if($tours->isNotEmpty())
<div class="related-tours-block my-12">
    <h3 class="text-xl font-semibold text-white mb-1">
        Visit {{ ucfirst($title) }} — See These Trips
    </h3>
    <p class="text-zinc-500 text-sm mb-6">Turn this story into a trip.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($partner_packages as $partner_package)
            <div class="tour-card bg-zinc-950 border border-zinc-800 rounded-2xl overflow-hidden flex flex-col">
                @if($partner_package->image_url)
                    <img src="{{ $partner_package->image_url }}" alt="{{ $partner_package->title }}"
                         class="w-full h-40 object-cover">
                @endif

                <div class="p-4 flex flex-col flex-1">
                    <h4 class="text-white font-medium mb-1">{{ $partner_package->title }}</h4>

                    @if($partner_package->price_from)
                        <p class="text-green-400 text-sm mb-3">From {{ $partner_package->price_from }}</p>
                    @endif

                    <div class="mt-auto">
                        @if($partner_package->isAffiliate())
                            <a href="{{ route('affiliate.redirect', [$partner_package->affiliate_source, $partner_package->id]) }}"
                               target="_blank"
                               class="block text-center bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 rounded-xl transition">
                                Book This Trip →
                            </a>
                        @else
                            <button
                                onclick="window.dispatchEvent(new CustomEvent('open-booking-modal', { detail: { tourId: {{ $partner_package->id }} } }))"
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