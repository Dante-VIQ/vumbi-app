<?php

namespace App\Helpers;

class SchemaBuilder
{
    public static function touristAttraction($package): string
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "TouristAttraction",
            "name" => $package->title,
            "description" => \Illuminate\Support\Str::limit(strip_tags($package->description), 200),
            "touristType" => ucfirst($package->type),
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => $package->location,
            ],
            "offers" => [
                "@type" => "Offer",
                "price" => $package->price,
                "priceCurrency" => "KES",
            ],
            "image" => $package->image ?: 'https://picsum.photos/400/300',
            "url" => route('tours.show', $package),
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
    }

public static function tourPackage($package): string
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "TouristTrip",
            "name" => $package->title,
            "description" => \Illuminate\Support\Str::limit(strip_tags($package->description), 200),
            "touristType" => ucfirst($package->type),
            "offers" => [
                "@type" => "Offer",
                "price" => $package->price,
                "priceCurrency" => "KES",
            ],
            "image" => $package->image ?: 'https://picsum.photos/400/300',
            "url" => route('tours.index', $package),
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
    }   

}
