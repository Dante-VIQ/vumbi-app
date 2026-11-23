<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailCampaignSend extends Model
{
    protected $fillable = ['email_campaign_id', 'newsletter_subscriber_id', 'email', 'sent_at', 'failed_at', 'error'];

    protected $casts = ['sent_at' => 'datetime', 'failed_at' => 'datetime'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EmailCampaign::class, 'email_campaign_id');
    }
}
