@extends('layouts.app')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('og_title', $seo['title'])
@section('og_description', $seo['description'])
@section('og_image', $seo['og_image'])

@section('content')
<div class="min-h-screen bg-zinc-950 text-white">
    <!-- City header, tabs, places, hotels, flights, partner packages, etc. -->
    @include('partials.discovery-result', ['result' => $searchResult->toArray(), 'city' => $city->name])
</div>
@endsection