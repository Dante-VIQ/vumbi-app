@extends('layouts.app')

@push('schema')
    @php
        $pageSchemas = [];

        $pageSchemas[] = [
            "@context" => "https://schema.org",
            "@type" => "CollectionPage",
            "name" => $destination->name . " Travel Guide | Vumbi Ventures",
            "description" => Str::limit($destination->description, 160),
            "url" => url()->current(),
            "about" => [
                "@type" => "Place",
                "name" => $destination->name,
                "description" => $destination->short_description,
                "geo" => [
                    "@type" => "GeoCoordinates",
                    "latitude" => $destination->latitude,
                    "longitude" => $destination->longitude
                ],
                "address" => [
                    "@type" => "PostalAddress",
                    "addressCountry" => $destination->country
                ]
            ],
            "mentions" => $destination->tours?->map(function ($tour) {
                return [
                    "@type" => "TouristTrip",
                    "name" => $tour->name ?? $tour->title,
                    "url" => route('tours.show', $tour)
                ];
            })?->toArray() ?? []
        ];

        // Optional: If you have articles about this destination, add an ItemList
        // if ($destination->articles->count()) {
        //     $pageSchemas[] = [
        //         "@context" => "https://schema.org",
        //         "@type" => "ItemList",
        //         "name" => "Articles about " . $destination->name,
        //         "itemListElement" => $destination->articles->map(function ($article, $index) {
        //             return [
        //                 "@type" => "ListItem",
        //                 "position" => $index + 1,
        //                 "url" => route('blog.show', $article),
        //                 "name" => $article->title
        //             ];
        //         })->toArray()
        //     ];
        // }
    @endphp

@endpush

@section('content')
    <livewire:destination-show :destination-id="$destination->id" />
@endsection