@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-zinc-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold mb-6">Destinations</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach ($destinations as $destination)
                    <x-destination-card :destination="$destination" />
                @endforeach
            </div>

            {{-- <div class="mt-8">
        {{ $destinations->links() }}
        </div> --}}
        </div>
    </div>
@endsection
