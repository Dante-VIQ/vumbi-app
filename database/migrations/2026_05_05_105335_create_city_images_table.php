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
        Schema::create('city_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('image_url');
            $table->string('photographer_name')->nullable();
            $table->string('source')->default('unsplash');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_images');
    }
};
