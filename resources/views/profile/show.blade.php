@extends('layouts.app')

@section('title', $user->name . '\'s Profile')

@section('content')
<div class="profile-header">
    <div class="position-relative">
        <img src="https://images.unsplash.com/photo-1519452575417-564c1401ecc0" alt="Cover" class="profile-cover">
        <div class="position-absolute" style="bottom: -75px; left: 50px;">
            @if($user->profile_picture)
                <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}" class="profile-avatar">
            @else
                <div class="profile-avatar bg-primary text-white d-flex align-items-center justify-content-center">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        @if($user->id === auth()->id())
            <div class="position-absolute" style="bottom: 20px; right: 20px;">
                <a href="{{ route('profile.edit') }}" class="btn btn-light">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
            </div>
        @else
            <div class="position-absolute" style="bottom: 20px; right: 20px;">
                <form action="{{ route('chats.request', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-chat-dots"></i> Message
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<div class="container profile-content">
    <div class="row">
        <div class="col-md-8">
            <h2 class="profile-name">{{ $user->name }}</h2>
            
            <div class="profile-info">
                @if($user->department)
                <div class="info-item">
                    <i class="bi bi-building"></i>
                    <span>{{ $user->department }}</span>
                </div>
                @endif
                
                @if($user->student_id)
                <div class="info-item">
                    <i class="bi bi-person-badge"></i>
                    <span>Student ID: {{ $user->student_id }}</span>
                </div>
                @endif
                
                @if($user->year && $user->semester)
                <div class="info-item">
                    <i class="bi bi-mortarboard"></i>
                    <span>Year {{ $user->year }}, Semester {{ $user->semester }}</span>
                </div>
                @endif
                
                <div class="info-item">
                    <i class="bi bi-calendar3"></i>
                    <span>Joined {{ $user->created_at->format('F Y') }}</span>
                </div>
            </div>
            
            @if($user->bio)
            <div class="profile-bio">
                <p>{{ $user->bio }}</p>
            </div>
            @endif
            
            <div class="profile-actions mb-4">
                @if($user->id !== auth()->id())
                <form action="{{ route('chats.request', $user) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-chat-dots"></i> Send Message
                    </button>
                </form>
                @endif
            </div>
            
            <h4 class="mb-3">Posts</h4>
            
            @forelse($posts as $post)
                @include('components.post-card', ['post' => $post])
            @empty
                <div class="card">
                    <div class="card-body">
                        <p class="text-center text-muted py-5">No posts to display.</p>
                    </div>
                </div>
            @endforelse
            
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Societies</h5>
                </div>
                <div class="card-body">
                    @forelse($societies as $society)
                        <div class="d-flex align-items-center mb-3">
                            @if($society->logo)
                                <img src="{{ Storage::url($society->logo) }}" alt="{{ $society->name }}" class="avatar avatar-md me-3">
                            @else
                                <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                    {{ strtoupper(substr($society->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0">
                                    <a href="{{ route('societies.show', $society) }}">{{ $society->name }}</a>
                                </h6>
                                <small class="text-muted">{{ ucfirst($society->pivot->role) }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Not a member of any society.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="fw-bold text-primary">{{ $user->posts()->count() }}</h3>
                            <p class="text-muted">Posts</p>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold text-primary">{{ $user->comments()->count() }}</h3>
                            <p class="text-muted">Comments</p>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold text-primary">{{ $user->societies()->count() }}</h3>
                            <p class="text-muted">Societies</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
