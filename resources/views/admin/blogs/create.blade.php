{{-- resources/views/admin/blogs/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create Blog Post - Vumbi Ventures Admin')
@section('header', 'Create New Blog Post')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('blogs.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        {{-- Title --}}
        <div class="bg-indigo-night bg-opacity-30 p-6 border border-dust-mite rounded">
            <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Post Title</label>
            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   class="w-full bg-transparent border border-dust-mite p-4 text-raw-linen focus:border-sunflare focus:outline-none @error('title') border-red-500 @enderror"
                   placeholder="Enter an engaging title...">
            @error('title')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div class="bg-indigo-night bg-opacity-30 p-6 border border-dust-mite rounded">
            <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Category</label>
            <div class="grid md:grid-cols-3 gap-4">
                @php
                    $defaultCategories = ['people', 'culture', 'destinations'];
                    $existingCategories = $categories->toArray();
                    $allCategories = array_unique(array_merge($defaultCategories, $existingCategories));
                @endphp

                @foreach($allCategories as $cat)
                    <label class="flex items-center gap-3 p-4 border border-dust-mite cursor-pointer hover:border-sunflare transition">
                        <input type="radio"
                               name="category"
                               value="{{ $cat }}"
                               {{ old('category') == $cat ? 'checked' : '' }}
                               class="appearance-none w-4 h-4 border border-dust-mite checked:bg-sunflare checked:border-sunflare">
                        <span class="text-raw-linen">{{ ucfirst($cat) }}</span>
                    </label>
                @endforeach
            </div>
            @error('category')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror

            {{-- New Category --}}
            <div class="mt-4 pt-4 border-t border-dust-mite">
                <label class="flex items-center gap-3">
                    <input type="checkbox"
                           id="newCategoryCheck"
                           class="appearance-none w-4 h-4 border border-dust-mite checked:bg-sunflare checked:border-sunflare">
                    <span class="text-raw-linen">Add new category</span>
                </label>

                <div id="newCategoryInput" class="mt-3 hidden">
                    <input type="text"
                           name="new_category"
                           value="{{ old('new_category') }}"
                           class="w-full bg-transparent border border-dust-mite p-3 text-raw-linen focus:border-sunflare focus:outline-none"
                           placeholder="Enter new category name...">
                </div>
            </div>
        </div>

        {{-- Media Upload --}}
        <div class="bg-indigo-night bg-opacity-30 p-6 border border-dust-mite rounded"
             x-data="{
                mediaType: 'image',
                preview: null,
                previewUrl: null
             }">
            <label class="block text-sunflare text-sm uppercase tracking-wider mb-4">Featured Media</label>

            {{-- Media Type Selection --}}
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <label class="flex items-center gap-3 p-4 border border-dust-mite cursor-pointer hover:border-sunflare transition">
                    <input type="radio"
                           x-model="mediaType"
                           value="image"
                           name="media_type"
                           class="appearance-none w-4 h-4 border border-dust-mite checked:bg-sunflare checked:border-sunflare">
                    <span class="text-raw-linen">Image</span>
                </label>

                <label class="flex items-center gap-3 p-4 border border-dust-mite cursor-pointer hover:border-sunflare transition">
                    <input type="radio"
                           x-model="mediaType"
                           value="video"
                           name="media_type"
                           class="appearance-none w-4 h-4 border border-dust-mite checked:bg-sunflare checked:border-sunflare">
                    <span class="text-raw-linen">Video</span>
                </label>
            </div>

            {{-- File Upload --}}
            <div class="border-2 border-dashed border-dust-mite p-8 text-center"
                 @dragover.prevent="$event.currentTarget.classList.add('border-sunflare')"
                 @dragleave.prevent="$event.currentTarget.classList.remove('border-sunflare')"
                 @drop.prevent="
                    $event.currentTarget.classList.remove('border-sunflare');
                    const file = $event.dataTransfer.files[0];
                    $refs.fileInput.files = $event.dataTransfer.files;

                    if(mediaType === 'image') {
                        const reader = new FileReader();
                        reader.onload = (e) => previewUrl = e.target.result;
                        reader.readAsDataURL(file);
                    }
                 ">

                <input type="file"
                       x-ref="fileInput"
                       name="media"
                       accept="{{ old('media_type', 'image') === 'image' ? 'image/*' : 'video/*' }}"
                       @change="
                            const file = $event.target.files[0];
                            if(mediaType === 'image' && file) {
                                const reader = new FileReader();
                                reader.onload = (e) => previewUrl = e.target.result;
                                reader.readAsDataURL(file);
                            }
                       "
                       class="hidden">

                <template x-if="!previewUrl">
                    <div>
                        <i class="fas fa-cloud-upload-alt text-4xl text-sunflare mb-4"></i>
                        <p class="text-raw-linen mb-2">Drag and drop your file here, or</p>
                        <button type="button"
                                @click="$refs.fileInput.click()"
                                class="bg-terracotta px-6 py-3 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition">
                            Browse Files
                        </button>
                        <p class="text-sm text-[#C4B9A6] mt-2">Max file size: 20MB</p>
                    </div>
                </template>

                <template x-if="previewUrl">
                    <div>
                        <img :src="previewUrl" class="max-h-64 mx-auto mb-4">
                        <button type="button"
                                @click="previewUrl = null; $refs.fileInput.value = ''"
                                class="border border-red-500 px-4 py-2 text-red-500 hover:bg-red-500 hover:text-raw-linen transition">
                            <i class="fas fa-times mr-2"></i>Remove
                        </button>
                    </div>
                </template>
            </div>

            @error('media')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
            @error('media_type')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Content --}}
        <div class="bg-indigo-night bg-opacity-30 p-6 border border-dust-mite rounded">
            <label class="block text-sunflare text-sm uppercase tracking-wider mb-2">Content</label>
            <textarea name="description"
                      rows="12"
                      class="w-full bg-transparent border border-dust-mite p-4 text-raw-linen focus:border-sunflare focus:outline-none font-mono @error('description') border-red-500 @enderror"
                      placeholder="Write your blog post content here... (HTML supported)">{{ old('description') }}</textarea>
            <p class="text-sm text-[#C4B9A6] mt-2">HTML is supported for rich formatting.</p>
            @error('description')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('blogs.index') }}"
               class="border border-dust-mite px-6 py-3 text-raw-linen hover:bg-terracotta transition">
                Cancel
            </a>
            <button type="submit"
                    class="bg-terracotta px-8 py-3 text-raw-linen hover:bg-sunflare hover:text-deep-earth transition flex items-center gap-2">
                <i class="fas fa-save"></i>
                Publish Post
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Toggle new category input
        document.getElementById('newCategoryCheck').addEventListener('change', function(e) {
            const input = document.getElementById('newCategoryInput');
            const radios = document.querySelectorAll('input[name="category"]');

            if(e.target.checked) {
                input.classList.remove('hidden');
                radios.forEach(radio => radio.disabled = true);
            } else {
                input.classList.add('hidden');
                radios.forEach(radio => radio.disabled = false);
            }
        });

        // Update file input accept type when media type changes
        document.querySelectorAll('input[name="media_type"]').forEach(radio => {
            radio.addEventListener('change', function(e) {
                const fileInput = document.querySelector('input[name="media"]');
                fileInput.accept = this.value === 'image' ? 'image/*' : 'video/*';
            });
        });
    });
</script>
@endpush
@endsection
