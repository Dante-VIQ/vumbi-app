<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Budget bands are now defined in USD ($250 / $700) and applied to the stored KSh price at the
 * configured rate. Recompute bands that still match the previous KSh result (30,000 / 90,000);
 * bands someone set differently by hand are left alone.
 */
return new class extends Migration
{
    private function band(float $price, float $max, float $min): string
    {
        return $price < $max ? 'budget' : ($price >= $min ? 'premium' : 'mid');
    }

    public function up(): void
    {
        $rate = (float) config('currency.kes_per_usd', 129);
        $rate = $rate > 0 ? $rate : 129.0;

        DB::table('partner_packages')->whereNotNull('budget_band')->where('price', '>', 0)->orderBy('id')->each(function ($row) use ($rate) {
            $price = (float) $row->price;
            if ($row->budget_band === $this->band($price, 30000, 90000)) {
                DB::table('partner_packages')->where('id', $row->id)
                    ->update(['budget_band' => $this->band($price, 250 * $rate, 700 * $rate)]);
            }
        });
    }

    public function down(): void
    {
        // Not reversible: bands are derived from the same prices under different thresholds.
    }
};
