@extends('layouts.app')

@push('meta')
@php
      $blog = App\Models\Blog::all();

   @endphp
<title>{{ $blog->meta_title ?? $blog->title }} | Vumbi Ventures</title>
<meta name="description" content="{{ $blog->meta_description ?? Str::limit($blog->excerpt, 155) }}">

{{-- Open Graph --}}
<meta property="og:title" content="{{ $blog->meta_title ?? $blog->title }}">
<meta property="og:description" content="{{ $blog->meta_description ?? Str::limit($blog->excerpt, 155) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset($blog->media_path ?? 'images/default-blog-image.jpg') }}">

{{-- Twitter Card --}}
<meta name="twitter:title" content="{{ $blog->meta_title ?? $post->title }}">
<meta name="twitter:description" content="{{ $blog->meta_description ?? Str::limit($blog->excerpt, 155) }}">
@endpush

@section('content')


   <livewire:blog-index />

@endsection
