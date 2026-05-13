@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-12 text-white">
    <h1 class="text-3xl font-bold mb-8">Edit Package</h1>
    <form action="{{ route('packages.update', $package) }}" method="POST" class="space-y-6 bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
        @csrf @method('PUT')
        @include('admin.packages._form', ['package' => $package])
        <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-black px-6 py-2 rounded-xl font-medium">Update Package</button>
    </form>
</div>
@endsection