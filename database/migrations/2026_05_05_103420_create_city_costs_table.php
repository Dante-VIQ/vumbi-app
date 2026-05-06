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
        Schema::create('city_costs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('meal_price',10,2)->nullable();
            $table->decimal('transport_ticket',10,2)->nullable();
            $table->decimal('taxi_start',10,2)->nullable();
            $table->decimal('coffee_price',10,2)->nullable();
            $table->decimal('beer_price',10,2)->nullable();

            $table->decimal('budget_daily_low',10,2)->nullable();
            $table->decimal('budget_daily_mid',10,2)->nullable();
            $table->decimal('budget_daily_high',10,2)->nullable();

            $table->string('currency',10)->nullable();

            $table->timestamps();
            $table->unique('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_costs');
    }
};
