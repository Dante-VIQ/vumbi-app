<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The homepage hero reads App\Models\HeaderMedia, but no migration created its table, so a fresh
 * install (or test database) could not render "/". Production already has the table, so this only
 * creates it when it is missing.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('header_media')) {
            return;
        }

        Schema::create('header_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('media_path')->nullable();
            $table->string('media_type', 20)->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Left in place: production data may live in this table.
    }
};
