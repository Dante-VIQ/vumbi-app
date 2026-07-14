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
Schema::create('affiliate_clicks', function (Blueprint $table) {
    $table->id();
    $table->string('source');
    $table->foreignId('tour_id')->nullable()->constrained();
    $table->string('referring_url')->nullable();
    $table->string('ip_hash');
    $table->timestamp('clicked_at');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_clicks');
    }
};
