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
Schema::create('cached_searches', function (Blueprint $table) {
    $table->id();
    $table->string('query_hash', 64)->unique();   // SHA-256 of the sanitized query
    $table->string('query');                       // original query (for reference)
    $table->binary('compressed_result');           // gzcompress(serialize(SearchResult))
    $table->timestamp('expires_at')->nullable();   // when this cache entry is no longer valid
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_searches');
    }
};
