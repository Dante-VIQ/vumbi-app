<?php

namespace App\Services;

use App\Mail\AdminNewLeadNotification;
use App\Mail\CustomerBookingConfirmation;
use App\Models\Lead;
use App\Models\PartnerLead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
               // Send notifications
        $this->sendAdminNotification($lead);
        $this->sendCustomerConfirmation($lead);
        return $lead;
    }

      private function sendAdminNotification(PartnerLead $lead): void
    {
        try {
            Mail::queue(new AdminNewLeadNotification($lead));
            Log::info('Admin notification sent for lead #' . $lead->id);
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification', [
                'lead_id' => $lead->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

       private function sendCustomerConfirmation(PartnerLead $lead): void
    {
        if (!$lead->email) {
            Log::info('No customer email provided for lead #' . $lead->id . '. Skipping confirmation email.');
            return;
        }

        try {
            Mail::queue(new CustomerBookingConfirmation($lead));
            Log::info('Customer confirmation sent for lead #' . $lead->id);
        } catch (\Exception $e) {
            Log::error('Failed to send customer confirmation', [
                'lead_id' => $lead->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}