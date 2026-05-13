<?php

namespace App\Http\Controllers\Admin;

use App\Models\PartnerLead;
use App\Models\PartnerPackage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PartnerLeadController extends Controller
{
        public function index(Request $request)
    {
        $leads = PartnerLead::with('package')
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->latest()
                    ->paginate(30);

        return view('admin.leads.index', compact('leads'));
    }

    public function show(PartnerLead $lead)
    {
        $lead->load('package');
        return view('admin.leads.show', compact('lead'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:255',
            'package_id'     => 'required|exists:partner_packages,id',
            'notes'          => 'nullable|string|max:500',
        ]);

        $package = PartnerPackage::findOrFail($validated['package_id']);

        $lead = PartnerLead::create([
            'customer_name'     => $validated['customer_name'],
            'customer_phone'    => $validated['customer_phone'],
            'customer_email'    => $validated['customer_email'] ?? null,
            'partner_package_id'=> $package->id,
            'package_title'     => $package->title,
            'location'          => $package->location,
            'estimated_price'   => $package->price,
            'commission_percent'=> 30.00, // default, adjustable
            'status'            => 'pending',
            'notes'             => $validated['notes'] ?? null,
        ]);

        // Notify partner — here we just log; you can later add mail/WhatsApp
        Log::info('New partner lead created', $lead->toArray());

        // Optionally queue a notification email to the partner
        // PartnerNotification::dispatch($lead);

        return response()->json([
            'message' => 'Thank you! We will contact you shortly to confirm your booking.',
            'lead_id' => $lead->id,
        ]);
    }

        public function updateStatus(Request $request, PartnerLead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $lead->update(['status' => $validated['status']]);

        return back()->with('success', 'Lead status updated.');
    }


public function export(Request $request)
{
    $leads = PartnerLead::with('package')
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->month, fn($q) => $q->whereMonth('created_at', $request->month))
                ->when($request->year, fn($q) => $q->whereYear('created_at', $request->year))
                ->latest()
                ->get();

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="leads_'.now()->format('Y-m-d').'.csv"',
    ];

    $callback = function() use ($leads) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['ID', 'Customer Name', 'Phone', 'Email', 'Package', 'Location', 'Price', 'Commission %', 'Commission Amount', 'Status', 'Created At']);

        foreach ($leads as $lead) {
            fputcsv($file, [
                $lead->id,
                $lead->customer_name,
                $lead->customer_phone,
                $lead->customer_email,
                $lead->package_title,
                $lead->location,
                $lead->estimated_price,
                $lead->commission_percent,
                $lead->estimated_price * $lead->commission_percent / 100,
                $lead->status,
                $lead->created_at->format('Y-m-d H:i')
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}