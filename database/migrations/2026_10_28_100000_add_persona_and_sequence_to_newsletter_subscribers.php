<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('persona', 20)->nullable()->index();      // adventure | youth | group, when known
            $table->string('source', 40)->nullable();                 // placement or 'quiz'
            $table->unsignedTinyInteger('sequence_step')->default(1); // last welcome-sequence email sent (1 = welcome)
            $table->timestamp('last_sequence_at')->nullable();
        });

        // People who joined before the sequence existed only agreed to monthly Field Notes:
        // mark them as finished so the follow-ups go to new subscribers only.
        DB::table('newsletter_subscribers')->update(['sequence_step' => 3]);
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropIndex(['persona']);
            $table->dropColumn(['persona', 'source', 'sequence_step', 'last_sequence_at']);
        });
    }
};
