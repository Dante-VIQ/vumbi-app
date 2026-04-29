@php
    $headerMedia = App\Models\HeaderMedia::latest()->get();
   @endphp

<div class="swiper-wrapper">
    @if (!empty($headerMedias) && count($headerMedias) > 0)
        @foreach ($headerMedia as $media)
            <div class="swiper-slide glass-panel rounded-[2.5rem] p-5 md:p-6 shadow-2xl float max-w-md w-full">
                {{-- Replace with actual destination image or video --}}
                <img src="{{ asset($media->media_path) }}" alt="{{ $media->title }}"
                    class="rounded-3xl w-full h-[380px] md:h-[420px] object-cover shadow-inner" loading="eager" width="600"
                    height="420">
                <div class="mt-5 text-center px-2">
                    <p class="font-semibold text-[#1A1A1A] text-lg">{{ $media->title }}</p>
                    <a href="/discover?search={{ urlencode($media['title']) }}"
                        class="inline-block mt-3 text-sm font-medium text-[#8B5A2B] hover:underline">View deal →</a>
                </div>
            </div>
        @endforeach
    @else
        <!-- Fallback if no images -->
        <div class="swiper-slide">
            <div
                class="w-full h-64 sm:h-80 md:h-96 lg:h-[500px] bg-gradient-to-r from-blue-900 to-gray-800 flex items-center justify-center rounded-2xl">
                <div class="text-center text-white p-4 sm:p-8">
                    <i class="fas fa-camera text-4xl sm:text-6xl mb-4 sm:mb-6 opacity-50"></i>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2 sm:mb-4">Gallery Images Coming Soon</h2>
                    <p class="opacity-75 text-sm sm:text-lg">Explore our gallery for amazing visuals</p>
                    <div class="mt-4 sm:mt-6">
                        <a href="/blog"
                            class="inline-block px-4 sm:px-6 py-2 sm:py-3 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100">
                            View Gallery
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>