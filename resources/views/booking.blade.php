@extends('layouts.booking')

@section('content')
    <div class="container mx-auto py-8">
        <livewire:booking-modal :partner-package-id="$partnerPackage->id" />
    </div>
@endsection