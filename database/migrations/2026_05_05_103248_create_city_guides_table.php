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
 Schema::create('city_guides', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->longText('intro_text')->nullable();
            $table->longText('history')->nullable();
            $table->longText('culture')->nullable();
            $table->longText('food_culture')->nullable();
            $table->longText('travel_tips')->nullable();
            $table->longText('ideal_traveler')->nullable();

            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->unique('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_guides');
    }
};
