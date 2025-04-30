@extends('layouts.app')
@section('title', 'Societies | Campus Connect')
@push('styles')
<link href="{{ asset('css/society.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="main-heading">Societies</div>
<div class="container main-section" id="post-container">
    @foreach($societies as $society)
    <div class="card position-relative mb-5" style=" background-color: #1d1d1d; color: white; border: 1px solid #363636">
        <!-- Banner -->
        <img src="{{ asset('images/' . $society->cover_image) }}" class="card-img-top" alt="Banner" style="height: 100px; object-fit: cover;">
    
        <!-- Profile picture -->
        <img src="{{ asset('images/' . $society->logo) }}" class="rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 border border-3 border-dark" style="width: 64px; height: 64px; object-fit: cover;" alt="Profile">
    
        <!-- Card Body -->
        <div class="card-body">
            <a href="{{ route('societies.show') }}" style="text-decoration: none">
            <h5 class="card-title mb-1">{{ $society->name }}</h5>
            <p class="card-text small mb-1 text-white-50">
                {{ $society->description }}
            </p>
            <p class="card-text text-secondary" style="font-size: 0.85rem;">{{ $society->followers }} followers</p>
        </a>
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">+ Follow</button>

        </div>
    </div>
    @endforeach
    <a href="{{ route('posts.create') }}" class="create-post-btn btn btn-primary rounded-circle shadow" data-bs-toggle="tooltip"
    data-bs-placement="left"
    title="Create Post">                    
        <i class="bi bi-plus-lg"></i>
    </a>  
</div>

@push('scripts')
<script src="{{ asset('js/society.js') }}"></script>
@endpush
@endsection
