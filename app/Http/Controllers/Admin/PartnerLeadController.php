<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\PartnerPackage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PartnerLeadController extends Controller
{
    public function index(Request $request)
    {
        $leads = Lead::with('package')
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->latest()
                    ->paginate(30);

        return view('admin.leads.index', compact('leads'));
    }

    public function show(Lead $lead)
    {
        $lead->load('package');
        return view('admin.leads.show', compact('lead'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'         => 'required|string|max:255',
            'phone'              => 'required|string|max:30',
            'email'              => 'nullable|email|max:255',
            'start_date'         => 'nullable|date',
            'partner_package_id' => 'required|exists:partner_packages,id',
        ]);

        $package = PartnerPackage::findOrFail($validated['partner_package_id']);

        $lead = Lead::create([
            'first_name'         => $validated['first_name'],
            'phone'              => $validated['phone'],
            'email'              => $validated['email'] ?? null,
            'start_date'         => $validated['start_date'] ?? null,
            'partner_package_id' => $package->id,
            'package_title'      => $package->title,
            'location'           => $package->location,
            'estimated_price'    => $package->price,
            'commission_percent' => 30.00,
            'status'             => 'pending',
        ]);

        Log::info('New partner lead created', $lead->toArray());

        return response()->json([
            'message' => 'Thank you! We will contact you shortly to confirm your booking.',
            'lead_id' => $lead->id,
        ]);
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $lead->update(['status' => $validated['status']]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Status updated successfully']);
        }

        return back()->with('success', 'Lead status updated.');
    }

    public function export(Request $request)
    {
        $leads = Lead::with('package')
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
            fputcsv($file, [
                'ID',
                'First Name',
                'Phone',
                'Email',
                'Start Date',
                'Package',
                'Location',
                'Price',
                'Commission %',
                'Commission Amount',
                'Status',
                'Created At',
            ]);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->first_name,
                    $lead->phone,
                    $lead->email,
                    $lead->start_date ? $lead->start_date->format('Y-m-d') : '',
                    $lead->package_title,
                    $lead->location,
                    $lead->estimated_price,
                    $lead->commission_percent,
                    $lead->estimated_price * $lead->commission_percent / 100,
                    $lead->status,
                    $lead->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}