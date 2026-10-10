@extends('layouts.app')

@section('title', 'Unsubscribe from Field Notes')
@section('robots', 'noindex, nofollow')

@section('content')
    <div class="bg-[#FCFAF7] text-[#1A1A1A] min-h-[60vh]">
        <div class="container mx-auto px-6 max-w-xl pt-28 pb-20 md:pt-36 text-center">
            @if ($state === 'confirm')
                <h1 class="text-3xl font-semibold mb-4">Unsubscribe from Field Notes?</h1>
                <p class="text-[#3A3A3A] mb-8 leading-relaxed">You will stop receiving our emails, and we will delete your address from our list.</p>
                <form method="POST" action="{{ request()->fullUrl() }}">
                    @csrf
                    <button type="submit" class="bg-[#8B5A2B] hover:bg-[#6B421F] text-white px-6 py-3 rounded-xl font-semibold transition">Yes, unsubscribe me</button>
                </form>
                <p class="mt-6 text-sm"><a href="{{ url('/') }}" class="text-[#8B5A2B] underline">No, take me back to the site</a></p>
            @else
                <h1 class="text-3xl font-semibold mb-4">You are unsubscribed</h1>
                <p class="text-[#3A3A3A] mb-8 leading-relaxed">Your address has been removed from our list. If this was a mistake, you can sign up again at any time.</p>
                <a href="{{ url('/blog') }}" class="text-[#8B5A2B] underline">Read Field Notes on the site</a>
            @endif
        </div>
    </div>
@endsection
