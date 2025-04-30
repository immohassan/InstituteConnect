
<?php $__env->startSection('title', 'Home | Campus Connect'); ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/home-screen.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="main-heading">Explore</div>
<div class="container main-section" id="post-container">
    <div class="tab-container mb-5">
        <div class="tab active">Followings</div>
        <div class="tab">Explore</div>
        </div>
        <div class="">
        <?php if(count($posts) > 0): ?>
        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center profile-header" data-url="<?php echo e(route('profile.show', ['id' => $post->user->id])); ?>">
                        <?php if($post->user->profile_picture): ?>
                            <img src="<?php echo e(asset('images/profile/' . $post->user->profile_picture)); ?>" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover; cursor:pointer;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; cursor:pointer;">
                                <span class="text-white"><?php echo e(strtoupper(substr($post->user->name, 0, 1))); ?></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h6 class="mb-0" style="cursor:pointer;"><?php echo e($post->user->name); ?></h6>
                            <small class="text-muted"><?php echo e($post->created_at->diffForHumans()); ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo e($post->content); ?></p>
                    <?php if($post->image): ?>
                        <div class="mt-3">
                            <img src="<?php echo e(asset('storage/' . $post->image)); ?>" style="max-height: 300px; max-width: 100%; object-fit: cover; border-radius: 10px; cursor: zoom-in;"
                            alt="Post Image" class="img-fluid rounded mb-3" data-bs-toggle="modal"
                            data-bs-target="#imageModal<?php echo e($post->id); ?>">
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="imageModal<?php echo e($post->id); ?>" tabindex="-1" aria-labelledby="imageModalLabel<?php echo e($post->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body text-center p-0">
                                        <img src="<?php echo e(asset('storage/' . $post->image)); ?>" alt="Zoomed Image" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php
                                $userLiked = $post->likes->contains('user_id', auth()->id());
                            ?>
                            <form class="like-form d-inline" data-post-id="<?php echo e($post->id); ?>" data-user-id="<?php echo e(Auth::user()->id); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border: none;">
                                    <i class="bi <?php echo e($userLiked ? 'bi-heart-fill' : 'bi-heart'); ?>"></i>
                                    <span class="like-count"><?php echo e($post->likes->count()); ?></span> Likes
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-secondary comment-toggle" data-post-id="<?php echo e($post->id); ?>" style="border: none;">
                                <i class="bi bi-chat"></i> <span class="comment-count" id="comment-count-<?php echo e($post->id); ?>"><?php echo e($post->comments->count()); ?></span>
                            </button>
                            
                        </div>
                        <?php if($post->user_id === $user->id): ?>
                        <div class="dropdown">
                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                        <ul class="dropdown-menu">
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPostModal<?php echo e($post->id); ?>">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($post->id); ?>">
                                    <i class="bi bi-trash me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>        
                    <?php endif; ?>
                    </div>
                </div>
                <!-- Edit Modal -->
                <div class="modal fade" id="editPostModal<?php echo e($post->id); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-body p-0">
                        <div class="card border-0 shadow-sm rounded-3 p-3 mb-4" style="max-width: 600px; margin: auto;">
                            <form method="POST" action="<?php echo e(route('posts.update', $post->id)); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="remove_image" id="remove_image_input" value="false">
                            <textarea 
                                name="content" 
                                class="form-control border-0" 
                                rows="3" 
                                style="resize: none; font-size: 16px; box-shadow: none;"
                            ><?php echo e($post->content); ?></textarea>
            
                            
                            <div id="preview<?php echo e($post->id); ?>" class="mt-2 d-flex gap-2 flex-wrap">
                                <?php if($post->image): ?>
                                <div class="position-relative">
                                    <img src="<?php echo e(asset('storage/' . $post->image)); ?>"
                                        style="max-height: 100px; max-width: 100px; object-fit: cover; border-radius: 5px;"
                                        class="img-thumbnail">
                                    <button type="button"
                                            class="btn-close position-absolute top-0 end-0 remove-existing-image"
                                            data-input-id="removeImage<?php echo e($post->id); ?>"
                                            aria-label="Remove"></button>
                                    <input type="hidden" name="remove_image" id="removeImage<?php echo e($post->id); ?>" value="0">
                                </div>
                                <?php endif; ?>
                            </div>
            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div>
                                <label for="attachment<?php echo e($post->id); ?>"
                                        class="btn btn-light btn-sm rounded-pill px-3"
                                        style="font-size: 14px; cursor: pointer;">
                                    @ Upload
                                </label>
                                <input type="file"
                                        name="attachment[]"
                                        id="attachment<?php echo e($post->id); ?>"
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
                <div class="modal fade" id="deleteModal<?php echo e($post->id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo e($post->id); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-sm rounded-3 p-3" style="max-width: 500px; margin: auto;">
                            <div class="modal-body text-center">
                                <h5 class="mb-3">Are you sure you want to delete this post?</h5>
                                <p class="text-muted small mb-4">This action cannot be undone.</p>
                                <form action="<?php echo e(route('posts.destroy', $post->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
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
                <div class="card-footer comment-section" id="comments-<?php echo e($post->id); ?>" style="display: none;">
                    <div id="comment-list-<?php echo e($post->id); ?>">
                    <?php if(count($post->comments) > 0): ?>
                        <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex mb-1">
                                <?php if($comment->user->profile_picture): ?>
                                    <img src="<?php echo e(asset('images/profile/' . $comment->user->profile_picture)); ?>" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <span class="text-white"><?php echo e(strtoupper(substr($comment->user->name, 0, 1))); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <div class="text-white rounded-3 p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="fw-bold"><?php echo e($comment->user->name); ?></small>
                                            <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                                        </div>
                                        <p class="mb-0 small"><?php echo e($comment->content); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted small">No comments yet.</p>
                    <?php endif; ?>
                </div>
                    
                    <!-- Comment Form -->
                    <form class="comment-form" data-post-id="<?php echo e($post->id); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="post_id" value="<?php echo e($post->id); ?>">
                        <div class="d-flex">
                            <?php if($user->profile_picture): ?>
                                <img src="<?php echo e(asset('images/profile/' . $user->profile_picture)); ?>" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <span class="text-white"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                </div>
                            <?php endif; ?>
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
                <div class="card mb-4">
                    <div class="card-body text-center py-5">
                        <p class="mb-0">No posts to show. Follow more users or join societies!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <a href="#" 
        class="create-post-btn btn btn-primary rounded-circle shadow" 
        data-bs-toggle="modal" 
        data-bs-target="#createPostModal"
        data-bs-placement="left"
        title="Create Post">
        <i class="bi bi-plus-lg"></i>
    </a>            
</div>
<?php echo $__env->make('posts.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="d-flex justify-content-center align-items-center mt-3">
<div class="spinner-border text-light text-center" id="load-more-spinner" role="status" style="display: none">
    <span class="visually-hidden">Loading...</span>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

function loadNotifications() {
    $('#no-notif').show();
    $.ajax({
        url: '/get-notifications', // Adjust to the URL that fetches notifications
        method: 'GET',
        success: function(data) {
            if (data.length > 0) {
                let notificationsHtml = '';
                data.forEach(function(notification) {
                    notificationsHtml += `
                        <div class="notification-item d-flex align-items-center" style="padding: 5px 12px; font-size: 14px; padding-bottom: 0px !important;">
                            <img src="/images/blank-profile.webp" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            <p class="mb-0 me-2">${notification.content}</p>
                        </div>
                        <small style="padding: 0px 0px 0px 50px; color:#d9d9d9">${notification.formatted_created_at}</small>
                    `;
                });

                $('#notification-dropdown').html(notificationsHtml);
                $('#notification-bell .bg-danger').show(); // Show the red dot if there are new notifications

            } else {
                $('#notification-dropdown').html('<p>No new notifications</p>');
                $('#notification-bell .bg-danger').hide(); // Hide the red dot if no new notifications
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching notifications:', error);
            $('#notification-dropdown').html('<p>Error loading notifications</p>');
        }
    });
}

    let page = 1;
    let loading = false;

    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && !loading) {
            loading = true;
            page++;
            $('#load-more-spinner').show();
            $.ajax({
                url: "<?php echo e(route('posts.index')); ?>?page=" + page,
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
            url: "<?php echo e(route('comments.store')); ?>",
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
                        url: "<?php echo e(route('notif.comment')); ?>",
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

                // Post liked, now check for user's subscription
                if (res.postUserId && res.postUserSubscriptionId) {
                    // If subscription exists, send notification
                    $.ajax({
                        url: "<?php echo e(route('notif.like')); ?>",
                        method: 'GET',
                        headers: { 'X-CSRF-TOKEN': token },
                        data: {
                            contents: "Hey "+res.ReceptorUserName+"! " +res.InitiatorName+" liked your post!",
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
            } else {
                likeIcon.removeClass('bi-heart-fill').addClass('bi-heart');
            }
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PuConnect\InstituteConnect\resources\views/home/home.blade.php ENDPATH**/ ?>