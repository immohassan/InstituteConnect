@extends('layouts.app')

@section('title', $society->name)

@section('content')
<div class="profile-header mb-5">
    <div class="position-relative">
        @if($society->cover_image)
            <img src="{{ Storage::url($society->cover_image) }}" alt="{{ $society->name }}" class="profile-cover">
        @else
            <img src="https://images.unsplash.com/photo-1503676382389-4809596d5290" alt="Default Cover" class="profile-cover">
        @endif
        
        <div class="position-absolute" style="bottom: -75px; left: 50px;">
            @if($society->logo)
                <img src="{{ Storage::url($society->logo) }}" alt="{{ $society->name }}" class="profile-avatar">
            @else
                <div class="profile-avatar bg-primary text-white d-flex align-items-center justify-content-center">
                    <i class="bi bi-people-fill fs-1"></i>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="container profile-content">
    <div class="row">
        <div class="col-md-8">
            <h2 class="profile-name">{{ $society->name }}</h2>
            
            <div class="profile-info mb-4">
                @if($society->email)
                <div class="info-item">
                    <i class="bi bi-envelope"></i>
                    <span>{{ $society->email }}</span>
                </div>
                @endif
                
                <div class="info-item">
                    <i class="bi bi-people"></i>
                    <span>{{ $society->users->count() }} members</span>
                </div>
                
                <div class="info-item">
                    <i class="bi bi-calendar3"></i>
                    <span>Established {{ $society->created_at->format('F Y') }}</span>
                </div>
            </div>
            
            <div class="profile-bio mb-4">
                <p>{{ $society->description }}</p>
            </div>
            
            <div class="mb-4">
                <h5>Leadership</h5>
                <div class="row">
                    @if($society->president())
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    @if($society->president()->profile_picture)
                                        <img src="{{ Storage::url($society->president()->profile_picture) }}" alt="{{ $society->president()->name }}" class="avatar avatar-md me-3">
                                    @else
                                        <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                            {{ strtoupper(substr($society->president()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="{{ route('profile.show', $society->president()) }}">{{ $society->president()->name }}</a>
                                        </h6>
                                        <small class="text-primary">President</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @foreach($society->convenors() as $convenor)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    @if($convenor->profile_picture)
                                        <img src="{{ Storage::url($convenor->profile_picture) }}" alt="{{ $convenor->name }}" class="avatar avatar-md me-3">
                                    @else
                                        <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                            {{ strtoupper(substr($convenor->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="{{ route('profile.show', $convenor) }}">{{ $convenor->name }}</a>
                                        </h6>
                                        <small class="text-primary">Convenor</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <h4 class="mb-3">Announcements</h4>
            
            @forelse($announcements as $announcement)
                @include('components.announcement-card', ['announcement' => $announcement])
            @empty
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="text-center text-muted py-3">No announcements to display.</p>
                    </div>
                </div>
            @endforelse
            
            <h4 class="mb-3">Recent Posts</h4>
            
            @forelse($posts as $post)
                @include('components.post-card', ['post' => $post])
            @empty
                <div class="card">
                    <div class="card-body">
                        <p class="text-center text-muted py-3">No posts to display.</p>
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
                    <h5 class="mb-0">About {{ $society->name }}</h5>
                </div>
                <div class="card-body">
                    <p>{{ $society->description }}</p>
                    
                    @if($society->email)
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        <span>{{ $society->email }}</span>
                    </div>
                    @endif
                    
                    <div class="d-flex align-items-center">
                        <i class="bi bi-people me-2 text-primary"></i>
                        <span>{{ $society->users->count() }} members</span>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Your Membership</h5>
                </div>
                <div class="card-body">
                    @if($isMember)
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            You are a member of this society
                        </div>
                        
                        <p>Your role: <strong>{{ ucfirst($memberRole) }}</strong></p>
                        
                        @if($memberRole == 'president' || $memberRole == 'convenor')
                            <div class="mb-3">
                                <a href="{{ route('admin.society.announcements.create') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-megaphone"></i> Create Society Announcement
                                </a>
                            </div>
                        @endif
                        
                        <form action="#" method="POST" class="d-inline" data-confirm="Are you sure you want to leave this society?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-box-arrow-left"></i> Leave Society
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            You are not a member of this society
                        </div>
                        
                        <p>Join this society to participate in discussions and receive updates.</p>
                        
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus"></i> Join Society
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Society Members</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($society->users()->take(5)->get() as $member)
                            <li class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    @if($member->profile_picture)
                                        <img src="{{ Storage::url($member->profile_picture) }}" alt="{{ $member->name }}" class="avatar avatar-sm me-3">
                                    @else
                                        <div class="avatar avatar-sm bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="{{ route('profile.show', $member) }}" class="text-decoration-none">{{ $member->name }}</a>
                                        </h6>
                                        <small class="text-muted">{{ ucfirst($member->pivot->role) }}</small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    
                    @if($society->users->count() > 5)
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-outline-primary">View All Members</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
