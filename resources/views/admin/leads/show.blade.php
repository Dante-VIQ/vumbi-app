@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12 text-white">
    <!-- Back link -->
    <a href="{{ route('leads.index') }}" class="text-zinc-400 hover:text-white mb-6 inline-block">
        ← Back to leads
    </a>

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Lead #{{ $lead->id }}</h1>
        <span class="px-3 py-1 rounded-full text-sm
            {{ $lead->status === 'confirmed' ? 'bg-green-900 text-green-400' : '' }}
            {{ $lead->status === 'pending' ? 'bg-amber-900 text-amber-400' : '' }}
            {{ $lead->status === 'completed' ? 'bg-blue-900 text-blue-400' : '' }}
            {{ $lead->status === 'cancelled' ? 'bg-red-900 text-red-400' : '' }}">
            {{ ucfirst($lead->status) }}
        </span>
    </div>

    <!-- Lead details card -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-zinc-400">Name</dt>
                <dd class="font-medium">{{ $lead->customer_name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-400">Phone</dt>
                <dd class="font-medium">{{ $lead->customer_phone }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-400">Email</dt>
                <dd class="font-medium">{{ $lead->customer_email ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-400">Submitted</dt>
                <dd class="font-medium">{{ $lead->created_at->format('M d, Y H:i') }}</dd>
            </div>
        </dl>

        @if($lead->notes)
        <div class="mt-4">
            <dt class="text-sm text-zinc-400">Customer Notes</dt>
            <dd class="mt-1 text-zinc-300">{{ $lead->notes }}</dd>
        </div>
        @endif
    </div>

    <!-- Package details card -->
    @if($lead->package)
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Requested Package</h2>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-zinc-400">Title</dt>
                <dd class="font-medium">{{ $lead->package_title }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-400">Location</dt>
                <dd class="font-medium">{{ $lead->location }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-400">Type</dt>
                <dd class="font-medium capitalize">{{ $lead->package->type }}</dd>
            </div>
            <div>
                <dt class="text-sm text-zinc-400">Price</dt>
                <dd class="font-medium">KSh {{ number_format($lead->estimated_price) }}</dd>
            </div>
        </dl>
        @if($lead->package->description)
        <div class="mt-4">
            <dt class="text-sm text-zinc-400">Description</dt>
            <dd class="mt-1 text-zinc-300">{{ $lead->package->description }}</dd>
        </div>
        @endif
    </div>
    @endif

    <!-- Commission breakdown -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Commission</h2>
        <div class="flex items-baseline gap-2">
            <span class="text-3xl font-bold text-green-400">
                KSh {{ number_format($lead->estimated_price * $lead->commission_percent / 100) }}
            </span>
            <span class="text-sm text-zinc-400">
                ({{ $lead->commission_percent }}% of KSh {{ number_format($lead->estimated_price) }})
            </span>
        </div>
    </div>

    <!-- Status update form -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
        <h2 class="text-xl font-semibold mb-4">Update Status</h2>
        <form action="{{ route('leads.update-status', $lead) }}" method="POST" class="flex items-center gap-4">
            @csrf
            @method('PATCH')
            <select name="status" class="bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2 text-white">
                @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" {{ $lead->status == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-black px-6 py-2 rounded-xl font-medium">
                Update
            </button>
        </form>
    </div>
</div>
@endsection