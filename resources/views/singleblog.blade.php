@extends('layouts.app')

@section('meta_title', $blog->meta_title ?? $blog->title)

@section('meta_description', $blog->meta_description ?? Str::limit(strip_tags($blog->excerpt ?? ''), 155))

@section('og_title', $blog->meta_title ?? $blog->title)
@section('og_description', $blog->meta_description ?? Str::limit(strip_tags($blog->excerpt ?? ''), 155))
@section('og_image', asset($blog->media_path ?? 'images/og-default.jpg'))
@section('twitter_title', $blog->meta_title ?? $blog->title)
@section('twitter_description', $blog->meta_description ?? Str::limit(strip_tags($blog->excerpt ?? ''), 155))
@section('twitter_image', asset($blog->media_path ?? 'images/og-default.jpg'))

@push('styles')
<style>
    .prose-custom {
        max-width: 100%;
    }

    .prose-custom h2 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1A1A1A;
    }

    .prose-custom h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #1A1A1A;
    }

    .prose-custom p {
        color: #4B5563;
        line-height: 1.75;
        margin-bottom: 1.25rem;
    }

    .prose-custom a {
        color: #8B5A2B;
        text-decoration: underline;
    }

    .prose-custom a:hover {
        color: #6B421F;
    }

    .prose-custom blockquote {
        border-left: 4px solid #8B5A2B;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6B6B6B;
    }

    .prose-custom img {
        border-radius: 1rem;
        margin: 1.5rem 0;
    }

    .prose-custom ul, .prose-custom ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
        color: #4B5563;
    }

    .prose-custom li {
        margin: 0.5rem 0;
    }

    .share-button {
        transition: all 0.3s ease;
    }

    .share-button:hover {
        transform: translateY(-2px);
    }

    .author-card {
        background: linear-gradient(135deg, #F9F5F0 0%, #FFFFFF 100%);
    }

    .related-post-card {
        transition: all 0.3s ease;
    }

    .related-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .table-of-contents {
        background: #F9F5F0;
        border-left: 4px solid #8B5A2B;
    }

    .table-of-contents a {
        transition: color 0.3s ease;
    }

    .table-of-contents a:hover {
        color: #8B5A2B;
    }
</style>
@endpush

@push('schema')
    @php
        $blogPostingSchema = [
            "@context" => "https://schema.org",
            "@type" => "BlogPosting",
            "headline" => $blog->meta_title ?? $blog->title,
            "description" => $blog->meta_description ?? $blog->excerpt ?? strip_tags(substr($blog->content, 0, 160)),
            "datePublished" => optional($blog->created_at)->toIso8601String() ?? now()->toIso8601String(),
            "dateModified" => optional($blog->updated_at)->toIso8601String() ?? now()->toIso8601String(),
            "author" => [
                "@type" => "Person",
                "name" => $blog->author->name ?? "Field Notes Team",
                "url" => url('/field-notes/author/' . ($blog->author->slug ?? 'team'))
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => "Vumbi Ventures",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => asset('images/vumbi-ventures-logo.png')
                ]
            ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => url()->current()
            ],
            "image" => $blog->media_path ? asset($blog->media_path) : asset('images/default-blog-image.jpg'),
            "keywords" => $blog->meta_keywords ?? $blog->tags ?? null,
            "articleSection" => $blog->category ?? null,
            "inLanguage" => "en"
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($blogPostingSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <!-- Article Header -->
    <livewire:view-blog :blog="$blog" />
@endsection

@push('scripts')
<script>
    // Share functions
    function shareOnTwitter() {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent("{{ $blog->title }}");
        window.open(`https://twitter.com/intent/tweet?text=${title}&url=${url}`, '_blank', 'width=600,height=400');
    }

    function shareOnLinkedIn() {
        const url = encodeURIComponent(window.location.href);
        window.open(`https://www.linkedin.com/shareArticle?mini=true&url=${url}`, '_blank', 'width=600,height=400');
    }

    function shareOnFacebook() {
        const url = encodeURIComponent(window.location.href);
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
    }

    function copyToClipboard() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const msg = document.getElementById('copy-message');
            msg.textContent = 'Link copied to clipboard!';
            msg.classList.remove('hidden');
            setTimeout(() => {
                msg.classList.add('hidden');
            }, 3000);
        });
    }

    // Table of Contents generation (if you add headings to content)
    document.addEventListener('DOMContentLoaded', function() {
        const headings = document.querySelectorAll('.prose h2, .prose h3');
        const tocList = document.getElementById('toc-list');

        if (tocList && headings.length > 0) {
            headings.forEach((heading, index) => {
                const id = `heading-${index}`;
                heading.id = id;

                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = `#${id}`;
                a.textContent = heading.textContent;
                a.className = 'hover:text-sunflare transition';
                a.style.paddingLeft = heading.tagName === 'H3' ? '1rem' : '0';
                a.style.display = 'block';
                a.style.fontSize = heading.tagName === 'H3' ? '0.875rem' : '1rem';

                li.appendChild(a);
                tocList.appendChild(li);
            });
        }
    });
</script>
@endpush
