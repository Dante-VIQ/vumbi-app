<?php

namespace App\Support;

/** Converts stored KSh package prices into the USD shown to visitors. */
final class Money
{
    public static function rate(): float
    {
        $rate = (float) config('currency.kes_per_usd', 129);

        return $rate > 0 ? $rate : 129.0;
    }

    /** Whole US dollars for a KSh amount. */
    public static function usdValue(float|int|string|null $kes): int
    {
        return (int) round(((float) $kes) / self::rate());
    }

    /** "$349" for a KSh amount. */
    public static function usd(float|int|string|null $kes): string
    {
        return '$' . number_format(self::usdValue($kes));
    }

    /** KSh amount for a USD figure a visitor typed into a filter. */
    public static function kesFromUsd(float|int|string|null $usd): float
    {
        return ((float) $usd) * self::rate();
    }
}
