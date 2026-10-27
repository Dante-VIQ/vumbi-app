<div {{ $attributes->class('bg-white rounded-2xl border border-black/5 p-5 shadow-sm') }}>
    <h3 class="font-semibold text-lg text-zinc-900 mb-2">Find a trip that fits you</h3>
    <div class="flex flex-wrap gap-2">
        @foreach (\App\Support\TripStyles::all() as $slug => $ts)
            <a href="{{ route('trips.show', $slug) }}" data-gtag-event="persona_card_click" data-gtag-params="{{ json_encode(['style' => $slug, 'placement' => 'field_notes']) }}"
                class="px-3 py-1.5 rounded-full border border-black/10 text-sm hover:border-[#8B5A2B] hover:text-[#8B5A2B] transition">{{ $ts['name'] }}</a>
        @endforeach
    </div>
</div>
