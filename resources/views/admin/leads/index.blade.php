@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 text-white">
    <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
        <h1 class="text-3xl font-bold">Booking Leads</h1>
        
        <div class="flex gap-4 flex-wrap">
            {{-- Export CSV Button --}}
            <a href="{{ route('leads.export', request()->query()) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>

            {{-- Export Excel (Optional) --}}
            <a href="{{ route('leads.export', array_merge(request()->query(), ['format' => 'excel'])) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    <div class="flex gap-4 mb-8 flex-wrap">
        @foreach(['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $status => $label)
            <a href="?status={{ $status }}" 
               class="px-4 py-2 rounded-xl text-sm {{ request('status') == $status ? 'bg-amber-400 text-black' : 'bg-zinc-800' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-x-auto">
        <table class="w-full text-left">
            <thead class="border-b border-zinc-800">
                <tr>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Package</th>
                    <th class="p-4">WhatsApp</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Preferred Date</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Submitted</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30">
                    <td class="p-4">
                        <div class="font-medium">{{ $lead->first_name }}</div>
                    </td>
                    <td class="p-4">
                        <a href="/booking/{{ $lead->partnerPackage->slug ?? $lead->partner_package_id }}" 
                           class="text-amber-400 hover:underline">
                            {{ $lead->partnerPackage->title ?? 'Package #' . $lead->partner_package_id }}
                        </a>
                    </td>
                    <td class="p-4">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" 
                           target="_blank" 
                           class="text-green-400 hover:underline">
                            {{ $lead->phone }}
                        </a>
                    </td>
                    <td class="p-4 text-sm">
                        {{ $lead->email ?: '—' }}
                    </td>
                    <td class="p-4">
                        @if($lead->start_date)
                            <span class="text-sm">{{ \Carbon\Carbon::parse($lead->start_date)->format('M d, Y') }}</span>
                        @else
                            <span class="text-zinc-500">—</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <select 
                            class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm status-select"
                            data-lead-id="{{ $lead->id }}"
                            data-current-status="{{ $lead->status }}"
                            onchange="updateLeadStatus(this)">
                            @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $lead->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="p-4 text-sm text-zinc-400">
                        {{ $lead->created_at->format('M d, Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-zinc-500">
                        No booking leads found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $leads->links() }}
    </div>
</div>

<script>
    async function updateLeadStatus(select) {
        const leadId = select.dataset.leadId;
        const newStatus = select.value;
        const oldStatus = select.dataset.currentStatus;

        if (newStatus === oldStatus) return;

        // Optimistically update
        select.dataset.currentStatus = newStatus;

        try {
            const res = await fetch(`/admin/leads/${leadId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });

            const data = await res.json();

            if (!res.ok) {
                select.value = oldStatus;
                select.dataset.currentStatus = oldStatus;
                showToast(data.message || 'Failed to update status', 'error');
            } else {
                showToast('Status updated successfully', 'success');
            }
        } catch (e) {
            select.value = oldStatus;
            select.dataset.currentStatus = oldStatus;
            showToast('Network error - please try again', 'error');
        }
    }

    function showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 px-5 py-3 rounded-xl text-sm font-medium z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection