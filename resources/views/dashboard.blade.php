@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush
@section('content')
@php
    if(Auth::user()->role == 'user'){
        Auth::logout();
        return redirect('/home');
    }
@endphp
<div class="main-heading">Admin Dashboard</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <!-- User Profile Card -->
            <div class="card mt-5 mb-4">
                <div class="card-body text-center border-0">
                    @if($user->profile_picture)
                        <img src="{{ asset('images/profile/' . $user->profile_picture) }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; margin-top:20px;" alt="{{ $user->name }}'s profile">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; background-color: #1e1e1e; margin-top:20px;">
                            <span class="text-white fs-1">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="text-muted mb-1">{{ $user->department ?: 'Department not set' }}</p>
                    <p class="text-muted mb-3">{{ $user->bio ?: 'No bio added yet' }}</p>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill px-4 py-1">Edit Profile</a>
                    </div>
                </div>
            </div>

            <!-- Societies -->
            {{-- <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Your Societies</h5>
                </div>
                <div class="card-body">
                    @if(count($societies) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($societies as $society)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $society->name }}
                                    <span class="badge bg-primary rounded-pill" >{{ $society->pivot->role ?? 'member' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">You are not part of any societies yet.</p>
                    @endif
                </div>
            </div> --}}
        </div>
        <div class="main-section col-md-6">
            <!-- Create Post -->
            <div class="card shadow-sm p-3 pb-5 mb-4" style="max-width: 600px; margin: auto; border-bottom: 1px solid #797979; border-radius: 0px;">
                <form method="POST" action="{{ route('admin_post.create') }}" enctype="multipart/form-data">
                    @csrf
                    <textarea 
                        name="content" 
                        class="form-control" 
                        rows="3" 
                        placeholder="What's on your mind?" 
                        style="resize: none; font-size: 16px; box-shadow: none; padding:10px;"></textarea>
            
                    <!-- Preview Area -->
                    <div id="preview" class="mt-2 d-flex gap-2 flex-wrap"></div>
            
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <!-- Attachment Button -->
                            <label for="attachment" class="btn btn-light btn-sm rounded-pill px-3" style="font-size: 14px; cursor: pointer;">
                                @ Upload
                            </label>
                            <input type="file" name="attachment[]" id="attachment" multiple hidden>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-1" style="font-weight: 500;">
                            Post
                        </button>
                    </div>
                </form>
            </div>
            
            
            

            <!-- Posts Feed -->
            @if(count($posts) > 0)
                @foreach($posts as $post)
                    <div class="card mb-4">
                        <div class="card-header">
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
                            @if($post->image)
                                <div class="mt-3">
                                    <img src="{{ asset($post->image) }}" style="max-height: 300px; max-width: 100%; object-fit: cover; border-radius: 10px; cursor: zoom-in;"
                                    alt="Post Image" class="img-fluid rounded mb-3" data-bs-toggle="modal"
                                    data-bs-target="#imageModal{{ $post->id }}">
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="imageModal{{ $post->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $post->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-transparent border-0">
                                            <div class="modal-body text-center p-0">
                                                <img src="{{ asset($post->image) }}" alt="Zoomed Image" class="img-fluid rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        {{-- <button type="submit" class="btn btn-sm {{ $user->hasLiked($post) ? 'btn-primary' : 'btn-outline-primary' }}"> --}}
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border: none;">
                                            {{-- <i class="bi bi-heart-fill"></i> {{ $post->likes->count() }} Likes --}}
                                            <i class="bi bi-heart-fill"></i> 20
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-secondary comment-toggle" data-post-id="{{ $post->id }}" style="border: none;">
                                        <i class="bi bi-chat"></i> {{ $post->comments->count() }}
                                    </button>
                                </div>
                                @if($user->role == 'admin' || $user->role == 'dev')
                                    <div class="dropdown">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $post->id }}">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>        
                                @endif
                            </div>
                        </div>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-transparent border-0">
                                <div class="modal-body p-0">
                                <div class="card border-0 shadow-sm rounded-3 p-3 mb-4" style="max-width: 600px; margin: auto;">
                                    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="remove_image" id="remove_image_input" value="false">
                                    <textarea 
                                        name="content" 
                                        class="form-control border-0" 
                                        rows="3" 
                                        style="resize: none; font-size: 16px; box-shadow: none;"
                                    >{{ $post->content }}</textarea>
                    
                                    {{-- Preview Area --}}
                                    <div id="preview{{ $post->id }}" class="mt-2 d-flex gap-2 flex-wrap">
                                        @if($post->image)
                                        <div class="position-relative">
                                            <img src="{{ asset($post->image) }}"
                                                style="max-height: 100px; max-width: 100px; object-fit: cover; border-radius: 5px;"
                                                class="img-thumbnail">
                                            <button type="button"
                                                    class="btn-close position-absolute top-0 end-0 remove-existing-image"
                                                    data-input-id="removeImage{{ $post->id }}"
                                                    aria-label="Remove"></button>
                                            <input type="hidden" name="remove_image" id="removeImage{{ $post->id }}" value="0">
                                        </div>
                                        @endif
                                    </div>
                    
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                        <label for="attachment{{ $post->id }}"
                                                class="btn btn-light btn-sm rounded-pill px-3"
                                                style="font-size: 14px; cursor: pointer;">
                                            @ Upload
                                        </label>
                                        <input type="file"
                                                name="attachment[]"
                                                id="attachment{{ $post->id }}"
                                                multiple
                                                hidden>
                                        </div>
                    
                                        <button type="submit"
                                                class="btn btn-primary rounded-pill px-4 py-1"
                                                style="font-weight: 500;">
                                        Update
                                        </button>
                                    </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $post->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $post->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-sm rounded-3 p-3" style="max-width: 500px; margin: auto;">
                                    <div class="modal-body text-center">
                                        <h5 class="mb-3">Are you sure you want to delete this post?</h5>
                                        <p class="text-muted small mb-4">This action cannot be undone.</p>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-4">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Comments Section (Hidden by default) -->
                        <div class="card-footer comment-section" id="comments-{{ $post->id }}" style="display: none;">
                            <div id="comment-list-{{ $post->id }}">
                            @if(count($post->comments) > 0)
                                @foreach($post->comments as $comment)
                                    <div class="d-flex mb-1">
                                        @if($comment->user->profile_picture)
                                            <img src="{{ asset('images/profile/' . $comment->user->profile_picture) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <span class="text-white">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="text-white rounded-3 p-2">
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
                        </div>
                            
                            <!-- Comment Form -->
                            <form class="comment-form" data-post-id="{{ $post->id }}">
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
                                            <input type="text" class="form-control form-control-sm comment-box" name="content" placeholder="Write a comment...">
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
            <div class="card mt-5 mb-4 text-center" >
                <div class="card-header">
                    <h5 class="card-title mb-0">Admin Access Portal</h5>
                </div>
                <div class="card-body border-0">
                    <a class="btn btn-outline-secondary cursor-pointer text-white-50" style="text-decoration: none;" href="{{ route('admin.portal') }}">Enter Portal</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
    // Toggle comments
    $('.comment-form').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let content = form.find('input[name="content"]').val();
        let postId = form.data('post-id');
        let token = form.find('input[name="_token"]').val();
        // Increment comment count
        let countSpan = $('#comment-count-' + postId);
        let currentCount = parseInt(countSpan.text());

        $.ajax({
            url: "{{ route('comments.store') }}",
            method: 'POST',
            data: {
                _token: token,
                content: content,
                post_id: postId
            },
            success: function(res) {
                form.find('input[name="content"]').val('');
                // Build new comment HTML
                let commentHTML = `
                    <div class="d-flex mb-1">
                        ${res.profile_picture 
                            ? `<img src="/images/profile/${res.profile_picture}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">`
                            : `<div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <span class="text-white">${res.user_name.charAt(0).toUpperCase()}</span>
                            </div>`
                        }
                        <div class="flex-grow-1">
                            <div class="text-white rounded-3 p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="fw-bold">${res.user_name}</small>
                                    <small class="text-muted">Just now</small>
                                </div>
                                <p class="mb-0 small">${res.content}</p>
                            </div>
                        </div>
                    </div>
                `;
                // Append the new comment
                $('#comment-list-' + postId).prepend(commentHTML);
                countSpan.text(currentCount + 1);
                    $.ajax({
                        url: "{{ route('notif.comment') }}",
                        method: 'GET',
                        headers: { 'X-CSRF-TOKEN': token },
                        data: {
                            contents: "Hey "+res.ReceptorUserName+"! " +res.InitiatorName+" commented on your post!",
                            subscriptionIds: res.postUserSubscriptionId,
                            url: window.location.href, // or the post URL
                            userId: res.ReceptorUserId,
                            initiatorId: res.InitiatorId
                        },
                        success: function(notifRes) {
                            console.log('Notification sent successfully!');
                            loadNotifications();
                        },
                        error: function() {
                            console.error('Failed to send notification.');
                        }
                    });
                }
        });
    });
    // Toggle comments
    $('.comment-toggle').on('click', function () {
        const postId = $(this).data('post-id');
        $(`#comments-${postId}`).toggle();
    });

    // Preview for main post form
    $('#attachment').on('change', function (e) {
        const preview = $('#preview');
        preview.empty();

        Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (file.type.startsWith('image/')) {
                    $('<img>', {
                        src: e.target.result,
                        class: 'rounded border',
                        css: { maxWidth: '100px', maxHeight: '100px' }
                    }).appendTo(preview);
                } else {
                    $('<div>', {
                        text: file.name,
                        class: 'small text-muted border rounded p-1'
                    }).appendTo(preview);
                }
            };
            reader.readAsDataURL(file);
        });
    });

    // Preview for edit modals (multiple attachments)
    $('input[type="file"][id^="attachment"]').on('change', function () {
        const postId = this.id.replace('attachment', '');
        const preview = $(`#preview${postId}`);
        preview.empty();

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (file.type.startsWith('image/')) {
                    $('<img>', {
                        src: e.target.result,
                        class: 'img-thumbnail',
                        css: {
                            maxWidth: '100px',
                            maxHeight: '100px',
                            objectFit: 'cover',
                            borderRadius: '5px'
                        }
                    }).appendTo(preview);
                } else {
                    $('<div>', {
                        text: file.name,
                        class: 'small text-muted border rounded p-1'
                    }).appendTo(preview);
                }
            };
            reader.readAsDataURL(file);
        });
    });

    // Remove existing image from edit modal
    $(document).on('click', '.remove-existing-image', function () {
        const inputId = $(this).data('input-id');
        $(`#${inputId}`).val('1'); // Mark for deletion
        $(this).closest('.position-relative').remove(); // Remove image block
    });
});
</script>
@endpush
@endsection