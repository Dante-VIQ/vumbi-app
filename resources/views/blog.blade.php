@extends('layouts.app')

@section('title', 'Field Notes: Travel Stories & African History | Vumbi Ventures')
@section('description', 'Untold travel narratives, cultural encounters, and deep dives into African history from places that rarely make headlines.')
@section('keywords', 'African history, travel stories, cultural deep-dives, East Africa travel, African civilizations, off-the-beaten-path')

@push('schema')

@php
    $pageSchemas = [];

    $pageSchemas[] = [
        "@context" => "https://schema.org",
        "@type" => "CollectionPage",
        "name" => "Vumbi Ventures Field Notes",
        "description" => "Untold travel narratives, cultural encounters, and deep dives into African history.",
        "url" => url()->current(),
        "about" => [
            "@type" => "Thing",
            "name" => "African Travel Stories & History"
        ],
        "mainEntity" => [
            "@type" => "ItemList",
            "itemListElement" => $blogs->map(function ($blog, $index) {
                return [
                    "@type" => "ListItem",
                    "position" => $index + 1,
                    "url" => route('blog.show', $blog),
                    "name" => $blog->title,
                    "description" => Str::limit($blog->excerpt, 120),
                    "image" => $blog->featured_image ? asset('storage/' . $blog->featured_image) : null
                ];
            })->toArray()
        ]
    ];
@endphp


@endpush

@section('content')
    <livewire:blog-index />
@endsection