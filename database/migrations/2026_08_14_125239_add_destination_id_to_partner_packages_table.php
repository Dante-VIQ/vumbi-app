<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            $table->foreignId('destination_id')
                  ->nullable()
                  ->after('location')
                  ->constrained('destinations')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('partner_packages', function (Blueprint $table) {
            $table->dropForeign(['destination_id']);
            $table->dropColumn('destination_id');
        });
    }
};