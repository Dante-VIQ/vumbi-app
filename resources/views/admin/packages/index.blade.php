@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 text-white">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Partner Packages</h1>
        <a href="{{ route('packages.create') }}" class="bg-green-600 hover:bg-green-500 px-5 py-2 rounded-xl text-sm font-medium">+ New Package</a>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500 text-green-400 p-4 rounded-xl mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-zinc-800">
                <tr>
                    <th class="p-4 font-medium">Title</th>
                    <th class="p-4">Location</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Price</th>
                    <th class="p-4">Active</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packages as $pkg)
                <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30">
                    <td class="p-4">{{ $pkg->title }}</td>
                    <td class="p-4">{{ $pkg->location }}</td>
                    <td class="p-4 capitalize">{{ $pkg->type }}</td>
                    <td class="p-4">KSh {{ number_format($pkg->price) }}</td>
                    <td class="p-4">{{ $pkg->active ? '✅' : '❌' }}</td>
                    <td class="p-4">
                        <a href="{{ route('packages.edit', $pkg) }}" class="text-amber-400 hover:underline mr-3">Edit</a>
                        <form action="{{ route('packages.destroy', $pkg) }}" method="POST" class="inline" onsubmit="return confirm('Delete this package?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $packages->links() }}</div>
</div>
@endsection