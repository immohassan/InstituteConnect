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
        <img src="{{ asset('images/' . $society->logo) }}" class="rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 border border-3 border-dark" style="width: 64px; height: 64px; object-fit: cover;     margin-top: -10px;" alt="Profile">
    
        <!-- Card Body -->
        <div class="card-body">
            <div>
                <a href="{{ route('societies.show', ['id' => $society->id]) }}" style="text-decoration: none">
                    <h5 class="card-title mb-1">{{ $society->name }}</h5>
                    <p class="card-text small mb-1 text-white-50">
                        {{ $society->description }}
                    </p>
                </a>
                <div class="d-flex align-content-center justify-content-between">
                    <p class="card-text text-secondary" style="font-size: 0.85rem;margin-top: 10px;">
                        <span class="follower-count" data-id="{{ $society->id }}">{{ $society->followers }}</span> followers
                    </p>
                    <button class="btn btn-outline-primary follow-btn px-4" style="border-radius: 30px;" 
                            data-id="{{ $society->id }}"
                            data-following="{{ auth()->user()->followedSocieties->contains($society->id) ? 'yes' : 'no' }}">
                        {{ auth()->user()->followedSocieties->contains($society->id) ? 'Unfollow' : '+ Follow' }}
                    </button>
                </div>
            </div>
            
        
        </div>
    </div>
    @endforeach
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'sub-admin' || Auth::user()->role == 'dev'  )
    <a href="{{ route('society.new') }}" class="create-post-btn btn btn-primary rounded-circle shadow rounded-pill" data-bs-toggle="tooltip"
    data-bs-placement="left"
    title="Create Post">                    
        <i class="bi bi-plus-lg"></i>
    </a>  
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('.follow-btn').click(function () {
            let btn = $(this);
            let id = btn.data('id');
            let following = btn.data('following') === 'yes';
            let url = following 
                ? `/society/${id}/unfollow`
                : `/society/${id}/follow`;

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === 'followed') {
                        btn.text('Unfollow').data('following', 'yes');

                    } else if (response.status === 'unfollowed') {
                        btn.text('+ Follow').data('following', 'no');
                    }

                    $('.follower-count[data-id="' +id + '"]').text(response.followers);
                },
                error: function () {
                    alert('Something went wrong. Try again.');
                }
            });
        });
    });
</script>
@endpush
@endsection
