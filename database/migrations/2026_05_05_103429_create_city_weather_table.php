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
 Schema::create('city_weather', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('month'); // 1–12
            $table->decimal('avg_temp_day',5,2)->nullable();
            $table->decimal('avg_temp_night',5,2)->nullable();
            $table->decimal('rainfall_mm',8,2)->nullable();

            $table->timestamps();
            $table->unique(['city_id','month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_weather');
    }
};
