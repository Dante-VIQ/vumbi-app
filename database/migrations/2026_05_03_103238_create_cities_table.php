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
Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('region_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();
            $table->string('timezone')->nullable();
            $table->integer('population')->nullable();

            $table->boolean('is_published')->default(false);
            $table->string('seo_title')->nullable();
$table->text('seo_description')->nullable();

            $table->timestamp('last_refreshed_at')->nullable();

            $table->index(['name','slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
