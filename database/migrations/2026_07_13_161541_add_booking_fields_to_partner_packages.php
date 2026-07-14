<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            $table->enum('booking_type', ['manual', 'affiliate'])->default('manual')->after('id');
            // manual = local partner, you handle it, email/WhatsApp follow-up
            // affiliate = Orange Adventures/Awin/etc, redirect straight to their checkout

            $table->string('affiliate_source')->nullable(); // 'orange_adventures', 'awin', etc — only for affiliate type
            $table->string('affiliate_url')->nullable();     // only for affiliate type
            $table->string('region')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            //
        });
    }
};
