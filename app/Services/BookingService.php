<?php

namespace App\Services;

use App\Models\Lead;

class BookingService
{
    public function createLead(array $data, string $source = null): Lead
    {
        return Lead::create([
            'partner_package_id' => $data['partner_package_id'],
            'first_name' => $data['first_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?: null,
            'start_date' => $data['start_date'] ?: null,
            'status' => 'new',
        ]);

        // Dispatch events for notifications, emails, etc.
        // event(new LeadCreated($lead));

        return $lead;
    }
}