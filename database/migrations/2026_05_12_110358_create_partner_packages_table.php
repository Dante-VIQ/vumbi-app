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

Schema::create('partner_packages', function (Blueprint $table) {
    $table->id();
    $table->string('location');           // e.g. 'Maasai Mara', 'Diani Beach'
    $table->string('title');              // e.g. '3-Day Safari'
    $table->text('description');
    $table->decimal('price', 10, 2);      // base price in KES or USD
    $table->string('vehicle_type');       // 'safari van', 'sedan', '4x4'
    $table->string('image')->nullable();
    $table->string('type');               // 'transfer', 'safari', 'tour'
    $table->boolean('active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_packages');
    }
};
