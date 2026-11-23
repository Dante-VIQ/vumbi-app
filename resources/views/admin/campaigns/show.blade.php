@extends('layouts.admin')
@section('title', $campaign->name . ' - Vumbi Ventures Admin')
@section('header', 'Campaign')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10 text-white">
    <a href="{{ route('campaigns.index') }}" class="text-sm text-zinc-400 hover:text-white">&larr; All campaigns</a>
    @if (session('success'))<div class="mt-4 p-4 rounded-xl bg-green-900/40 border border-green-700 text-green-200">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="mt-4 p-4 rounded-xl bg-red-900/40 border border-red-700 text-red-200">{{ session('error') }}</div>@endif
    @if ($errors->any())<div class="mt-4 p-4 rounded-xl bg-red-900/40 border border-red-700 text-red-200">{{ $errors->first() }}</div>@endif

    <div class="flex flex-wrap items-start justify-between gap-4 mt-3 mb-6">
        <div>
            <h1 class="text-3xl font-bold">{{ $campaign->name }}</h1>
            <p class="text-zinc-400 mt-1">Subject: {{ $campaign->subject }}</p>
            <p class="text-zinc-400">To: {{ \App\Models\EmailCampaign::audiences()[$campaign->audience] ?? $campaign->audience }} ({{ $audienceCount }} subscriber(s) now)</p>
        </div>
        <span class="px-3 py-1 rounded-full text-sm capitalize {{ $campaign->status === 'draft' ? 'bg-zinc-700' : ($campaign->status === 'sending' ? 'bg-amber-500 text-black' : 'bg-green-700') }}">{{ $campaign->status }}</span>
    </div>

    @unless ($campaign->isDraft())
        <div class="grid grid-cols-3 gap-4 mb-8 text-center">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4"><div class="text-2xl font-bold">{{ $sent }}</div><div class="text-xs text-zinc-400">sent</div></div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4"><div class="text-2xl font-bold">{{ max($total - $sent - $failed, 0) }}</div><div class="text-xs text-zinc-400">waiting in queue</div></div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4"><div class="text-2xl font-bold">{{ $failed }}</div><div class="text-xs text-zinc-400">failed</div></div>
        </div>
        @if ($failures->isNotEmpty())
            <div class="mb-8 text-sm bg-zinc-900 border border-zinc-800 rounded-2xl p-4">
                <div class="font-semibold mb-2">Failures</div>
                @foreach ($failures as $f)<div class="text-zinc-400">{{ $f->email }}: {{ $f->error }}</div>@endforeach
            </div>
        @endif
        <p class="text-sm text-zinc-500 mb-8">Open and click rates are not tracked here. In Google Analytics, filter by utm_medium = newsletter to see visits and leads from this campaign.</p>
    @endunless

    <div class="grid lg:grid-cols-2 gap-8">
        <div>
            <h2 class="font-semibold mb-2">Preview</h2>
            <iframe src="{{ route('campaigns.preview', $campaign) }}" class="w-full h-[560px] bg-white rounded-2xl border border-zinc-800" title="Email preview"></iframe>
        </div>
        <div class="space-y-6">
            @if ($campaign->isDraft())
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
                    <h2 class="font-semibold mb-2">1. Send yourself a test</h2>
                    <form method="POST" action="{{ route('campaigns.test', $campaign) }}" class="flex gap-2">
                        @csrf
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="flex-1 bg-zinc-950 border border-zinc-700 rounded-xl px-3 py-2">
                        <button class="bg-zinc-700 hover:bg-zinc-600 px-4 rounded-xl">Send test</button>
                    </form>
                </div>
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
                    <h2 class="font-semibold mb-2">2. Send to the list</h2>
                    <form method="POST" action="{{ route('campaigns.send', $campaign) }}" onsubmit="return confirm('Send this campaign to {{ $audienceCount }} subscriber(s)? This cannot be undone.');">
                        @csrf
                        <label class="flex items-start gap-2 text-sm text-zinc-300 mb-3">
                            <input type="checkbox" name="confirm" value="1" class="mt-1" required>
                            <span>I checked the test email and want to send it to {{ $audienceCount }} subscriber(s).</span>
                        </label>
                        <button class="bg-amber-400 text-black px-5 py-2 rounded-xl font-semibold hover:bg-amber-300 transition" @disabled($audienceCount === 0)>Send campaign</button>
                        @if ($audienceCount === 0)<p class="text-xs text-zinc-500 mt-2">No subscribers in this audience yet.</p>@endif
                    </form>
                    <p class="text-xs text-zinc-500 mt-3">Emails go out through the queue, so the queue worker must be running on the server.</p>
                </div>
                <div class="flex gap-4 text-sm">
                    <a href="{{ route('campaigns.edit', $campaign) }}" class="underline text-zinc-300 hover:text-white">Edit</a>
                    <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}" onsubmit="return confirm('Delete this draft?');">@csrf @method('DELETE')<button class="underline text-red-400 hover:text-red-300">Delete draft</button></form>
                </div>
            @else
                <a href="{{ route('campaigns.create', ['from' => $campaign->id]) }}" class="inline-block bg-amber-400 text-black px-5 py-2 rounded-xl font-semibold hover:bg-amber-300 transition">Duplicate as a new draft</a>
            @endif
        </div>
    </div>
</div>
@endsection
