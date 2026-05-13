<?php

// database/migrations/xxxx_add_tour_fields_to_partner_packages.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title')->nullable();
            $table->integer('duration_days')->nullable();
            $table->integer('duration_nights')->nullable();
            $table->json('itinerary')->nullable();               // array of strings, one per day
            $table->json('included')->nullable();                // array of strings
            $table->json('excluded')->nullable();                // array of strings
            $table->string('difficulty')->nullable();            // easy, moderate, challenging
            $table->integer('group_size_min')->nullable();
            $table->integer('group_size_max')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'duration_days', 'duration_nights',
                'itinerary', 'included', 'excluded',
                'difficulty', 'group_size_min', 'group_size_max',
            ]);
        });
    }
};