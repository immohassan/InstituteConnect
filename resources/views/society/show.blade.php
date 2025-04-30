@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush
@section('content')
@php
    $posts = $posts->filter(function($post) use ($user) {
        return $post->user_id === $user->id;
    });
@endphp
<div class="container main-section" id="post-container">
    <div class="container text-white py-4">
        <div class="d-flex align-items-top">
            <!-- Info Section -->
            <div class="ms-1 col-md-6">
                <p class="mb-1 user-name mt-4">
                {{ $user->name }} 
                </p>
                <p>
                    {{ $user->bio ?: 'No bio added yet' }}<br>
                    <span style="font-size: 14px"><strong id="followers-count">{{ $user->followers }}</strong> Connections</span>
                </p>
                
            </div>

            <!-- Profile Image Placeholder -->
            <div class="rounded-circle bg-secondary profile-pic" style="width: 100px; height: 100px;">
                @if($user->profile_picture)
                <img src="{{ asset('images/profile/' . $user->profile_picture) }}" alt="{{ strtoupper(substr($user->name, 0, 1)) }}" style="object-fit: cover; height: 100px; width:100px" class="rounded-circle">
                @else
                <div class="d-flex align-items-center justify-content-center">
                    <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                @endif
            </div>
            </div>
            <div class="user-institute">
                <i class="bi bi-patch-check-fill"></i>
                {{ $user->department ?: 'Department not set' }}
            </div>
            <div class="society-member">
                <i class="bi bi-building-fill"></i>
                {{ $user->semester}}@if($user->semester == 1)st
                @elseif($user->semester == 1)nd @elseif($user->semester == 3)rd
                @elseif($user->semester == 8 || $user->semester == 7 || $user->semester == 6 || $user->semester == 5 || $user->semester == 4)th 
                @endif Semester
            </div>
            <div class="edit-profile-btn">
                @if(Auth::user()->id == $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-light px-4" style="text-decoration: none;">
                        Edit Profile
                    </a>
                @else
                    <button id="" 
                            class="btn btn-outline-light px-4 follow-btn" 
                            data-user-id="{{ $user->id }}">
                        {{ auth()->user()->following->contains($user->id) ? 'Unfollow' : 'Follow' }}
                    </button>
                @endif
            </div>            
            <div class="container section-breaker"></div>
        </div>


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
                            <img src="{{ asset('storage/' . $post->image) }}" style="max-height: 300px; max-width: 100%; object-fit: cover; border-radius: 10px; cursor: zoom-in;"
                            alt="Post Image" class="img-fluid rounded mb-3" data-bs-toggle="modal"
                            data-bs-target="#imageModal{{ $post->id }}">
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="imageModal{{ $post->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $post->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body text-center p-0">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Zoomed Image" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @php
                                $userLiked = $post->likes->contains('user_id', auth()->id());
                            @endphp
                            <form class="like-form d-inline" data-post-id="{{ $post->id }}" data-user-id="{{ Auth::user()->id }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border: none;">
                                    <i class="bi {{ $userLiked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    <span class="like-count">{{ $post->likes->count() }}</span> Likes
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-secondary comment-toggle" data-post-id="{{ $post->id }}" style="border: none;">
                                <i class="bi bi-chat"></i> <span class="comment-count" id="comment-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
                            </button>
                        </div>
                        @if($post->user_id === Auth::user()->id)
                        <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPostModal{{ $post->id }}">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </button>
                                </li>
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
                                    <img src="{{ asset('storage/' . $post->image) }}"
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
                <p class="mb-0">No posts to show.</p>
            </div>
        </div>
    @endif
</div>
</div>


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        // Toggle comments
        $('.comment-toggle').on('click', function () {
        const postId = $(this).data('post-id');
        $(`#comments-${postId}`).toggle();
    });

    $('.follow-btn').click(function() {
        var button = $(this);
        var userId = button.data('user-id');
        var isFollowing = $.trim(button.text()) === 'Unfollow';
        var url = isFollowing ? '/unfollow/' + userId : '/follow/' + userId;

        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.success) {
                button.text(isFollowing ? 'Follow' : 'Unfollow');
                var countSpan = $('#followers-count');
                var currentCount = parseInt(countSpan.text());
                if (isFollowing) {
                    countSpan.text(currentCount - 1);
                } else {
                    countSpan.text(currentCount + 1);
                }
                if(res.follow){
                $.ajax({
                        url: "{{ route('notif.follow') }}",
                        method: 'GET',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        data: {
                            contents: "Hey "+res.ReceptorUserName+"! " +res.InitiatorName+" started following you!",
                            subscriptionIds: res.postUserSubscriptionId,
                            url: window.location.href, // or the post URL,
                            userId: res.ReceptorUserId,
                            initiatorId: res.InitiatorId
                        },
                        success: function(notifRes) {
                            console.log('Notification sent successfully!');
                        },
                        error: function() {
                            console.error('Failed to send notification.');
                        }
                    });
                }
            }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    });

    $('.like-form').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let postId = form.data('post-id');
        let likeIcon = form.find('i');
        let countSpan = form.find('.like-count');
        let token = form.find('input[name="_token"]').val();

        $.ajax({
            url: `/posts/${postId}/toggle-like`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token },
            success: function(res) {
                countSpan.text(res.count);
                if (res.liked) {
                    likeIcon.removeClass('bi-heart').addClass('bi-heart-fill');
                } else {
                    likeIcon.removeClass('bi-heart-fill').addClass('bi-heart');
                }
            }
        });
    });

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
            }
        });
    });
</script>

@endpush
@endsection
