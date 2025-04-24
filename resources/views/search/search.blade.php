@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endpush
@section('content')
<div class="main-heading">Search</div>
<div class="container main-section" id="post-container">
    <div class="">
        <input type="text" class="form-control search-bar" id="user-search" placeholder="Search">
    </div>
    <div class="mt-1 text-secondary mb-5">
        Follow suggestions
    </div>
    {{-- after configuartions change the filter to only dev profiles --}}
    <div id="search-loader" class="text-center my-3" style="display: none;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="suggestions">
    @foreach($users as $user) 
    <div class=" suggestion-block mt-2 d-flex align-items-center justify-content-between p-3 rounded shadow-sm" style="background-color: #1a1a1a; cursor: pointer;" data-url="{{ route('profile.show', ['id' => $user->id]) }}" >
        <div class="d-flex align-items-center">
            <img src="{{ $user->profile_picture ? asset('images/profile/' . $user->profile_picture) : asset('images/blank-profile.webp')}}" alt="User Avatar" class="rounded-circle me-3" width="48" height="48">
            <div>
                <div class="d-flex align-items-center">
                    <strong class="text-white me-1">{{ $user->name }}</strong>
                    <i class="devicon-devicon-plain" title="Developer of the App"></i>
                </div>
                <small class="user-department">{{ $user->department ? $user->department : "No Department Yet"}}</small>
            </div>
        </div>
        <button class="btn btn-outline-light btn-sm px-4">Follow</button>
    </div>
    @endforeach
</div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.suggestion-block', function () {
    const url = $(this).data('url');
    window.location.href = url;
});

    $('#user-search').on('keyup', function () {
    const query = $(this).val();

    if (query.length > 1) {
        $('#search-loader').show(); // Show loader
        $.ajax({
            url: "{{ route('users.search') }}",
            type: "GET",
            data: { name: query },
            success: function (data) {
                $('.suggestions').html(data);
            },
            complete: function () {
                $('#search-loader').hide(); // Hide loader
            }
        });
    } else {
        // Reload static suggestions when input is cleared
        $.ajax({
            url: '{{ route("users.static") }}', // <- new route for static suggestions
            type: 'GET',
            success: function (data) {
                $('.suggestions').html(data);
            }
        });
    }
});

});
</script>
@endpush
@endsection