<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerLead extends Model
{
    protected $fillable = [
        'first_name',
        'phone',
        'email',
        'start_date',
        'partner_package_id',
        'package_title',
        'location',
        'estimated_price',
        'commission_percent',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'estimated_price' => 'decimal:2',
        'commission_percent' => 'decimal:2',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(PartnerPackage::class, 'partner_package_id');
    }

    // Convenience accessors for backward compatibility if needed
    public function getCustomerNameAttribute(): string
    {
        return $this->first_name ?? '';
    }

    public function getCustomerPhoneAttribute(): string
    {
        return $this->phone ?? '';
    }

    public function getCustomerEmailAttribute(): ?string
    {
        return $this->email;
    }
}