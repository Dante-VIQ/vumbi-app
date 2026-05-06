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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('external_id')->unique(); // from Travelpayouts/Booking
            $table->decimal('rating',2,1)->nullable();
            $table->string('address')->nullable();

            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();

            $table->text('affiliate_url'); // booking link

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
