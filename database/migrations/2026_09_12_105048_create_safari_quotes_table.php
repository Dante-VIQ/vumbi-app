<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('safari_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('destination')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('travel_month'); // Stores YYYY-MM
            $table->unsignedInteger('travelers')->default(1);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, contacted, booked, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safari_quotes');
    }
};
