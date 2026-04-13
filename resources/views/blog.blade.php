@extends('layouts.app')

@section('title', 'Field Notes: Travel Stories & African History | Vumbi Ventures')
@section('description', 'Untold travel narratives, cultural encounters, and deep dives into African history from places that rarely make headlines.')
@section('keywords', 'African history, travel stories, cultural deep-dives, East Africa travel, African civilizations, off-the-beaten-path')

@section('canonical', url()->current())
@section('robots', 'index, follow')

@push('schema')
@php
    $pageSchemas = [];

    // Safely extract items whether $blogs is a Collection or LengthAwarePaginator
    $blogItems = isset($blogs) && method_exists($blogs, 'getCollection') 
        ? $blogs->getCollection() 
        : collect($blogs ?? []);

    $itemList = $blogItems->values()->map(function ($blog, $index) {
        $item = [
            "@type" => "ListItem",
            "position" => $index + 1,
            "url" => route('blog.show', $blog),
            "name" => $blog->title ?? '',
            "description" => Str::limit($blog->excerpt ?? $blog->description ?? '', 120),
        ];

        if (!empty($blog->featured_image)) {
            $item["image"] = asset('storage/' . $blog->featured_image);
        }

        return $item;
    })->toArray();

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
            "itemListElement" => $itemList
        ]
    ];
@endphp

<script type="application/ld+json">
    {!! json_encode($pageSchemas, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    <livewire:blog-index />
@endsection