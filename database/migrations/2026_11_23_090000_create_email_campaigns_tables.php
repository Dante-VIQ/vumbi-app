<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // internal label
            $table->string('subject');
            $table->string('preheader')->nullable();      // preview text shown after the subject in inboxes
            $table->longText('body');                     // Markdown
            $table->string('cta_label', 60)->nullable();
            $table->string('cta_url', 500)->nullable();
            $table->string('audience', 20)->default('all'); // all | adventure | youth | group
            $table->string('status', 12)->default('draft')->index(); // draft | sending | sent
            $table->unsignedInteger('recipients_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('email_campaign_sends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_campaign_id')->constrained()->cascadeOnDelete();
            // No foreign key: unsubscribing deletes the subscriber, the send log stays.
            $table->unsignedBigInteger('newsletter_subscriber_id');
            $table->string('email');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->unique(['email_campaign_id', 'newsletter_subscriber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaign_sends');
        Schema::dropIfExists('email_campaigns');
    }
};
