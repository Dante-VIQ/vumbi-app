<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * App\Models\Culture uses the HasSlug trait, but no migration added the column, so a fresh install
 * could not save a culture. Production already has it; this only adds it when missing.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cultures') && ! Schema::hasColumn('cultures', 'slug')) {
            Schema::table('cultures', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique();
            });
        }
    }

    public function down(): void
    {
        // Left in place: production data may rely on this column.
    }
};
