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
        $format = $request->input('format', 'csv');
        
        $leads = Lead::with('package')
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->when($request->month, fn($q) => $q->whereMonth('created_at', $request->month))
                    ->when($request->year, fn($q) => $q->whereYear('created_at', $request->year))
                    ->latest()
                    ->get();

        if ($format === 'excel') {
            return $this->exportExcel($leads);
        }

        return $this->exportCSV($leads);
    }

    private function exportCSV($leads): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="leads_' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ];

        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");
            
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

    private function exportExcel($leads)
    {
        // Simple HTML table that Excel can open
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1">';
        $html .= '<tr><th>ID</th><th>First Name</th><th>Phone</th><th>Email</th><th>Start Date</th><th>Package</th><th>Location</th><th>Price</th><th>Commission %</th><th>Commission Amount</th><th>Status</th><th>Created At</th></tr>';
        
        foreach ($leads as $lead) {
            $html .= '<tr>';
            $html .= '<td>' . $lead->id . '</td>';
            $html .= '<td>' . htmlspecialchars($lead->first_name) . '</td>';
            $html .= '<td>' . htmlspecialchars($lead->phone) . '</td>';
            $html .= '<td>' . htmlspecialchars($lead->email ?? '') . '</td>';
            $html .= '<td>' . ($lead->start_date ? $lead->start_date->format('Y-m-d') : '') . '</td>';
            $html .= '<td>' . htmlspecialchars($lead->package_title) . '</td>';
            $html .= '<td>' . htmlspecialchars($lead->location ?? '') . '</td>';
            $html .= '<td>' . $lead->estimated_price . '</td>';
            $html .= '<td>' . $lead->commission_percent . '</td>';
            $html .= '<td>' . ($lead->estimated_price * $lead->commission_percent / 100) . '</td>';
            $html .= '<td>' . $lead->status . '</td>';
            $html .= '<td>' . $lead->created_at->format('Y-m-d H:i') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table></body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="leads_' . now()->format('Y-m-d') . '.xls"')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }
}