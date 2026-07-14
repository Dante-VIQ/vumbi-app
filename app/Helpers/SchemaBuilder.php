<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SchemaBuilder
{
    /**
     * Default OG/schema image if a package has none set.
     * Use a real branded asset — never a placeholder/stock service —
     * since this gets indexed by Google as the entity's image.
     */
    protected static string $defaultImage = '/images/og-default.jpg';

    protected static string $defaultCurrency = 'KES';

    /**
     * Product schema — this is the type Google actually renders as a
     * rich result (price, availability, star rating). Use this on
     * every tour/package detail page.
     */
    public static function product($package): string
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "name" => $package->title,
            "description" => Str::limit(strip_tags($package->description), 200),
            "image" => self::image($package),
            "url" => route('tours.show', $package),
        ];

        $offer = self::offer($package);
        if ($offer) {
            $schema["offers"] = $offer;
        }

        // Only include AggregateRating if you actually have review data —
        // Google penalizes/ignores fabricated or self-marked-up ratings,
        // and an AggregateRating with no reviews behind it is a validation
        // error, not just a missed opportunity.
        if (!empty($package->rating_average) && !empty($package->rating_count)) {
            $schema["aggregateRating"] = [
                "@type" => "AggregateRating",
                "ratingValue" => (string) $package->rating_average,
                "reviewCount" => (int) $package->rating_count,
            ];
        }

        return self::toScriptTag($schema);
    }

    /**
     * TouristTrip schema — doesn't trigger a Google rich result, but
     * AI answer engines (AI Overviews, Perplexity, etc.) read it to
     * understand multi-day itineraries, stops, and who the trip suits.
     * Worth including alongside Product, not instead of it.
     */
    public static function touristTrip($package): string
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "TouristTrip",
            "name" => $package->title,
            "description" => Str::limit(strip_tags($package->description), 200),
            "touristType" => ucfirst($package->type),
            "provider" => [
                "@type" => "Organization",
                "name" => "Vumbi Ventures",
                "url" => url('/'),
            ],
            "image" => self::image($package),
            "url" => route('tours.show', $package),
        ];

        $offer = self::offer($package);
        if ($offer) {
            $schema["offers"] = $offer;
        }

        // If your packages table has a structured itinerary (day-by-day
        // stops), map it in here — this is what makes TouristTrip useful
        // to AI systems beyond what Product already covers. Left out by
        // default since the current model doesn't expose one; wire it in
        // once you have `$package->itinerary` (array of day => description).
        if (!empty($package->itinerary)) {
            $schema["itinerary"] = collect($package->itinerary)->map(fn ($stop, $day) => [
                "@type" => "TouristAttraction",
                "name" => $stop['name'] ?? "Day {$day}",
                "description" => $stop['description'] ?? null,
            ])->values()->all();
        }

        return self::toScriptTag($schema);
    }

    /**
     * Convenience wrapper for tour detail pages — outputs both schemas
     * in one call, since they serve different purposes (Product for
     * Google rich results, TouristTrip for AI/itinerary context).
     *
     * Usage in a blade view:
     *   @push('schema')
     *       {!! \App\Helpers\SchemaBuilder::tourPage($package) !!}
     *   @endpush
     */
    public static function tourPage($package): string
    {
        return self::product($package) . "\n" . self::touristTrip($package);
    }

    /**
     * TouristAttraction — reserve this for actual places/landmarks
     * (e.g. your /cultures pages: Great Zimbabwe, Lamu Old Town),
     * NOT for bookable packages. A bookable package is a Product;
     * an attraction is a place. Mixing the two on the same entity
     * is what caused the duplicate-schema issue this replaces.
     */
    public static function touristAttraction($place): string
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "TouristAttraction",
            "name" => $place->title,
            "description" => Str::limit(strip_tags($place->description), 200),
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => $place->location,
            ],
            "image" => self::image($place),
            "url" => property_exists($place, 'url')
                ? $place->url
                : url()->current(),
        ];

        return self::toScriptTag($schema);
    }

    /**
     * Builds a schema-safe Offer block, or null if there's no valid
     * price — omitting the offer entirely is safer than emitting
     * "price": null, which fails validation.
     */
    protected static function offer($package): ?array
    {
        if (empty($package->price) || !is_numeric($package->price)) {
            return null;
        }

        return [
            "@type" => "Offer",
            "price" => (string) $package->price,
            "priceCurrency" => $package->currency ?? self::$defaultCurrency,
            "availability" => ($package->is_available ?? true)
                ? "https://schema.org/InStock"
                : "https://schema.org/SoldOut",
            "url" => route('tours.show', $package),
        ];
    }

    protected static function image($model): string
    {
        return $model->image ?: url(self::$defaultImage);
    }

    protected static function toScriptTag(array $schema): string
    {
        return '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_UNICODE)
            . '</script>';
    }
}