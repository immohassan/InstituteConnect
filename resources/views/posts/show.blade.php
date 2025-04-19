@extends('layouts.app')

@section('title', 'View Post')

@section('content')
<div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Post Details</h2>
            <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Post Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    @if($post->user->profile_picture)
                        <img src="{{ Storage::url($post->user->profile_picture) }}" alt="{{ $post->user->name }}" class="avatar avatar-md me-3">
                    @else
                        <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-0">
                            <a href="{{ route('profile.show', $post->user) }}" class="text-dark text-decoration-none">{{ $post->user->name }}</a>
                        </h5>
                        <p class="text-muted mb-0 small">
                            {{ $post->created_at->format('M d, Y \a\t h:i A') }}
                            @if($post->society)
                                · Posted in <a href="{{ route('societies.show', $post->society) }}">{{ $post->society->name }}</a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $post->content }}</p>
                
                @if($post->image)
                    <div class="text-center mt-3">
                        <img src="{{ Storage::url($post->image) }}" alt="Post image" class="img-fluid rounded">
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">
                            <i class="bi bi-hand-thumbs-up-fill text-primary"></i> {{ $post->likes->count() }} likes
                        </span>
                        <span class="text-muted ms-3">
                            <i class="bi bi-chat-fill text-primary"></i> {{ $post->comments->count() }} comments
                        </span>
                    </div>
                    
                    @if($post->user_id === auth()->id() || auth()->user()->isSuperAdmin())
                    <div>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this post?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-around">
                    @if($liked)
                        <form action="{{ route('posts.unlike', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action active">
                                <i class="bi bi-hand-thumbs-up-fill"></i> Liked
                            </button>
                        </form>
                    @else
                        <form action="{{ route('posts.like', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-action">
                                <i class="bi bi-hand-thumbs-up"></i> Like
                            </button>
                        </form>
                    @endif
                    
                    <button class="btn btn-action" onclick="document.getElementById('comment-input').focus()">
                        <i class="bi bi-chat"></i> Comment
                    </button>
                    
                    <a href="#" class="btn btn-action" onclick="return sharePost(event)">
                        <i class="bi bi-share"></i> Share
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Comments ({{ $post->comments->count() }})</h5>
            </div>
            
            @include('components.comment-list', ['comments' => $post->comments])
            
            <div class="card-footer bg-white">
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="form-control" id="comment-input" name="content" placeholder="Write a comment..." required>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function sharePost(event) {
        event.preventDefault();
        
        if (navigator.share) {
            navigator.share({
                title: 'Post by {{ $post->user->name }}',
                text: '{{ Str::limit($post->content, 100) }}',
                url: window.location.href
            })
            .catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback for browsers that don't support the Web Share API
            const dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = window.location.href;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            
            alert('Link copied to clipboard!');
        }
        
        return false;
    }
</script>
@endsection
