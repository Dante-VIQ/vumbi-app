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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_package_id')->constrained('partner_packages');
            // $table->foreignId('partner_id')->constrained('partners');
            $table->string('source')->default('direct');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedInteger('adults')->default(2);
            $table->unsignedInteger('children')->default(0);
            $table->string('special_occasion')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country', 2);
            $table->text('message')->nullable();
            $table->boolean('whatsapp_opt_in')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
