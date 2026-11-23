@extends('layouts.admin')
@section('title', 'Email campaigns - Vumbi Ventures Admin')
@section('header', 'Email campaigns')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10 text-white">
    @if (session('success'))<div class="mb-6 p-4 rounded-xl bg-green-900/40 border border-green-700 text-green-200">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="mb-6 p-4 rounded-xl bg-red-900/40 border border-red-700 text-red-200">{{ session('error') }}</div>@endif

    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold">Email campaigns</h1>
            <p class="text-zinc-400 mt-1">{{ number_format($subscriberCount) }} subscriber(s) on the list. <a href="{{ route('subscribers.index') }}" class="text-amber-400 hover:underline">View list</a></p>
        </div>
        <a href="{{ route('campaigns.create') }}" class="bg-amber-400 text-black px-5 py-2 rounded-xl font-semibold hover:bg-amber-300 transition">New campaign</a>
    </div>

    <h2 class="text-lg font-semibold mb-3">Start from an existing campaign</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
        @foreach ($templates as $key => $t)
            <a href="{{ route('campaigns.create', ['template' => $key]) }}" class="block bg-zinc-900 border border-zinc-800 rounded-2xl p-4 hover:border-amber-400 transition">
                <div class="font-semibold">{{ $t['label'] }}</div>
                <div class="text-sm text-zinc-400 mt-1">{{ $t['description'] }}</div>
            </a>
        @endforeach
    </div>

    <h2 class="text-lg font-semibold mb-3">Your campaigns</h2>
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-x-auto mb-10">
        <table class="w-full text-left">
            <thead class="border-b border-zinc-800 text-sm text-zinc-400">
                <tr><th class="p-4">Name</th><th class="p-4">Audience</th><th class="p-4">Status</th><th class="p-4">Recipients</th><th class="p-4">Sent</th><th class="p-4"></th></tr>
            </thead>
            <tbody>
            @forelse ($campaigns as $c)
                <tr class="border-b border-zinc-800/50">
                    <td class="p-4"><a href="{{ route('campaigns.show', $c) }}" class="text-amber-400 hover:underline">{{ $c->name }}</a><div class="text-xs text-zinc-500">{{ $c->subject }}</div></td>
                    <td class="p-4 text-sm">{{ \App\Models\EmailCampaign::audiences()[$c->audience] ?? $c->audience }}</td>
                    <td class="p-4 text-sm capitalize">{{ $c->status }}</td>
                    <td class="p-4 text-sm">{{ $c->recipients_count ?: '-' }}</td>
                    <td class="p-4 text-sm">{{ optional($c->started_at)->format('d M Y') ?? '-' }}</td>
                    <td class="p-4 text-right"><a href="{{ route('campaigns.create', ['from' => $c->id]) }}" class="text-sm text-zinc-300 hover:text-white underline">Duplicate</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-6 text-zinc-400">No campaigns yet. Pick a starting point above or create a new one.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $campaigns->links() }}

    <h2 class="text-lg font-semibold mb-3 mt-10">Automated welcome emails</h2>
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 text-sm text-zinc-300">
        <p>These go out on their own to every new subscriber (day 0, day 3, day 7), so you do not need to send them by hand.</p>
        <p class="mt-2 text-zinc-400">Subscribers by last step reached:
            @foreach ([1 => 'Welcome', 2 => 'Trips', 3 => 'Questions / finished'] as $step => $label)
                <span class="inline-block mr-3">{{ $label }}: <strong class="text-white">{{ $sequence[$step] ?? 0 }}</strong></span>
            @endforeach
        </p>
    </div>
</div>
@endsection
