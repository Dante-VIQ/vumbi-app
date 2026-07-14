<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Run this AFTER `php artisan app:backfill-slugs` has populated slugs
 * for all existing rows — adding a unique constraint before that will
 * fail if any rows currently share a null/empty slug.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cultures', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cultures', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->string('slug')->nullable()->change();
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->string('slug')->nullable()->change();
        });
    }
};