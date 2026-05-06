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
        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('type',['hotel','flight']);
            $table->enum('partner',['awin','travelpayouts','bonusarrive']);

            $table->text('clicked_url');
            $table->ipAddress('user_ip')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_clicks');
    }
};
