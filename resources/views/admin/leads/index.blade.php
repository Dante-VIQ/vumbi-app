@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 text-white">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Partner Leads</h1>
        <div class="flex gap-4">
            <a href="{{ route('leads.export', request()->query()) }}" 
               class="bg-amber-400 hover:bg-amber-300 text-black px-5 py-2 rounded-xl text-sm font-medium">
                Export CSV
            </a>
        </div>
    </div>

    <div class="flex gap-4 mb-8">
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
                    <th class="p-4">Phone</th>
                    <th class="p-4">Price</th>
                    <th class="p-4">Commission</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30">
                    <td class="p-4">{{ $lead->customer_name }}</td>
                    <td class="p-4">{{ $lead->package_title }}</td>
                    <td class="p-4">{{ $lead->customer_phone }}</td>
                    <td class="p-4">KSh {{ number_format($lead->estimated_price) }}</td>
                    <td class="p-4">{{ $lead->commission_percent }}% (KSh {{ number_format($lead->estimated_price * $lead->commission_percent / 100) }})</td>
                    <td class="p-4">
                        <!-- Inline status dropdown -->
                        <select 
                            class="bg-zinc-800 border border-zinc-700 rounded-lg px-2 py-1 text-sm status-select"
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
                    <td class="p-4 text-sm">{{ $lead->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $leads->links() }}</div>
</div>

<script>
    async function updateLeadStatus(select) {
        const leadId = select.dataset.leadId;
        const newStatus = select.value;
        const oldStatus = select.dataset.currentStatus;

        if (newStatus === oldStatus) return;

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

            if (res.ok) {
                select.dataset.currentStatus = newStatus;
                // Optionally flash a success message
                showToast('Status updated');
            } else {
                select.value = oldStatus;
                alert('Failed to update status');
            }
        } catch (e) {
            select.value = oldStatus;
            alert('Network error');
        }
    }
</script>
@endsection