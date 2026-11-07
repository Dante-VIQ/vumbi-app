<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Record which persona (adventure, youth, group) a visitor had chosen when they
 * converted, so conversion rate can be compared by persona.
 */
return new class extends Migration
{
    private array $tables = ['leads', 'safari_quotes', 'tour_clicks'];

    public function up(): void
    {
        foreach ($this->tables as $name) {
            if (Schema::hasTable($name) && ! Schema::hasColumn($name, 'persona')) {
                Schema::table($name, function (Blueprint $table) {
                    $table->string('persona', 20)->nullable()->index();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $name) {
            if (Schema::hasTable($name) && Schema::hasColumn($name, 'persona')) {
                Schema::table($name, function (Blueprint $table) {
                    $table->dropIndex(['persona']);
                    $table->dropColumn('persona');
                });
            }
        }
    }
};
