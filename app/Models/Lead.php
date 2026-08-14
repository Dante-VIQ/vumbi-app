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