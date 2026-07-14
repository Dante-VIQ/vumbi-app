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

        Schema::create('partner_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_package_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('start_date')->nullable();
            $table->string('status')->default('new'); // new | contacted | confirmed | lost
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_leads');
    }
};
