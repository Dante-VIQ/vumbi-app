<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location')->nullable();
            $table->text('detail')->nullable();
            $table->string('media_path')->nullable();
            $table->string('price_range')->nullable();
            $table->string('best_time_to_visit')->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('legacy_doctor_id')->nullable()->index(); // Maps old doctor ID
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('destinations');
    }
};