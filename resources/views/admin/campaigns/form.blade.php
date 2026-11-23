@extends('layouts.admin')
@section('title', ($campaign->exists ? 'Edit' : 'New') . ' campaign - Vumbi Ventures Admin')
@section('header', $campaign->exists ? 'Edit campaign' : 'New campaign')

@section('content')
@php $field = 'w-full bg-zinc-950 border border-zinc-700 rounded-xl px-4 py-2.5 text-white focus:border-amber-400 focus:outline-none'; @endphp
<div class="max-w-3xl mx-auto px-6 py-10 text-white">
    <a href="{{ route('campaigns.index') }}" class="text-sm text-zinc-400 hover:text-white">&larr; All campaigns</a>
    <h1 class="text-3xl font-bold mt-2 mb-6">{{ $campaign->exists ? 'Edit campaign' : 'New campaign' }}</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-900/40 border border-red-700 text-red-200"><ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $campaign->exists ? route('campaigns.update', $campaign) : route('campaigns.store') }}" class="space-y-5">
        @csrf
        @if ($campaign->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm text-zinc-400 mb-1" for="name">Internal name (only you see this)</label>
            <input id="name" name="name" value="{{ old('name', $campaign->name) }}" class="{{ $field }}" required maxlength="120">
        </div>
        <div>
            <label class="block text-sm text-zinc-400 mb-1" for="subject">Subject line</label>
            <input id="subject" name="subject" value="{{ old('subject', $campaign->subject) }}" class="{{ $field }}" required maxlength="150">
        </div>
        <div>
            <label class="block text-sm text-zinc-400 mb-1" for="preheader">Preview text (shown after the subject in the inbox, optional)</label>
            <input id="preheader" name="preheader" value="{{ old('preheader', $campaign->preheader) }}" class="{{ $field }}" maxlength="150">
        </div>
        <div>
            <label class="block text-sm text-zinc-400 mb-1" for="body">Message (Markdown: blank line = new paragraph, <code>[text](/blog)</code> = link, <code>- item</code> = list)</label>
            <textarea id="body" name="body" rows="14" class="{{ $field }} font-mono text-sm" required>{{ old('body', $campaign->body) }}</textarea>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-zinc-400 mb-1" for="cta_label">Button text (optional)</label>
                <input id="cta_label" name="cta_label" value="{{ old('cta_label', $campaign->cta_label) }}" class="{{ $field }}" maxlength="60">
            </div>
            <div>
                <label class="block text-sm text-zinc-400 mb-1" for="cta_url">Button link (/page or https://...)</label>
                <input id="cta_url" name="cta_url" value="{{ old('cta_url', $campaign->cta_url) }}" class="{{ $field }}" maxlength="500">
            </div>
        </div>
        <div>
            <label class="block text-sm text-zinc-400 mb-1" for="audience">Send to</label>
            <select id="audience" name="audience" class="{{ $field }}">
                @foreach ($audiences as $key => $label)
                    <option value="{{ $key }}" @selected(old('audience', $campaign->audience) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <p class="text-xs text-zinc-500">Every email automatically gets an unsubscribe link, and links to vumbiventures.com are tagged so Google Analytics can show which campaign sent the visit.</p>

        <div class="flex gap-3">
            <button class="bg-amber-400 text-black px-6 py-2.5 rounded-xl font-semibold hover:bg-amber-300 transition">Save draft</button>
            <a href="{{ route('campaigns.index') }}" class="px-6 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
