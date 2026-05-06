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
    Schema::create('place_experiences', function (Blueprint $table) {
        $table->id();

        $table->foreignId('place_id')->constrained()->cascadeOnDelete();

        $table->string('title'); // Lake Nakuru Safari
        $table->string('category'); // nature, food, nightlife

        $table->text('description');

        $table->integer('price_estimate')->nullable();

        $table->unsignedInteger('popularity_score')->default(0);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_experiences');
    }
};
