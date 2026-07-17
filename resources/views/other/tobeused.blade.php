                            @if ($this->shouldInsertWidget($index))
                                {{-- Inline Monetization Widget --}}
                                <div
                                    class="not-prose my-8 p-6 bg-white rounded-2xl border border-[#8B5A2B]/20 shadow-sm">
                                    <p class="text-sm font-medium text-[#8B5A2B] uppercase tracking-wider mb-3">📍 While
                                        You're Here</p>
                                    <div class="grid sm:grid-cols-2 gap-4">
                                        @forelse($affiliateResults['inline'] ?? [] as $offer)
                                            <a href="{{ $offer['url'] }}" target="_blank" rel="nofollow sponsored"
                                                class="block p-4 bg-[#FCFAF7] rounded-xl border border-black/5 hover:border-[#8B5A2B]/30 hover:shadow-md transition group">
                                                @if (!empty($offer['image']))
                                                    <img src="{{ $offer['image'] }}" alt="{{ $offer['title'] }}"
                                                        class="w-full h-32 object-cover rounded-lg mb-3">
                                                @endif
                                                <h4 class="font-semibold text-[#1A1A1A] group-hover:text-[#8B5A2B]">
                                                    {{ $offer['title'] }}</h4>
                                                <p class="text-sm text-[#5C5C5C] mt-1">
                                                    {{ $offer['description'] ?? '' }}</p>
                                                <span class="inline-block mt-2 text-sm font-medium text-[#8B5A2B]">View
                                                    Deal →</span>
                                            </a>
                                        @empty
                                            {{-- Fallback static affiliate (e.g., Booking.com) --}}
                                            <a href="#"
                                                class="block p-4 bg-[#FCFAF7] rounded-xl border border-black/5 hover:border-[#8B5A2B]/30 hover:shadow-md transition">
                                                <h4 class="font-semibold">Find Hotels in
                                                    {{ $detectedLocation ?? 'Kenya' }}</h4>
                                                <p class="text-sm text-[#5C5C5C] mt-1">Search and compare prices from
                                                    top booking sites.</p>
                                                <span
                                                    class="inline-block mt-2 text-sm font-medium text-[#8B5A2B]">Search
                                                    Now →</span>
                                            </a>
                                        @endforelse
                                    </div>
                                </div>
                            @endif