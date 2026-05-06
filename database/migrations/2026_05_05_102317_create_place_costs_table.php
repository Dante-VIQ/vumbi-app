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
    Schema::create('place_costs', function (Blueprint $table) {
        $table->id();

        $table->foreignId('place_id')->constrained()->cascadeOnDelete();

        $table->integer('daily_budget_low')->nullable();   // 1500
        $table->integer('daily_budget_mid')->nullable();   // 4000
        $table->integer('daily_budget_high')->nullable();  // 10000

        $table->integer('food_cost_min')->nullable();
        $table->integer('food_cost_max')->nullable();

        $table->integer('transport_cost_min')->nullable();
        $table->integer('transport_cost_max')->nullable();

        $table->integer('accommodation_min')->nullable();
        $table->integer('accommodation_max')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_costs');
    }
};
