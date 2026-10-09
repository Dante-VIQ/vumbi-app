<?php

namespace App\Services;

use App\Mail\AdminNewLeadNotification;
use App\Mail\CustomerBookingConfirmation;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BookingService
{
    public function createLead(array $data, ?string $source = null): Lead
    {
        $lead = Lead::create([
            'partner_package_id' => $data['partner_package_id'],
            'first_name' => $data['first_name'],
            'phone' => $data['phone'],
            'email' => ($data['email'] ?? null) ?: null,
            'start_date' => ($data['start_date'] ?? null) ?: null,
            'status' => 'new',
        ]);

        // The lead is already saved; a mail failure must never lose it or
        // show the visitor an error, so each send is isolated and logged.
        $this->sendAdminNotification($lead);
        $this->sendCustomerConfirmation($lead);

        return $lead;
    }

    /**
     * Sent immediately rather than queued: a new lead is time-sensitive and a
     * queued mail silently never leaves if no queue worker is running.
     */
    private function sendAdminNotification(Lead $lead): void
    {
        try {
            Mail::send(new AdminNewLeadNotification($lead));
            Log::info('Admin notification sent for lead #' . $lead->id);
        } catch (Throwable $e) {
            Log::error('Failed to send admin notification', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendCustomerConfirmation(Lead $lead): void
    {
        if (! $lead->email) {
            Log::info('No customer email provided for lead #' . $lead->id . '. Skipping confirmation email.');

            return;
        }

        try {
            Mail::send(new CustomerBookingConfirmation($lead));
            Log::info('Customer confirmation sent for lead #' . $lead->id);
        } catch (Throwable $e) {
            Log::error('Failed to send customer confirmation', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
