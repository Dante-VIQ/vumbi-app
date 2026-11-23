@extends('layouts.admin')
@section('title', 'Subscribers - Vumbi Ventures Admin')
@section('header', 'Email list')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10 text-white">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold">Email list</h1>
        <div class="flex gap-3">
            <a href="{{ route('campaigns.index') }}" class="bg-amber-400 text-black px-5 py-2 rounded-xl font-semibold hover:bg-amber-300 transition">Campaigns</a>
            <a href="{{ route('subscribers.export') }}" class="bg-zinc-800 hover:bg-zinc-700 px-5 py-2 rounded-xl transition">Export CSV</a>
        </div>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4"><div class="text-3xl font-bold">{{ number_format($total) }}</div><div class="text-sm text-zinc-400">subscribers</div></div>
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4"><div class="text-3xl font-bold">{{ number_format($last30) }}</div><div class="text-sm text-zinc-400">joined in the last 30 days</div></div>
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-zinc-300">
            <div class="text-zinc-400 mb-1">By type</div>
            @foreach ($byPersona as $p => $n)<div>{{ $p ? ucfirst($p) : 'Not known' }}: <strong class="text-white">{{ $n }}</strong></div>@endforeach
        </div>
    </div>

    @if ($bySource->isNotEmpty())
        <p class="text-sm text-zinc-400 mb-6">Where they signed up:
            @foreach ($bySource as $s => $n)<span class="mr-3">{{ $s ?: 'unknown' }} <strong class="text-white">{{ $n }}</strong></span>@endforeach
        </p>
    @endif

    <form method="GET" class="flex gap-2 mb-4">
        <input name="q" value="{{ request('q') }}" placeholder="Search email" class="flex-1 bg-zinc-950 border border-zinc-700 rounded-xl px-3 py-2">
        <select name="persona" class="bg-zinc-950 border border-zinc-700 rounded-xl px-3 py-2">
            <option value="">All types</option>
            @foreach (\App\Support\Persona::keys() as $k)<option value="{{ $k }}" @selected(request('persona') === $k)>{{ ucfirst($k) }}</option>@endforeach
        </select>
        <button class="bg-zinc-700 hover:bg-zinc-600 px-4 rounded-xl">Filter</button>
    </form>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-x-auto mb-6">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-800 text-zinc-400"><tr><th class="p-3">Email</th><th class="p-3">Type</th><th class="p-3">Source</th><th class="p-3">Joined</th></tr></thead>
            <tbody>
            @forelse ($subscribers as $s)
                <tr class="border-b border-zinc-800/50"><td class="p-3">{{ $s->email }}</td><td class="p-3">{{ $s->persona ? ucfirst($s->persona) : '-' }}</td><td class="p-3">{{ $s->source ?: '-' }}</td><td class="p-3">{{ optional($s->subscribed_at)->format('d M Y') ?? '-' }}</td></tr>
            @empty
                <tr><td colspan="4" class="p-6 text-zinc-400">No subscribers match.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $subscribers->links() }}
</div>
@endsection
