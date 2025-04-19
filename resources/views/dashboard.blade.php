@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <!-- User Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($user->profile_picture)
                        <img src="{{ asset('images/profile/' . $user->profile_picture) }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;" alt="{{ $user->name }}'s profile">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                            <span class="text-white fs-1">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="text-muted mb-1">{{ $user->department ?: 'Department not set' }}</p>
                    <p class="text-muted mb-3">{{ $user->bio ?: 'No bio added yet' }}</p>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Edit Profile</a>
                    </div>
                </div>
            </div>

            <!-- Societies -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Your Societies</h5>
                </div>
                <div class="card-body">
                    @if(count($societies) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($societies as $society)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $society->name }}
                                    <span class="badge bg-primary rounded-pill">{{ $society->pivot->role ?? 'member' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">You are not part of any societies yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Create Post -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3" placeholder="What's on your mind?"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Post</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Posts Feed -->
            @if(count($posts) > 0)
                @foreach($posts as $post)
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <div class="d-flex align-items-center">
                                @if($post->user->profile_picture)
                                    <img src="{{ asset('images/profile/' . $post->user->profile_picture) }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        <span class="text-white">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $post->user->name }}</h6>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $post->content }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->hasLiked($post) ? 'btn-primary' : 'btn-outline-primary' }}">
                                            <i class="bi bi-heart-fill"></i> {{ $post->likes->count() }} Likes
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-secondary ms-2 comment-toggle" data-post-id="{{ $post->id }}">
                                        <i class="bi bi-chat"></i> {{ $post->comments->count() }} Comments
                                    </button>
                                </div>
                                @if($post->user_id === $user->id || $user->isAdmin() || $user->isSuperAdmin())
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $post->id }}">
                                            @if($post->user_id === $user->id)
                                                <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">Edit</a></li>
                                            @endif
                                            <li>
                                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Comments Section (Hidden by default) -->
                        <div class="card-footer bg-white comment-section" id="comments-{{ $post->id }}" style="display: none;">
                            @if(count($post->comments) > 0)
                                @foreach($post->comments as $comment)
                                    <div class="d-flex mb-3">
                                        @if($comment->user->profile_picture)
                                            <img src="{{ asset('images/profile/' . $comment->user->profile_picture) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <span class="text-white">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="bg-light rounded-3 p-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="fw-bold">{{ $comment->user->name }}</small>
                                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 small">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small">No comments yet.</p>
                            @endif
                            
                            <!-- Comment Form -->
                            <form action="{{ route('comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <div class="d-flex">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('images/profile/' . $user->profile_picture) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <span class="text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" name="content" placeholder="Write a comment...">
                                            <button class="btn btn-sm btn-primary" type="submit">Post</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card mb-4">
                    <div class="card-body text-center py-5">
                        <p class="mb-0">No posts to show. Follow more users or join societies!</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-3">
            <!-- Announcements -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    @if(count($announcements) > 0)
                        @foreach($announcements as $announcement)
                            <div class="border-bottom pb-3 mb-3">
                                <h6>{{ $announcement->title }}</h6>
                                <p class="text-muted small mb-1">{{ $announcement->created_at->format('M d, Y') }} by {{ $announcement->user->name }}</p>
                                <p class="small">{{ Str::limit($announcement->content, 100) }}</p>
                                <a href="{{ route('announcements.show', $announcement->id) }}" class="btn btn-sm btn-link p-0">Read more</a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No announcements available.</p>
                    @endif
                </div>
            </div>

            <!-- Academic Resources -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Academic Resources</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('resources.results') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-bar-graph text-primary me-2"></i>
                                Results
                            </div>
                            <span class="badge bg-primary rounded-pill">New</span>
                        </a>
                        <a href="{{ route('resources.attendance') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-calendar-check text-primary me-2"></i>
                                Attendance
                            </div>
                        </a>
                        <a href="{{ route('subjects.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-book text-primary me-2"></i>
                            Subjects
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle comments
        const commentToggles = document.querySelectorAll('.comment-toggle');
        commentToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const commentSection = document.getElementById(`comments-${postId}`);
                
                if (commentSection.style.display === 'none') {
                    commentSection.style.display = 'block';
                } else {
                    commentSection.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection