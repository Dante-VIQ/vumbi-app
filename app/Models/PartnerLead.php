<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLead extends Model
{
    protected $fillable = [
        'customer_name', 'customer_phone', 'customer_email',
        'partner_package_id', 'package_title', 'location',
        'estimated_price', 'commission_percent', 'status', 'notes',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
        'commission_percent' => 'decimal:2',
    ];

    public function package()
    {
        return $this->belongsTo(PartnerPackage::class, 'partner_package_id');
    }
}