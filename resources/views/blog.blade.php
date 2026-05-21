@extends('layouts.app')

@push('meta')
<title>Field Notes — Africa Travel Stories, History & Culture | Vumbi Ventures</title>
<meta name="description" content="Travel narratives, untold African history, and cultural stories from places that rarely make headlines. Explore Field Notes by Vumbi Ventures.">

{{-- Open Graph --}}
<meta property="og:title" content="Field Notes — Africa Travel Stories, History & Culture | Vumbi Ventures">
<meta property="og:description" content="Travel narratives, untold African history, and cultural stories from places that rarely make headlines. Explore Field Notes by Vumbi Ventures.">
<meta property="og:url" content="{{ url('/blog') }}">
<meta property="og:image" content="{{ asset('images/og-default.jpg') }}">
<meta property="og:type" content="website">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Field Notes — Africa Travel Stories, History & Culture">
<meta name="twitter:description" content="Travel narratives, untold African history, and cultural stories from places that rarely make headlines.">
<meta name="twitter:image" content="{{ asset('images/og-default.jpg') }}">

{{-- Keywords --}}
<meta name="keywords" content="africa travel stories, african history blog, east africa travel, african culture, field notes africa, vumbi ventures blog, untold africa stories, africa travel guide">

{{-- Canonical --}}
<link rel="canonical" href="{{ url('/blog') }}">
@endpush

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