
                <a href="{{ route('destination.show', $destination) }}"
                    class="group bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden hover:border-green-400/50 transition hover:-translate-y-1">
                    <div class="h-48 bg-cover bg-center"
                        style="background-image: url('{{ $destination->image ?: 'https://picsum.photos/400/250?random=' . $destination->id }}')">
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-lg mt-1">{{ $destination->name }}</h3>
                        <p class="text-sm text-zinc-400 mt-1 line-clamp-2">{{ $destination->detail }}</p>
                        @if ($destination->location)
                            <p class="text-sm text-zinc-500 mt-2">{{ $destination->location }}</p>
                        @endif
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-zinc-500 text-sm">View Details →</span>
                        </div>
                    </div>
                </a>
