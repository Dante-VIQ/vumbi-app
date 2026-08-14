@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 text-white">
    <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 class="text-3xl font-bold">Booking Leads</h1>
        
        <div class="flex gap-4 flex-wrap">
            {{-- Export CSV Button --}}
            <a href="{{ route('leads.export', request()->query()) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>

            {{-- Export Excel (Optional) --}}
            <a href="{{ route('leads.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    {{-- Rest of your leads table --}}
    ...
</div>
@endsection