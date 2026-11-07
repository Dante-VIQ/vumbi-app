<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Human-readable URLs for Field Notes. Numeric URLs (/blog/32) are already indexed,
 * so the route 301-redirects them to the slug URL. Slugs are generated once and never
 * change when a title is edited, so a published URL stays stable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('slug', 191)->nullable()->unique()->after('title');
        });

        $used = [];
        DB::table('blogs')->orderBy('id')->each(function ($row) use (&$used) {
            $base = Str::limit(Str::slug((string) $row->title), 80, '');
            if ($base === '' || ctype_digit($base)) {
                $base = 'post-' . ($base !== '' ? $base : $row->id);
            }
            $slug = $base;
            $i = 2;
            while (isset($used[$slug])) {
                $slug = "{$base}-{$i}";
                $i++;
            }
            $used[$slug] = true;
            DB::table('blogs')->where('id', $row->id)->update(['slug' => $slug]);
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
