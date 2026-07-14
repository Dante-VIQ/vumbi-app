<div>

    @if ($teaser)
        <div class="flex justify-between items-center mb-4 mt-12">
            <h3 class="text-lg font-semibold text-white">
                {{ $location ? "Culture & Stories from {$location}" : 'African Culture & Stories' }}
            </h3>
            <a href="{{ route('culture.index') }}" class="text-sm text-green-400 hover:text-green-300">View all →</a>
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($this->getCultures() as $culture)
            <a href="{{ route('culture.show', $culture) }}"
                class="group bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden hover:border-green-400/50 transition hover:-translate-y-1">
                <div class="h-48 bg-cover bg-center"
                    style="background-image: url('{{ $culture->image ?: 'https://picsum.photos/400/250?random=' . $culture->id }}')">
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-lg mt-1">{{ $culture->name }}</h3>
                    <p class="text-sm text-zinc-400 mt-1 line-clamp-2">{{ $culture->detail }}</p>
                    @if ($culture->location)
                        <p class="text-sm text-zinc-500 mt-2">{{ $culture->location }}</p>
                    @endif
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-zinc-500 text-sm">View Details →</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $this->getCultures()->links() }}
    </div>

</div>