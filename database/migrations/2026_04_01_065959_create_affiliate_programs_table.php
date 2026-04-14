<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_programs', function (Blueprint $table) {
            $table->id();
            $table->string('network'); // 'awin' or 'travelpayouts'
            $table->string('program_name');
            $table->string('program_id')->nullable(); // Advertiser ID or program code
            $table->string('type'); // hotel, flight, tour, activity, safari, insurance, gear, etc.
            $table->json('keywords'); // e.g. ["maasai mara", "safari", "luxury lodge", "kenya"]
            $table->text('description')->nullable();
            $table->text('affiliate_link')->nullable(); // base link or widget HTML
            $table->text('widget_code')->nullable(); // for Travelpayouts widgets
            $table->integer('priority')->default(50);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_programs');
    }
};