<?php

use Livewire\Component;
use App\Models\Destination;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public Destination $destination;
    public $selectedCategory = 'all';
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 9;

    protected $queryString = ['search', 'sortField', 'sortDirection', 'selectedCategory'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getDestinations()
    {
        $query = Destination::query()->latest();

        if ($this->selectedCategory !== 'all') {
            $query->where('location', $this->selectedCategory);
        }

        if (!empty($this->search)) {
            $search = '%' . $this->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('detail', 'like', $search);
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function getCategories()
    {
        return Destination::select('location')->distinct()->pluck('location')->toArray();
    }
};
?>

<div>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach ($this->getDestinations() as $destination)
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
    @endforeach
</div>

<div class="mt-8">
    {{ $this->getDestinations()->links() }}
</div>

</div>