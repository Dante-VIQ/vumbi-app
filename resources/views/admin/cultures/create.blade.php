@extends('admin.layouts.app')

@section('title', 'Add New Culture')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.cultures.index') }}" 
               class="inline-flex items-center text-gray-500 hover:text-gray-700 mb-4">
                ← Back to Cultures
            </a>
            <h1 class="text-3xl font-semibold text-gray-800">Add New Culture</h1>
            <p class="text-gray-600">Create a new cultural destination or experience</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm p-8">

            <form action="{{ route('admin.cultures.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Culture Name <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-[#8B5A2B] focus:border-transparent outline-none"
                               placeholder="e.g. Maasai Mara Cultural Dance">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="location" 
                               value="{{ old('location') }}"
                               class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-[#8B5A2B] focus:border-transparent outline-none"
                               placeholder="e.g. Narok County, Kenya">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Detail -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description / Detail <span class="text-red-500">*</span></label>
                        <textarea name="detail" 
                                  rows="6"
                                  class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-[#8B5A2B] focus:border-transparent outline-none"
                                  placeholder="Provide detailed information about this culture...">{{ old('detail') }}</textarea>
                        @error('detail')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        <input type="file" 
                               name="image" 
                               accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:bg-[#F5EFE6] file:text-[#8B5A2B] file:font-medium">
                        <p class="text-xs text-gray-500 mt-2">Recommended: JPG, PNG, WebP (Max 2MB)</p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button type="submit"
                                class="bg-[#8B5A2B] hover:bg-[#6B421F] text-white px-8 py-3.5 rounded-2xl font-medium transition flex-1 sm:flex-none">
                            Create Culture
                        </button>
                        
                        <a href="{{ route('admin.cultures.index') }}"
                           class="border border-gray-300 hover:bg-gray-50 px-8 py-3.5 rounded-2xl font-medium transition text-center flex-1 sm:flex-none">
                            Cancel
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
@endsection