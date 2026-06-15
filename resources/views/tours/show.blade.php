@extends('layouts.app')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('og_title', $seo['title'])
@section('og_description', $seo['description'])
@section('og_image', $seo['og_image'])

@push('structured_data')
    {!! \App\Helpers\SchemaBuilder::touristAttraction($package) !!}
@endpush

@section('content')
    <div class="min-h-screen bg-zinc-950 text-white">
        <!-- Hero -->
        <div class="h-96 bg-cover bg-center relative"
            style="background-image: url('{{ $package->image ?: 'https://picsum.photos/1200/600?random=' . $package->id }}')">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-8 max-w-4xl mx-auto">
                <span
                    class="text-xs text-green-400 uppercase tracking-wide bg-zinc-900/80 px-3 py-1 rounded-full">{{ $package->type }}</span>
                <h1 class="text-4xl font-bold mt-2">{{ $package->title }}</h1>
                <p class="text-zinc-300 mt-2">{{ $package->location }}</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Quick Facts -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @if($package->duration_days)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold">{{ $package->duration_days }}D / {{ $package->duration_nights }}N
                            </div>
                            <div class="text-xs text-zinc-400 mt-1">Duration</div>
                        </div>
                    @endif
                    @if($package->difficulty)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold capitalize">{{ $package->difficulty }}</div>
                            <div class="text-xs text-zinc-400 mt-1">Difficulty</div>
                        </div>
                    @endif
                    @if($package->group_size_max)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold">{{ $package->group_size_min }}‑{{ $package->group_size_max }}</div>
                            <div class="text-xs text-zinc-400 mt-1">Group Size</div>
                        </div>
                    @endif
                    @if($package->vehicle_type)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold text-sm">{{ $package->vehicle_type }}</div>
                            <div class="text-xs text-zinc-400 mt-1">Vehicle</div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Description</h2>
                    <p class="text-zinc-300 leading-relaxed">{{ $package->description }}</p>
                </div>

                <!-- Itinerary -->
                {{-- @if(!empty($package->itinerary))
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Day‑by‑day Itinerary</h2>
                    <div class="space-y-4">
                        @foreach($package->itinerary as $index => $day)
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4">
                            <div class="text-green-400 font-semibold mb-1">Day {{ $index + 1 }}</div>
                            <p class="text-zinc-300">{{ $day }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif --}}

                @if(!empty($package->itinerary))
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Day‑by‑day Itinerary</h2>
                        <div class="space-y-4">
                            @foreach($package->itinerary as $index => $day)
                                @php
                                    // Split into title (first line) and description (rest)
                                    $lines = preg_split('/\r\n|\r|\n/', $day, 2);
                                    $title = $lines[0] ?? 'Day ' . ($index + 1);
                                    $day = $lines[1] ?? '';
                                @endphp
                                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4">
                                    <div class="text-green-400 font-semibold mb-1">
                                        Day {{ $index + 1 }}: {{ $title }}
                                    </div>
                                    @if($day)
                                        <p class="text-zinc-300 whitespace-pre-line">{{ $day }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Included / Excluded -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($package->included))
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-green-400 mb-3">What's Included</h3>
                            <ul class="space-y-2">
                                @foreach($package->included as $item)
                                    <li class="flex items-center gap-2 text-zinc-300">
                                        <span class="text-green-400">✓</span> {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(!empty($package->excluded))
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-red-400 mb-3">What's Excluded</h3>
                            <ul class="space-y-2">
                                @foreach($package->excluded as $item)
                                    <li class="flex items-center gap-2 text-zinc-300">
                                        <span class="text-red-400">✗</span> {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Similar Tours -->
                @if($similar->count())
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">You May Also Like</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($similar as $similarTour)
                                <a href="{{ route('tours.show', $similarTour) }}"
                                    class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-green-400/50 transition">
                                    <div class="h-32 bg-cover bg-center"
                                        style="background-image: url('{{ $similarTour->image ?: 'https://picsum.photos/200/150?random=' . $similarTour->id }}')">
                                    </div>
                                    <div class="p-3">
                                        <h4 class="font-medium text-sm">{{ $similarTour->title }}</h4>
                                        <span class="text-green-400 text-sm font-semibold mt-1 block">KSh
                                            {{ number_format($similarTour->price) }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Card (Sticky Booking) -->
            <div class="lg:sticky lg:top-8 h-fit">
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                    <div class="text-3xl font-bold text-green-400 mb-4">KSh {{ number_format($package->price) }}</div>
                    <p class="text-sm text-zinc-400 mb-6">Per person</p>

                    <a href="booking/{{ $partnerPackage->slug }}"
                        class="w-full bg-green-600 hover:bg-green-500 text-white py-3 rounded-xl font-medium mb-4 transition">
                        Book This Safari
                    </a>
                    <button
                        class="w-full bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 py-3 rounded-xl font-medium transition">
                        <a href="https://wa.me/254734591543?text=I'm%20interested%20in%20{{ urlencode($package->title) }}"
                            target="_blank" class="flex justify-center items-center gap-2">
                            💬 Ask on WhatsApp
                        </a>
                    </button>

                    <div class="border-t border-zinc-800 mt-6 pt-4 text-sm text-zinc-500 space-y-2">
                        @if($package->duration_days)
                            <p>Duration: {{ $package->duration_days }}D / {{ $package->duration_nights }}N</p>
                        @endif
                        @if($package->difficulty)
                            <p>Difficulty: {{ ucfirst($package->difficulty) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function dispatchBookingEvent(packageId) {
            document.dispatchEvent(new CustomEvent('open-booking', { detail: { packageId } }));
        }
    </script>
@endsection