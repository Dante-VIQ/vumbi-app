<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Package prices are in KSh, but budget bands were first derived assuming USD
 * ($250 / $700). Recompute bands that still match the old USD result for the
 * stored price; bands someone set differently by hand are left alone.
 */
return new class extends Migration
{
    private function band(float $price, int $max, int $min): string
    {
        return $price < $max ? 'budget' : ($price >= $min ? 'premium' : 'mid');
    }

    public function up(): void
    {
        DB::table('partner_packages')->whereNotNull('budget_band')->where('price', '>', 0)->orderBy('id')->each(function ($row) {
            $price = (float) $row->price;
            if ($row->budget_band === $this->band($price, 250, 700)) {
                DB::table('partner_packages')->where('id', $row->id)->update(['budget_band' => $this->band($price, 30000, 90000)]);
            }
        });
    }

    public function down(): void
    {
        // Not reversible: the previous bands were derived from the same prices under the old thresholds.
    }
};
