<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * The three visitor personas and the tag vocabulary used to match tours to them.
 *
 * The chosen persona lives in a cookie so the server can rank tours and record
 * it on leads, quotes and affiliate clicks. See App\Http\Middleware\PersistPersona.
 */
final class Persona
{
    public const COOKIE = 'vumbi_persona';

    public const ADVENTURE = 'adventure';
    public const YOUTH = 'youth';
    public const GROUP = 'group';

    /** Package prices are stored in Kenya shillings (KSh). Per-person bands: budget below BUDGET_MAX, premium from PREMIUM_MIN. */
    public const BUDGET_MAX = 30000;
    public const PREMIUM_MIN = 90000;

    public static function all(): array
    {
        return [
            self::ADVENTURE => [
                'label' => 'Adventure seeker',
                'tagline' => 'Hikes, climbs and multi-day expeditions',
            ],
            self::YOUTH => [
                'label' => 'Youth explorer',
                'tagline' => 'Budget trips, festivals and shared departures',
            ],
            self::GROUP => [
                'label' => 'Group traveller',
                'tagline' => 'Families, friends, schools and teams',
            ],
        ];
    }

    public static function keys(): array
    {
        return array_keys(self::all());
    }

    public static function label(?string $key): ?string
    {
        return self::all()[$key]['label'] ?? null;
    }

    public static function interests(): array
    {
        return [
            'hiking' => 'Hike',
            'wildlife' => 'Wildlife',
            'culture' => 'Culture',
            'beach' => 'Beach',
            'festival' => 'Festival',
        ];
    }

    public static function budgetBands(): array
    {
        return ['budget' => 'Budget', 'mid' => 'Mid-range', 'premium' => 'Premium'];
    }

    /** Enquiry-form wording for each budget band, in KSh. */
    public static function budgetRangeLabels(): array
    {
        return [
            'budget' => 'Under KSh ' . number_format(self::BUDGET_MAX),
            'mid' => 'KSh ' . number_format(self::BUDGET_MAX) . ' to ' . number_format(self::PREMIUM_MIN),
            'premium' => 'KSh ' . number_format(self::PREMIUM_MIN) . ' and above',
        ];
    }

    public static function accommodationStyles(): array
    {
        return ['camping' => 'Camping', 'hostel' => 'Hostel', 'lodge' => 'Lodge', 'luxury' => 'Luxury'];
    }

    /** Trip length filters: key => label. Bounds live in PartnerPackage::scopeTagged. */
    public static function durations(): array
    {
        return ['short' => '1-3 days', 'week' => '4-7 days', 'long' => '8+ days'];
    }

    /** Party-size filters: travellers => label. */
    public static function partySizes(): array
    {
        return [2 => '2 people', 6 => '6 people', 12 => '12+ people'];
    }

    public static function bandForPrice(float|int|string|null $price): ?string
    {
        if ($price === null || $price === '' || ! is_numeric($price) || (float) $price <= 0) {
            return null;
        }

        $price = (float) $price;

        return match (true) {
            $price < self::BUDGET_MAX => 'budget',
            $price >= self::PREMIUM_MIN => 'premium',
            default => 'mid',
        };
    }

    /** Accepts "groups", "Adventure-Seeker" etc.; returns a valid key or null. */
    public static function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strtolower(trim($value));
        $value = match (true) {
            str_starts_with($value, 'adventure') => self::ADVENTURE,
            str_starts_with($value, 'youth') => self::YOUTH,
            str_starts_with($value, 'group') => self::GROUP,
            default => $value,
        };

        return in_array($value, self::keys(), true) ? $value : null;
    }

    /** Pre-written WhatsApp opener that tells us who is asking. */
    public static function whatsappMessage(?string $persona, ?string $context = null): string
    {
        $base = match ($persona) {
            self::ADVENTURE => 'Hi Vumbi Ventures, I am interested in an adventure trip (a hike, climb or multi-day expedition) and would like help planning it.',
            self::YOUTH => 'Hi Vumbi Ventures, I am looking for a budget-friendly trip and would like help planning it.',
            self::GROUP => 'Hi Vumbi Ventures, I am planning a trip for a group and would like help with options and prices.',
            default => 'Hi Vumbi Ventures, I would like help planning a trip.',
        };

        return $context ? $base . ' ' . $context : $base;
    }

    /** The persona for this request: ?persona= wins, then the cookie. */
    public static function current(?Request $request = null): ?string
    {
        $request ??= request();

        $query = $request->query('persona');
        if ($query !== null) {
            return is_string($query) ? self::normalize($query) : null;
        }

        $cookie = $request->cookie(self::COOKIE);

        return is_string($cookie) ? self::normalize($cookie) : null;
    }

    /**
     * Keep only known filter keys with valid values.
     *
     * @return array{interest?: string, budget?: string, days?: string, group?: int, audience?: string}
     */
    public static function sanitizeFilters(array $input): array
    {
        $clean = [];

        // Query strings can carry arrays (?interest[]=x); only plain strings are valid here.
        $str = fn (string $key) => isset($input[$key]) && (is_string($input[$key]) || is_int($input[$key]))
            ? (string) $input[$key]
            : null;

        if (($v = $str('interest')) !== null && array_key_exists($v, self::interests())) {
            $clean['interest'] = $v;
        }
        if (($v = $str('budget')) !== null && array_key_exists($v, self::budgetBands())) {
            $clean['budget'] = $v;
        }
        if (($v = $str('days')) !== null && array_key_exists($v, self::durations())) {
            $clean['days'] = $v;
        }
        if (($v = $str('group')) !== null && ctype_digit($v) && (int) $v > 0) {
            $clean['group'] = min((int) $v, 200);
        }
        if (($v = $str('audience')) !== null && in_array($v, self::keys(), true)) {
            $clean['audience'] = $v;
        }

        return $clean;
    }

    /**
     * Order packages so the ones tagged for this persona come first, untagged
     * ones next and tours tagged only for other personas last. The sort is
     * stable, so within each group the original (newest first) order stays.
     */
    public static function rank(Collection $packages, ?string $persona): Collection
    {
        if (! $persona) {
            return $packages->values();
        }

        return $packages
            ->sortByDesc(fn ($package) => $package->personaScore($persona))
            ->values();
    }
}
