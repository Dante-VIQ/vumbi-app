<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name', 'phone', 'email',
        'partner_package_id', 'start_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'status' => 'string',
    ];

    public function package()
    {
        return $this->belongsTo(PartnerPackage::class, 'partner_package_id');
    }

    // The lead emails were written against PartnerLead's denormalised columns.
    // Lead stores only the package id, so expose the same fields from the package.
    public function getPackageTitleAttribute(): string
    {
        return $this->package?->title ?? 'your trip';
    }

    public function getLocationAttribute(): ?string
    {
        return $this->package?->location;
    }

    // Not tracked on Lead; null keeps the price/commission rows out of the email.
    public function getEstimatedPriceAttribute(): ?float
    {
        return null;
    }

    public function getCommissionPercentAttribute(): ?float
    {
        return null;
    }

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