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
          Schema::create('hotel_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hotel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('price_per_night',10,2);
            $table->string('currency',10)->default('USD');
            $table->timestamp('last_updated');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_prices');
    }
};
