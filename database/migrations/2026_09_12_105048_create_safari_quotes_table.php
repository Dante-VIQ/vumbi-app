<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safari_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('destination')->default('Unspecified');
            $table->string('name', 150);
            $table->string('email', 255);
            $table->string('phone', 30);
            $table->string('travel_month', 7);       // Y-m
            $table->unsignedTinyInteger('travelers');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'contacted', 'quoted', 'closed'])->default('pending');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();

            $table->index(['email', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safari_quotes');
    }
};