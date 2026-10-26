<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Persona and filter tags for tours.
 *
 * `difficulty` already holds the activity level and `duration_days` /
 * `group_size_min` / `group_size_max` already exist, so only the missing
 * dimensions are added here. Every column is nullable: an untagged tour keeps
 * working and simply ranks in the middle of a persona-ordered list.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            $table->json('audiences')->nullable();          // any of: adventure, youth, group
            $table->json('interests')->nullable();          // hiking, wildlife, culture, beach, festival
            $table->string('budget_band', 20)->nullable()->index();   // budget, mid, premium
            $table->string('accommodation_style', 30)->nullable();    // camping, hostel, lodge, luxury
        });
    }

    public function down(): void
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            $table->dropIndex(['budget_band']);
            $table->dropColumn(['audiences', 'interests', 'budget_band', 'accommodation_style']);
        });
    }
};
