@extends('layouts.app')

@section('title', 'Field Notes - Stories from Overlooked Places | Vumbi Ventures Blog')
@section('description', 'Field Notes documents overlooked people, cultures, and destinations across Africa. Read stories about artisans, traditions, hidden gems, and the spirit of African innovation.')
@section('keywords', 'Field Notes, Vumbi Ventures blog, African stories, overlooked places, African culture, hidden destinations, African innovation, people profiles')

@push('styles')
<style>
    .category-badge {
        transition: all 0.3s ease;
    }

    .category-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(139, 90, 43, 0.1);
    }

    .featured-article {
        position: relative;
        overflow: hidden;
    }

    .featured-article::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
        z-index: 1;
    }

    .article-card {
        transition: all 0.3s ease;
    }

    .article-card:hover {
        transform: translateY(-5px);
    }

    .read-time {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: #6B6B6B;
    }

    .pagination-link {
        transition: all 0.3s ease;
    }

    .pagination-link:hover {
        background-color: #8B5A2B;
        color: white;
    }
</style>
@endpush

@push('schema')
    @php
        $blogSchema = [
            "@context" => "https://schema.org",
            "@type" => "Blog",
            "@id" => url('/field-notes') . "#blog",
            "name" => "Field Notes - Vumbi Ventures Blog",
            "description" => "Documenting overlooked people, cultures, and destinations across Africa.",
            "url" => url('/field-notes'),
            "publisher" => [
                "@type" => "Organization",
                "name" => "Vumbi Ventures",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => asset('images/vumbi-ventures-logo.png')
                ]
            ],
            "blogPost" => [
                [
                    "@type" => "BlogPosting",
                    "headline" => "The Tailor Who Teaches",
                    "description" => "How a Kumasi seamstress built a secret school in her workshop.",
                    "datePublished" => "2025-03-15",
                    "author" => [
                        "@type" => "Person",
                        "name" => "Field Notes Team"
                    ]
                ],
                [
                    "@type" => "BlogPosting",
                    "headline" => "Broken Phones, Fixed Faster",
                    "description" => "Why Accra's repair culture outpaces Amsterdam's.",
                    "datePublished" => "2025-03-08",
                    "author" => [
                        "@type" => "Person",
                        "name" => "Field Notes Team"
                    ]
                ]
            ]
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($blogSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <!-- Hero Section -->
   
@endsection

@push('scripts')
<script>
    // Add any page-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add infinite scroll or lazy loading
        // Optional: Add search functionality
    });
</script>
@endpush
