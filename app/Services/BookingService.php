<?php

namespace App\Services;

use App\Models\Lead;

class BookingService
{
    public function createLead(array $data, string $source = null): Lead
    {
        $lead = Lead::create([
            'tour_id'          => $data['tour_id'],
            'partner_id'       => $data['partner_id'],
            'source'           => $source ?? request()->get('utm_source', 'direct'),
            'start_date'       => $data['start_date'],
            'end_date'         => $data['end_date'] ?? null,
            'adults'           => $data['adults'],
            'children'         => $data['children'] ?? 0,
            'special_occasion' => $data['special_occasion'] ?? null,
            'first_name'       => $data['first_name'],
            'last_name'        => $data['last_name'],
            'email'            => $data['email'],
            'phone'            => $data['phone'],
            'country'          => $data['country'],
            'message'          => $data['message'] ?? null,
            'whatsapp_opt_in'  => $data['whatsapp_opt_in'] ?? true,
        ]);

        // Dispatch events for notifications, emails, etc.
        // event(new LeadCreated($lead));

        return $lead;
    }
}