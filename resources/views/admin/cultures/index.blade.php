@extends('admin.layouts.app')

@section('title', 'Manage Cultures')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Cultures Management</h1>
                <p class="text-gray-600 mt-1">Manage all cultural destinations and experiences</p>
            </div>
            
            <a href="{{ route('admin.cultures.create') }}" 
               class="bg-[#8B5A2B] hover:bg-[#6B421F] text-white px-6 py-3 rounded-xl flex items-center gap-2 transition shadow-sm">
                <span class="text-xl leading-none">+</span>
                <span>Add New Culture</span>
            </a>
        </div>

        {{-- Success Message --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-2xl mb-6 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Search + Filter -->
        <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
            <form method="GET" action="{{ route('admin.cultures.index') }}" class="flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[280px]">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by name or location..." 
                               class="w-full px-5 py-3 pl-12 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-[#8B5A2B] focus:border-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 absolute left-5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <button type="submit"
                        class="px-8 py-3 bg-gray-800 text-white rounded-2xl hover:bg-gray-900 transition">
                    Search
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.cultures.index') }}" 
                       class="text-gray-500 hover:text-gray-700 font-medium">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-5 text-left text-sm font-medium text-gray-600">Image</th>
                            <th class="px-6 py-5 text-left text-sm font-medium text-gray-600">Culture Name</th>
                            <th class="px-6 py-5 text-left text-sm font-medium text-gray-600">Location</th>
                            <th class="px-6 py-5 text-left text-sm font-medium text-gray-600">Detail</th>
                            <th class="px-6 py-5 text-center text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cultures as $culture)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Image -->
                                <td class="px-6 py-4">
                                    @if($culture->image)
                                        <img src="{{ Storage::url($culture->image) }}" 
                                             alt="{{ $culture->name }}"
                                             class="w-14 h-14 object-cover rounded-2xl border border-gray-100">
                                    @else
                                        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 text-xs">
                                            No Image
                                        </div>
                                    @endif
                                </td>

                                <!-- Name -->
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">{{ $culture->name }}</div>
                                </td>

                                <!-- Location -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 text-gray-600">
                                        <span class="text-[#8B5A2B]">📍</span>
                                        {{ $culture->location }}
                                    </span>
                                </td>

                                <!-- Detail -->
                                <td class="px-6 py-4">
                                    <p class="text-gray-600 text-sm line-clamp-2 max-w-xs">
                                        {{ Str::limit($culture->detail, 90) }}
                                    </p>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-4">
                                        <a href="{{ route('admin.cultures.edit', $culture) }}" 
                                           class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                            <span>✏️</span> Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.cultures.destroy', $culture) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this culture?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-700 font-medium flex items-center gap-1">
                                                <span>🗑️</span> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="text-gray-400 text-xl mb-2">🌿</div>
                                    <p class="text-gray-500">No cultures found</p>
                                    @if(request('search'))
                                        <p class="text-sm text-gray-400 mt-1">Try adjusting your search term</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $cultures->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection