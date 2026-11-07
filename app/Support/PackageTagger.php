<?php

namespace App\Support;

use App\Models\PartnerPackage;

/**
 * Suggests persona tags for a tour from its price, difficulty, group size and
 * wording. These are starting points: a person should review them.
 */
final class PackageTagger
{
    /**
     * @return array{audiences: array<int,string>, interests: array<int,string>, budget_band: ?string, accommodation_style: ?string}
     */
    public static function suggest(PartnerPackage $package): array
    {
        $text = strtolower(implode(' ', array_filter([
            $package->title,
            $package->description,
            $package->location,
            $package->vehicle_type,
        ])));

        $interests = [];
        $interestPatterns = [
            'hiking' => '/\b(hik(e|es|ing)|trek(s|king)?|climb(s|ing)?|summit|kilimanjaro|mount kenya|mt\.? )/',
            'wildlife' => '/\b(safari|game drive|wildlife|big five|migration|gorilla|national park|reserve|rhino|flamingo)/',
            'culture' => '/\b(cultur(e|al)|village|maasai|heritage|history|historic|museum|artisan|local community)/',
            'beach' => '/\b(beach|coast(al)?|diani|zanzibar|island|snorkel|dhow|lamu|watamu|malindi)/',
            'festival' => '/\b(festival|carnival|chale wote|concert)/',
        ];
        foreach ($interestPatterns as $key => $pattern) {
            if (preg_match($pattern, $text)) {
                $interests[] = $key;
            }
        }

        $budgetBand = Persona::bandForPrice($package->price);

        $accommodation = match (true) {
            (bool) preg_match('/\b(camping|campsite|bush camp|backpack)/', $text) => 'camping',
            (bool) preg_match('/\b(hostel|dorm)/', $text) => 'hostel',
            (bool) preg_match('/\b(luxury|five-star|5-star|boutique|villa)/', $text) => 'luxury',
            (bool) preg_match('/\b(lodge|tented|hotel|resort)/', $text) => 'lodge',
            default => null,
        };

        $audiences = [];

        if (in_array($package->difficulty, ['moderate', 'challenging'], true) || in_array('hiking', $interests, true)) {
            $audiences[] = Persona::ADVENTURE;
        }

        if ($budgetBand === 'budget'
            || in_array($accommodation, ['camping', 'hostel'], true)
            || in_array('festival', $interests, true)
            || preg_match('/\b(youth|student|backpack|budget|shared)/', $text)) {
            $audiences[] = Persona::YOUTH;
        }

        if (($package->group_size_max ?? 0) >= 8
            || preg_match('/\b(group|family|families|corporate|school|team|friends|private (van|vehicle|tour))/', $text)
            || preg_match('/\b(van|coaster|bus)\b/', strtolower((string) $package->vehicle_type))) {
            $audiences[] = Persona::GROUP;
        }

        return [
            'audiences' => array_values(array_unique($audiences)),
            'interests' => $interests,
            'budget_band' => $budgetBand,
            'accommodation_style' => $accommodation,
        ];
    }
}
