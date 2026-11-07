@extends('layouts.app')

@section('title', 'Find Your Trip in 1 Minute | Vumbi Ventures')
@section('description', 'Answer four quick questions about who is travelling, what you enjoy, your budget and time, and see East Africa trips that fit.')

@php
    $choice = 'block cursor-pointer rounded-xl border border-black/10 bg-white px-4 py-3 text-sm transition hover:border-[#8B5A2B] peer-checked:border-[#8B5A2B] peer-checked:bg-[#8B5A2B]/10 peer-checked:font-semibold peer-focus-visible:ring-2 peer-focus-visible:ring-[#8B5A2B]';
@endphp

@section('content')
<section class="pt-24 pb-6 md:pt-32 text-center container mx-auto px-6 max-w-2xl">
    <h1 class="text-3xl md:text-4xl font-semibold tracking-tight">Find your trip in a minute</h1>
    <p class="mt-3 text-[#5C5C5C]">Four quick questions. We'll show trips that fit, and you can change anything afterwards.</p>
</section>

<section class="container mx-auto px-6 pb-20 max-w-2xl">
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">Please choose who is travelling to continue.</div>
    @endif

    <form action="{{ route('quiz.submit') }}" method="POST" class="space-y-8 bg-white rounded-3xl border border-black/5 shadow-lg p-6 md:p-8">
        @csrf
        <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>

        <fieldset>
            <legend class="font-semibold mb-3">1. Who is travelling? *</legend>
            <div class="grid sm:grid-cols-3 gap-3">
                @foreach (\App\Support\Persona::all() as $key => $info)
                    <div>
                        <input class="peer sr-only" type="radio" name="persona" id="q-persona-{{ $key }}" value="{{ $key }}" required @checked(old('persona', $persona) === $key)>
                        <label for="q-persona-{{ $key }}" class="{{ $choice }}">
                            <span class="block">{{ $info['label'] }}</span>
                            <span class="block text-xs font-normal text-[#5C5C5C] mt-0.5">{{ $info['tagline'] }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </fieldset>

        <fieldset>
            <legend class="font-semibold mb-3">2. What do you enjoy most?</legend>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach (\App\Support\Persona::interests() as $key => $label)
                    <div>
                        <input class="peer sr-only" type="radio" name="interest" id="q-interest-{{ $key }}" value="{{ $key }}" @checked(old('interest') === $key)>
                        <label for="q-interest-{{ $key }}" class="{{ $choice }}">{{ $label }}</label>
                    </div>
                @endforeach
            </div>
        </fieldset>

        <fieldset>
            <legend class="font-semibold mb-3">3. Budget per person</legend>
            <div class="grid sm:grid-cols-3 gap-3">
                @foreach (\App\Support\Persona::budgetRangeLabels() as $key => $label)
                    <div>
                        <input class="peer sr-only" type="radio" name="budget" id="q-budget-{{ $key }}" value="{{ $key }}" @checked(old('budget') === $key)>
                        <label for="q-budget-{{ $key }}" class="{{ $choice }}">{{ $label }}</label>
                    </div>
                @endforeach
            </div>
        </fieldset>

        <fieldset>
            <legend class="font-semibold mb-3">4. How long?</legend>
            <div class="grid sm:grid-cols-3 gap-3">
                @foreach (\App\Support\Persona::durations() as $key => $label)
                    <div>
                        <input class="peer sr-only" type="radio" name="days" id="q-days-{{ $key }}" value="{{ $key }}" @checked(old('days') === $key)>
                        <label for="q-days-{{ $key }}" class="{{ $choice }}">{{ $label }}</label>
                    </div>
                @endforeach
            </div>
        </fieldset>

        <div class="rounded-2xl bg-[#F8F5F2] p-4">
            <label for="q-email" class="block text-sm font-medium">Want the free Kenya first-safari checklist and our Field Notes? (optional)</label>
            <input type="email" name="email" id="q-email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com"
                class="mt-2 w-full px-4 py-3 bg-white border border-black/10 rounded-xl focus:outline-none focus:border-[#8B5A2B]">
            @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            <label class="mt-3 flex items-start gap-2 text-xs text-[#5C5C5C]">
                <input type="checkbox" name="consent" value="1" class="mt-0.5 rounded border-gray-300 text-[#8B5A2B]">
                <span>Yes, email me Field Notes about once a month, plus two short follow-ups in my first week. I can unsubscribe at any time. <a href="{{ route('privacy') }}" class="underline">Privacy policy</a>.</span>
            </label>
        </div>

        <button type="submit" data-gtag-event="quiz_complete"
            class="w-full py-3.5 rounded-xl bg-[#8B5A2B] hover:bg-[#5C3A1E] text-white font-semibold transition shadow-md">Show me trips</button>
    </form>
</section>
@endsection
