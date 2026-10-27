<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Anonymous answers only: no email or IP. Used to see which kinds of traveller arrive.
        Schema::create('quiz_responses', function (Blueprint $table) {
            $table->id();
            $table->string('persona', 20)->index();
            $table->string('interest', 20)->nullable();
            $table->string('budget', 20)->nullable();
            $table->string('days', 20)->nullable();
            $table->boolean('subscribed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_responses');
    }
};
