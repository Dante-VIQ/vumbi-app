<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\HeaderMedia;

new class extends Component
{
    use WithFileUploads;

    public $title;
    public $body;
    public $media;           // Livewire file upload
    public $location;

    protected $rules = [
        'title'   => 'required|string|max:255',
        'body'    => 'required|string',
        'media'   => 'required|file|max:20480', // 20MB max
        'location'=> 'nullable|string|max:255',
    ];
public function save()
{
    $validated = $this->validate();

    $mediaPath = null;
    $mediaType = null;

    if ($this->media) {
        // Store directly inside 'public/uploads/headers' (subfolder for organization)
        // The 'public_direct' disk should point to 'public' directory (default config)
        $storedPath = $this->media->store('uploads/headers', 'public_direct');
        
        // $storedPath will be something like 'uploads/headers/abc123.jpg'
        $mediaPath = 'uploads/' . $storedPath;   // No extra prepending!
        // $mediaType = $this->getMediaType($this->media->getClientOriginalExtension());
    }

    HeaderMedia::create([
        'title'       => $validated['title'],
        'body'        => $validated['body'],
        'media_path'  => $mediaPath,
        'media_type'  => $mediaType,
        'location'    => $validated['location'],
        'user_id'     => Auth::id(),
    ]);

    $this->resetForm();
    session()->flash('success', 'Header media uploaded successfully!');
    $this->dispatch('headerMediaPosted');
}

    private function getMediaType(string $extension): string
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $videoExtensions)) {
            return 'video';
        }

        return 'other';
    }

    private function resetForm()
    {
        $this->reset(['title', 'body', 'media', 'location']);
    }
};
?>

<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-[#8B5A2B]">Create New Header Media</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Title</label>
                <input type="text" wire:model="title" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8B5A2B]">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Body -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Description / Body</label>
                <textarea wire:model="body" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8B5A2B]"></textarea>
                @error('body') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Media Upload -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">Media (Image or Video)</label>
                <input type="file" wire:model="media" accept="image/*,video/*"
                       class="w-full border border-gray-300 rounded-xl p-3">
                @error('media') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                
                @if($media)
                    <div class="mt-3 text-sm text-green-600">
                        Selected: {{ $media->getClientOriginalName() }}
                    </div>
                @endif
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-medium mb-2">Location (Optional)</label>
                <input type="text" wire:model="location" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8B5A2B]">
            </div>

        </div>

        <button type="submit"
                class="mt-8 w-full bg-[#8B5A2B] hover:bg-[#6B421F] text-white font-medium py-4 rounded-2xl transition flex items-center justify-center gap-2"
                wire:loading.attr="disabled">
            <span wire:loading.remove>Upload Header Media</span>
            <span wire:loading>Processing...</span>
        </button>
    </form>
</div>