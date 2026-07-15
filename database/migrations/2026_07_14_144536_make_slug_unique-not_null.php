<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Idempotent: safe to re-run even if it partially succeeded before
 * (e.g. cultures' unique index got created but destinations failed).
 * Checks for each index by name before trying to add it.
 *
 * Run `php artisan app:backfill-slugs` and confirm no null/duplicate
 * slugs remain BEFORE running this — see queries in chat.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Not-null change is safe to re-run — no "already exists" failure mode.
        Schema::table('cultures', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });

        $this->addUniqueIndexIfMissing('cultures', 'cultures_slug_unique', 'slug');
        $this->addUniqueIndexIfMissing('destinations', 'destinations_slug_unique', 'slug');
    }

    public function down(): void
    {
        $this->dropIndexIfExists('cultures', 'cultures_slug_unique');
        $this->dropIndexIfExists('destinations', 'destinations_slug_unique');

        Schema::table('cultures', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
        });
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
        });
    }

    protected function addUniqueIndexIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);

        if (empty($exists)) {
            Schema::table($table, function (Blueprint $t) use ($column) {
                $t->unique($column);
            });
        }
    }

    protected function dropIndexIfExists(string $table, string $indexName): void
    {
        $exists = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);

        if (!empty($exists)) {
            Schema::table($table, function (Blueprint $t) use ($indexName) {
                $t->dropUnique($indexName);
            });
        }
    }
};