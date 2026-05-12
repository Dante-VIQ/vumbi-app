<?php

namespace App\Http\Controllers;

use App\Models\PartnerLead;
use App\Models\PartnerPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerLeadController extends Controller
{
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
}