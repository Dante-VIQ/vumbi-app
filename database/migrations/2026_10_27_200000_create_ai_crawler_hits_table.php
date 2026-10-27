<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per bot, path and day, with a hit counter. Small by design.
        Schema::create('ai_crawler_hits', function (Blueprint $table) {
            $table->id();
            $table->string('bot', 60);
            $table->string('path', 255);
            $table->date('day');
            $table->unsignedInteger('hits')->default(1);
            $table->timestamps();
            $table->unique(['bot', 'path', 'day']);
            $table->index('day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_crawler_hits');
    }
};
