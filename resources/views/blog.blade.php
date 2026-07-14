@extends('layouts.app')

@php
    $seo_title = 'Field Notes – African Travel Stories, Culture & History';
    $seo_description = 'Real field notes from across Africa — untold history, culture deep-dives and honest travel stories, written by people who\'ve actually been there.';
    $seo_og_title = 'Field Notes';
    $seo_og_description = 'Travel stories, African history and cultural deep-dives from across the continent.';
@endphp


@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "Field Notes by Vumbi Ventures",
    "description": "Travel narratives, untold African history, and cultural stories from places that rarely make headlines.",
    "url": "{{ url('/blog') }}",
    "publisher": {
        "@type": "Organization",
        "name": "Vumbi Ventures",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/vumbi-ventures-logo.png') }}"
        }
    },
    "inLanguage": "en",
    "about": [
        { "@type": "Thing", "name": "Africa Travel" },
        { "@type": "Thing", "name": "African History" },
        { "@type": "Thing", "name": "East Africa" },
        { "@type": "Thing", "name": "African Culture" }
    ]
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ url('/') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Field Notes",
            "item": "{{ url('/blog') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    <livewire:blog-index />
@endsection