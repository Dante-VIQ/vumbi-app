@extends('layouts.app')

@section('title', 'FAQ: What Is Vumbi Ventures and How Do Trips Work? | Vumbi Ventures')
@section('description', 'What is Vumbi Ventures? A Kenya-based travel discovery and storytelling company that connects travellers with vetted local partners. Answers on trips, prices, booking and more.')

@section('content')
<section class="pt-24 pb-8 md:pt-32 text-center container mx-auto px-6 max-w-3xl">
    <h1 class="text-4xl md:text-5xl font-semibold tracking-tight">Frequently asked questions</h1>
    <p class="mt-4 text-[#5C5C5C]">Short, honest answers about Vumbi Ventures and how planning a trip with us works.</p>
</section>

<section class="container mx-auto px-6 pb-20 max-w-3xl space-y-10">
    @foreach ($sections as $heading => $items)
        <div>
            <h2 class="text-2xl font-semibold mb-4">{{ $heading }}</h2>
            <div class="space-y-3">
                @foreach ($items as $item)
                    <details class="group bg-white rounded-xl border border-black/5 p-4" @if ($loop->parent->first && $loop->first) open @endif>
                        <summary class="font-medium cursor-pointer">{{ $item['q'] }}</summary>
                        <p class="mt-2 text-[#5C5C5C] leading-relaxed">{{ $item['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="bg-white rounded-2xl border border-black/5 p-6 text-center">
        <h2 class="text-xl font-semibold">Still have a question?</h2>
        <p class="mt-2 text-[#5C5C5C]">Ask us. We reply within 24 business hours.</p>
        <div class="mt-4 flex flex-wrap justify-center gap-3">
            <a href="{{ route('contact') }}" class="px-5 py-2.5 rounded-xl bg-[#8B5A2B] hover:bg-[#5C3A1E] text-white font-semibold transition">Contact us</a>
            <a href="{{ route('quiz') }}" class="px-5 py-2.5 rounded-xl bg-white border border-black/10 hover:border-[#8B5A2B] font-semibold transition">Take the trip quiz</a>
        </div>
    </div>
</section>
@endsection
