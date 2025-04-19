@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>Admin Dashboard</h2>
        <p class="text-muted">Manage your educational platform from here.</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h1 class="display-4 text-primary mb-3">
                    <i class="bi bi-people"></i>
                </h1>
                <h3 class="card-title">{{ $userCount }}</h3>
                <p class="card-text">Total Users</p>
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Manage Users</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h1 class="display-4 text-primary mb-3">
                    <i class="bi bi-collection"></i>
                </h1>
                <h3 class="card-title">{{ $postCount }}</h3>
                <p class="card-text">Total Posts</p>
                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">View Posts</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h1 class="display-4 text-primary mb-3">
                    <i class="bi bi-people-fill"></i>
                </h1>
                <h3 class="card-title">{{ $societyCount }}</h3>
                <p class="card-text">Total Societies</p>
                <a href="{{ route('admin.societies.index') }}" class="btn btn-sm btn-outline-primary">Manage Societies</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h1 class="display-4 text-primary mb-3">
                    <i class="bi bi-megaphone"></i>
                </h1>
                <h3 class="card-title">{{ $announcementCount }}</h3>
                <p class="card-text">Total Announcements</p>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-sm btn-outline-primary">Manage Announcements</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Announcements</h5>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($recentAnnouncements as $announcement)
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $announcement->title }}</h6>
                            <small>{{ $announcement->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 text-truncate">{{ $announcement->content }}</p>
                        <small>
                            Posted by: {{ $announcement->user->name }} 
                            @if($announcement->society)
                            | Society: {{ $announcement->society->name }}
                            @endif
                        </small>
                    </a>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No announcements found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Users</h5>
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                @endif
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($recentUsers as $user)
                    <div class="list-group-item">
                        <div class="d-flex align-items-center">
                            @if($user->profile_picture)
                            <img src="{{ Storage::url($user->profile_picture) }}" class="avatar avatar-md me-3" alt="{{ $user->name }}">
                            @else
                            <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small>{{ $user->email }}</small>
                                <div>
                                    <small class="badge bg-secondary">
                                        {{ $user->roles->pluck('name')->implode(', ') }}
                                    </small>
                                    @if($user->department)
                                    <small class="text-muted">{{ $user->department }}</small>
                                    @endif
                                </div>
                            </div>
                            @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No users found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Posts</h5>
                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($recentPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $post->user->name }}</h6>
                            <small>{{ $post->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 text-truncate">{{ $post->content }}</p>
                        <small>
                            Comments: {{ $post->comments->count() }} | 
                            Likes: {{ $post->likes->count() }}
                            @if($post->society)
                            | Society: {{ $post->society->name }}
                            @endif
                        </small>
                    </a>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No posts found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-outline-primary w-100 p-3">
                            <i class="bi bi-megaphone display-6 d-block mb-2"></i>
                            Create Announcement
                        </a>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.societies.create') }}" class="btn btn-outline-primary w-100 p-3">
                            <i class="bi bi-people-fill display-6 d-block mb-2"></i>
                            Create Society
                        </a>
                    </div>
                    
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.results.create') }}" class="btn btn-outline-primary w-100 p-3">
                            <i class="bi bi-file-earmark-text display-6 d-block mb-2"></i>
                            Add Results
                        </a>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.attendance.create') }}" class="btn btn-outline-primary w-100 p-3">
                            <i class="bi bi-calendar-check display-6 d-block mb-2"></i>
                            Add Attendance
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
