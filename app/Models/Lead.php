<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';
    
    protected $fillable = [
        'partner_package_id',
        'partner_id',
        'source',
        'start_date',
        'end_date',
        'adults',
        'children',
        'special_occasion',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'message',
        'whatsapp_opt_in',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'whatsapp_opt_in' => 'boolean',
    ];

    public function partnerPackage()
    {
        return $this->belongsTo(PartnerPackage::class);
    }

    // public function partner()
    // {
    //     return $this->belongsTo(Partner::class);
    // }
}