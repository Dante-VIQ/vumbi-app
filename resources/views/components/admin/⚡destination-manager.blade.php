<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Destination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

new class extends Component {
    use WithPagination, WithFileUploads;

    // Form fields
    public $destinationId;
    public $name;
    public $location;
    public $detail;
    public $price_range;
    public $best_time_to_visit;
    public $featured = false;
    public $media;
    public $existingMedia;

    // UI state
    public $isEditing = false;
    public $showForm = false;
    public $search = '';

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Provide paginated data to the view
    public function with(): array
    {
        $query = Destination::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
        }

        return [
            'destinations' => $query->latest()->paginate(10),
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(Destination $destination)
    {
        $this->destinationId = $destination->id;
        $this->name = $destination->name;
        $this->location = $destination->location;
        $this->detail = $destination->detail;
        $this->price_range = $destination->price_range;
        $this->best_time_to_visit = $destination->best_time_to_visit;
        $this->featured = $destination->featured;
        $this->existingMedia = $destination->media_path;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'detail' => 'required|string',
            'price_range' => 'nullable|string|max:100',
            'best_time_to_visit' => 'nullable|string|max:255',
            'media' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'location' => $this->location,
            'detail' => $this->detail,
            'price_range' => $this->price_range,
            'best_time_to_visit' => $this->best_time_to_visit,
            'featured' => $this->featured,
        ];

        if ($this->media) {
            $path = $this->media->store('destinations', 'public');
            $data['media_path'] = $path;
        }

        if ($this->isEditing) {
            $destination = Destination::find($this->destinationId);
            if ($this->media && $destination->media_path) {
                Storage::disk('public')->delete($destination->media_path);
            }
            $destination->update($data);
            session()->flash('message', 'Destination updated.');
        } else {
            Destination::create($data);
            session()->flash('message', 'Destination created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(Destination $destination)
    {
        if ($destination->media_path) {
            Storage::disk('public')->delete($destination->media_path);
        }
        $destination->delete();
        session()->flash('message', 'Destination deleted.');
    }

    public function toggleFeatured(Destination $destination)
    {
        $destination->update(['featured' => !$destination->featured]);
    }

    private function resetForm()
    {
        $this->reset([
            'destinationId', 'name', 'location', 'detail',
            'price_range', 'best_time_to_visit', 'featured',
            'media', 'existingMedia', 'isEditing'
        ]);
    }
};
?>

<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Manage Destinations</h1>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        {{-- Search and Add --}}
        <div class="flex flex-wrap gap-4 items-center justify-between mb-6">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search destinations..."
                    class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#8B5A2B] focus:border-[#8B5A2B]">
                <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button wire:click="create" class="bg-[#8B5A2B] hover:bg-[#6B421F] text-white px-5 py-2 rounded-lg transition">
                + Add New Destination
            </button>
        </div>

        {{-- Form Modal --}}
        @if($showForm)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-4">{{ $isEditing ? 'Edit' : 'Add' }} Destination</h2>

                        <form wire:submit.prevent="save" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#8B5A2B] focus:border-[#8B5A2B]">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location *</label>
                                <input type="text" wire:model="location"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#8B5A2B] focus:border-[#8B5A2B]">
                                @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                <textarea wire:model="detail" rows="5"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#8B5A2B] focus:border-[#8B5A2B]"></textarea>
                                @error('detail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                                <input type="text" wire:model="price_range" placeholder="e.g., $100 - $300 per night"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Best Time to Visit</label>
                                <input type="text" wire:model="best_time_to_visit" placeholder="e.g., June - October"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="featured" id="featured"
                                    class="w-4 h-4 text-[#8B5A2B] border-gray-300 rounded">
                                <label for="featured" class="ml-2 text-sm text-gray-700">Featured destination</label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                @if($existingMedia)
                                    <img src="{{ Storage::url($existingMedia) }}" class="w-40 h-40 object-cover rounded-lg mb-2 border">
                                @endif
                                <input type="file" wire:model="media" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#F5EFE6] file:text-[#8B5A2B]">
                                @error('media') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex gap-3 pt-4 border-t">
                                <button type="submit" class="bg-[#8B5A2B] hover:bg-[#6B421F] text-white px-6 py-2 rounded-lg">
                                    {{ $isEditing ? 'Update' : 'Create' }}
                                </button>
                                <button type="button" wire:click="$set('showForm', false)"
                                    class="border border-gray-300 hover:bg-gray-50 px-6 py-2 rounded-lg">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Image</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Name</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Location</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Featured</th>
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $destination)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                @if($destination->media_path)
                                    <img src="{{ Storage::url($destination->media_path) }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400">—</div>
                                @endif
                            </td>
                            <td class="p-4 font-medium">{{ $destination->name }}</td>
                            <td class="p-4 text-gray-600">{{ $destination->location }}</td>
                            <td class="p-4">
                                <button wire:click="toggleFeatured({{ $destination->id }})" class="text-lg">
                                    @if($destination->featured)
                                        <span class="text-yellow-500">★</span>
                                    @else
                                        <span class="text-gray-300">☆</span>
                                    @endif
                                </button>
                            </td>
                            <td class="p-4">
                                <button wire:click="edit({{ $destination->id }})" class="text-blue-600 hover:underline mr-3">Edit</button>
                                <button wire:click="delete({{ $destination->id }})"
                                    onclick="return confirm('Delete this destination?')"
                                    class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-500">No destinations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $destinations->links() }}
        </div>
    </div>
</div>