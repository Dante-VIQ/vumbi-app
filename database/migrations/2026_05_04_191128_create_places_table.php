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
Schema::create('places', function (Blueprint $table) {
            $table->id();

            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();

$table->unsignedBigInteger('place_category_id');
$table->foreign('place_category_id')
      ->references('id')
      ->on('place_categories')
      ->onDelete('cascade');

            $table->string('name');
            $table->string('google_place_id')->unique();

            $table->string('address')->nullable();
            $table->decimal('latitude',10,7)->nullable();
            $table->decimal('longitude',10,7)->nullable();

            $table->decimal('rating',2,1)->nullable();
            $table->integer('price_level')->nullable();
            $table->string('website')->nullable();

            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
