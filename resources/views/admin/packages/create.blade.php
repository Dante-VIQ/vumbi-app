@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-12 text-white">
    <h1 class="text-3xl font-bold mb-8">New Partner Package</h1>
    <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-6 bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
        @csrf
        @include('admin.packages._form')
        <button type="submit" class="bg-green-600 hover:bg-green-500 px-6 py-2 rounded-xl font-medium">Create Package</button>
    </form>
</div>
@endsection