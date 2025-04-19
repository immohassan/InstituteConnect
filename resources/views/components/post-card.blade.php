<div class="card post-card mb-4">
    <div class="post-header">
        <div class="d-flex align-items-center">
            @if($post->user->profile_picture)
                <a href="{{ route('profile.show', $post->user) }}">
                    <img src="{{ Storage::url($post->user->profile_picture) }}" alt="{{ $post->user->name }}" class="avatar avatar-md">
                </a>
            @else
                <a href="{{ route('profile.show', $post->user) }}">
                    <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </div>
                </a>
            @endif
            <div class="post-meta ms-3">
                <div class="d-flex align-items-center">
                    <a href="{{ route('profile.show', $post->user) }}" class="text-dark text-decoration-none">
                        <h6 class="post-author mb-0">{{ $post->user->name }}</h6>
                    </a>
                    @if($post->society)
                        <span class="mx-2">•</span>
                        <a href="{{ route('societies.show', $post->society) }}" class="text-decoration-none">
                            <small class="text-primary">{{ $post->society->name }}</small>
                        </a>
                    @endif
                </div>
                <small class="post-time text-muted">{{ $post->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>
    
    <div class="post-content">
        <p>{{ Str::limit($post->content, 500) }}</p>
        
        @if(Str::length($post->content) > 500)
            <a href="{{ route('posts.show', $post) }}" class="text-primary">Read more</a>
        @endif
        
        @if($post->image)
            <div class="text-center">
                <img src="{{ Storage::url($post->image) }}" class="post-image" alt="Post image">
            </div>
        @endif
    </div>
    
    <div class="post-actions">
        <div class="d-flex justify-content-between w-100">
            <div>
                @if(auth()->check() && auth()->user()->hasLiked($post))
                    <form action="{{ route('posts.unlike', $post) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-action active">
                            <i class="bi bi-hand-thumbs-up-fill"></i> Liked ({{ $post->likes->count() }})
                        </button>
                    </form>
                @else
                    <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-action">
                            <i class="bi bi-hand-thumbs-up"></i> Like ({{ $post->likes->count() }})
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('posts.show', $post) }}" class="btn btn-action">
                    <i class="bi bi-chat"></i> Comment ({{ $post->comments->count() }})
                </a>
            </div>
            
            <div>
                @if($post->user_id === auth()->id() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-action">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    @if($post->comments->count() > 0)
        <div class="comment-list">
            @foreach($post->comments->take(3) as $comment)
                <div class="comment-item">
                    <div class="comment-avatar">
                        @if($comment->user->profile_picture)
                            <img src="{{ Storage::url($comment->user->profile_picture) }}" alt="{{ $comment->user->name }}" class="avatar avatar-sm">
                        @else
                            <div class="avatar avatar-sm bg-primary text-white d-flex align-items-center justify-content-center">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <a href="{{ route('profile.show', $comment->user) }}" class="comment-author text-decoration-none text-dark">
                                {{ $comment->user->name }}
                            </a>
                            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="comment-body">
                            {{ $comment->content }}
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if($post->comments->count() > 3)
                <div class="text-center mt-2">
                    <a href="{{ route('posts.show', $post) }}" class="text-primary">View all {{ $post->comments->count() }} comments</a>
                </div>
            @endif
        </div>
    @endif
    
    <form action="{{ route('comments.store', $post) }}" method="POST" class="comment-form">
        @csrf
        <input type="text" name="content" placeholder="Write a comment..." required>
        <button type="submit" class="btn btn-primary btn-sm">Post</button>
    </form>
</div>
