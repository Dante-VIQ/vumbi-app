@php
    $founder = config('about.founder', []);
    $paras = ! empty($founder['note']) ? preg_split('/\n{2,}/', trim($founder['note'])) : [];
    $scene = $founder['scene'] ?? null;
@endphp
@if (! empty($founder['name']) && $paras)
    <section class="py-16 bg-[#FCFAF7]" aria-labelledby="founder-note-heading" id="founder-note">
        <div class="container mx-auto px-6 max-w-5xl grid md:grid-cols-5 gap-8 items-center">
            @if (! empty($founder['photo']))
                <div class="md:col-span-2">
                    <img src="{{ asset($founder['photo']) }}" alt="{{ $founder['name'] }}, {{ $founder['role'] ?? 'founder' }} of Vumbi Ventures" class="rounded-2xl w-full object-cover" loading="lazy" width="600" height="600">
                </div>
            @elseif (! empty($scene['image']))
                <figure class="md:col-span-2">
                    <img src="{{ $scene['image'] }}" alt="{{ $scene['alt'] ?? '' }}" class="rounded-2xl w-full h-64 md:h-80 object-cover" loading="lazy" width="600" height="400">
                    @if (! empty($scene['caption']))
                        <figcaption class="mt-2 text-xs text-gray-500">{{ $scene['caption'] }}@if (! empty($scene['credit'])) Photo: <a href="{{ $scene['credit_url'] ?? '#' }}" rel="noopener" class="underline">{{ $scene['credit'] }}</a> on Unsplash.@endif</figcaption>
                    @endif
                </figure>
            @endif
            <div class="{{ (! empty($founder['photo']) || ! empty($scene['image'])) ? 'md:col-span-3' : 'md:col-span-5' }}">
                <p class="text-xs font-bold tracking-wider text-[#8B5A2B] uppercase">A note from the founder</p>
                <h2 id="founder-note-heading" class="mt-2 text-2xl md:text-3xl font-bold text-[#1A1A1A]">Why Vumbi Ventures exists</h2>
                @foreach (array_slice($paras, 0, 3) as $para)
                    <p class="mt-4 text-gray-700 leading-relaxed">{{ $para }}</p>
                @endforeach
                <p class="mt-6 font-bold text-[#1A1A1A]">{{ $founder['name'] }}<span class="font-medium text-[#8B5A2B]">, {{ $founder['role'] ?? 'Founder' }}, Vumbi Ventures</span></p>
                <a href="{{ route('about') }}#founder" class="mt-3 inline-block text-sm font-semibold text-[#8B5A2B] underline" data-gtag-event="founder_note_click">Read more about us</a>
            </div>
        </div>
    </section>
@endif
