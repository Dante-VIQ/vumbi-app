<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'subscribed_at',
        'persona',
        'source',
        'sequence_step',
        'last_sequence_at',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'last_sequence_at' => 'datetime',
    ];
}