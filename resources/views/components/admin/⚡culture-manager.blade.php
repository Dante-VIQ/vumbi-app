<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Culture;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithPagination, WithFileUploads;

    // Form fields
    public $cultureId;
    public $name;
    public $location;
    public $detail;
    public $image;
    public $existingImage;

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
        $query = Culture::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
        }

        return [
            'cultures' => $query->latest()->paginate(10),
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(Culture $culture)
    {
        $this->cultureId = $culture->id;
        $this->name = $culture->name;
        $this->location = $culture->location;
        $this->detail = $culture->detail;
        $this->existingImage = $culture->image;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'detail' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $this->name,
            'location' => $this->location,
            'detail' => $this->detail,
        ];

        if ($this->image) {
            $path = $this->image->store('cultures', 'public');
            $data['image'] = $path;
        }

        if ($this->isEditing) {
            $culture = Culture::find($this->cultureId);
            if ($this->image && $culture->image) {
                Storage::disk('public')->delete($culture->image);
            }
            $culture->update($data);
            session()->flash('message', 'Culture updated.');
        } else {
            Culture::create($data);
            session()->flash('message', 'Culture created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(Culture $culture)
    {
        if ($culture->image) {
            Storage::disk('public')->delete($culture->image);
        }
        $culture->delete();
        session()->flash('message', 'Culture deleted.');
    }

    private function resetForm()
    {
        $this->reset(['cultureId', 'name', 'location', 'detail', 'image', 'existingImage', 'isEditing']);
    }
};
?>

<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Manage Cultures</h1>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        {{-- Search and Add --}}
        <div class="flex flex-wrap gap-4 items-center justify-between mb-6">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search cultures..."
                    class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#8B5A2B] focus:border-[#8B5A2B]">
                <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button wire:click="create" class="bg-[#8B5A2B] hover:bg-[#6B421F] text-white px-5 py-2 rounded-lg transition">
                + Add New Culture
            </button>
        </div>

        {{-- Form Modal --}}
        @if($showForm)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-4">{{ $isEditing ? 'Edit' : 'Add' }} Culture</h2>

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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Detail *</label>
                                <textarea wire:model="detail" rows="5"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#8B5A2B] focus:border-[#8B5A2B]"></textarea>
                                @error('detail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                @if($existingImage)
                                    <img src="{{ Storage::url($existingImage) }}" class="w-40 h-40 object-cover rounded-lg mb-2 border">
                                @endif
                                <input type="file" wire:model="image" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#F5EFE6] file:text-[#8B5A2B]">
                                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                        <th class="p-4 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cultures as $culture)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                @if($culture->image)
                                    <img src="{{ Storage::url($culture->image) }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400">—</div>
                                @endif
                            </td>
                            <td class="p-4 font-medium">{{ $culture->name }}</td>
                            <td class="p-4 text-gray-600">{{ $culture->location }}</td>
                            <td class="p-4">
                                <button wire:click="edit({{ $culture->id }})" class="text-blue-600 hover:underline mr-3">Edit</button>
                                <button wire:click="delete({{ $culture->id }})"
                                    onclick="return confirm('Delete this culture?')"
                                    class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-8 text-center text-gray-500">No cultures found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $cultures->links() }}
        </div>
    </div>
</div>