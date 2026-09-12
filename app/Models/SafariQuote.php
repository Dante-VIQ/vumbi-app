<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class SafariQuote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'destination',
        'name',
        'email',
        'phone',
        'travel_month',
        'travelers',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'travelers' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope query to filter pending quotes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Accessor to get formatted travel month (e.g., "October 2026").
     */
    protected function formattedTravelMonth(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->travel_month 
                ? Carbon::parse($this->travel_month)->format('F Y') 
                : 'Not specified',
        );
    }

    /**
     * Accessor to get a clean WhatsApp click-to-chat URL.
     */
    protected function whatsappUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $cleanedPhone = preg_replace('/[^0-9]/', '', $this->phone);
                return "https://wa.me/{$cleanedPhone}";
            }
        );
    }
}