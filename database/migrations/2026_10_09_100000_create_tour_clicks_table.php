<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Click log for the /go/{source}/{id} affiliate redirect.
 *
 * Kept separate from `affiliate_clicks`, which is a hotel/flight click log with
 * enum columns that cannot hold tour clicks.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tour_clicks')) {
            return;
        }

        Schema::create('tour_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_package_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('source', 80)->nullable();
            $table->text('destination_url');
            $table->text('referring_url')->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->timestamp('clicked_at')->useCurrent();
            $table->timestamps();

            $table->index(['partner_package_id', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_clicks');
    }
};
