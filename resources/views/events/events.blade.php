@extends('layouts.app')
@section('title', 'Events Calendar | Campus Connect')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/events.css') }}">
@endpush
@section('content')
<div class="main-heading">Upcoming Events</div>
<div class="container main-section" id="post-container">
    <div class="text-white fs-4 sub-heading">Events</div>
    <div class="container main-section2">
        <div class="calendar-container">
        <iframe src="https://calendar.google.com/calendar/embed?src=immohassan06%40gmail.com&ctz=Asia%2FKarachi" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        </div>
    </div>
    <a href="{{ route('posts.create') }}" class="create-post-btn btn btn-primary rounded-circle shadow" data-bs-toggle="tooltip"
    data-bs-placement="left"
    title="Create Post">                    
        <i class="bi bi-plus-lg"></i>
    </a>  
</div>
@push('scripts')

@endpush
@endsection