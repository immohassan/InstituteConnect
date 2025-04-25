@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home-screen.css') }}">
@endpush

@section('content')
<div class="main-heading">Explore</div>
<div class="container main-section" id="post-container">
    <div class="tab-container mb-5">
        <div class="tab active">Followings</div>
        <div class="tab">Explore</div>
        </div>
        <div class="">
        @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center profile-header" data-url="{{ route('profile.show', ['id' => $post->user->id]) }}">
                        @if($post->user->profile_picture)
                            <img src="{{ asset('images/profile/' . $post->user->profile_picture) }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover; cursor:pointer;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; cursor:pointer;">
                                <span class="text-white">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0" style="cursor:pointer;">{{ $post->user->name }}</h6>
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
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border: none;">
                                    @if($post->likes->count() == 0)
                                    <i class="bi bi-heart"></i> {{ $post->likes->count() }} Likes
                                    @elseif($post->likes->contains('user_id', $post->user_id))
                                    <i class="bi bi-heart-fill"></i> {{ $post->likes->count() }} Likes
                                    @else
                                    <i class="bi bi-heart"></i> {{ $post->likes->count() }} Likes
                                    @endif
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-secondary comment-toggle" data-post-id="{{ $post->id }}" style="border: none;">
                                <i class="bi bi-chat"></i> {{ $post->comments->count() }}
                            </button>
                        </div>
                        @if($post->user_id === $user->id)
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
        <a href="{{ route('posts.create') }}" class="create-post-btn btn btn-primary rounded-circle shadow" data-bs-toggle="tooltip"
        data-bs-placement="left"
        title="Create Post">                    
            <i class="bi bi-plus-lg"></i>
        </a>        
</div>

<div class="d-flex justify-content-center align-items-center mt-3">
<div class="spinner-border text-light text-center" id="load-more-spinner" role="status" style="display: none">
    <span class="visually-hidden">Loading...</span>
</div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let page = 1;
    let loading = false;

    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && !loading) {
            loading = true;
            page++;
            $('#load-more-spinner').show();
            $.ajax({
                url: "{{ route('posts.index') }}?page=" + page,
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (data) {
                    if ($.trim(data).length === 0) return;
                    $('#post-container').append(data);
                    $('#load-more-spinner').hide();
                    loading = false;
                }
            });
        }
    });

    $(document).ready(function () {

        $(document).on('click', '.profile-header', function () {
    const url = $(this).data('url');
    window.location.href = url;
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

    const $button = $('.create-post-btn');
    const offset = 30; // default distance from bottom

    $(window).on('scroll', function () {
        const footerOffset = $('footer').offset()?.top || 0;
        const scrollBottom = $(window).scrollTop() + $(window).height();

        if (scrollBottom > footerOffset) {
            const overlap = scrollBottom - footerOffset;
            $button.css('bottom', (offset + overlap) + 'px');
        } else {
            $button.css('bottom', offset + 'px');
        }
    });
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
