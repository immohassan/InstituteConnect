
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<?php
    if(Auth::user()->role == 'user'){
        Auth::logout();
        return redirect('/home');
    }
?>
<div class="main-heading">Admin Dashboard</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <!-- User Profile Card -->
            <div class="card mt-5 mb-4">
                <div class="card-body text-center border-0">
                    <?php if($user->profile_picture): ?>
                        <img src="<?php echo e(asset('images/profile/' . $user->profile_picture)); ?>" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; margin-top:20px;" alt="<?php echo e($user->name); ?>'s profile">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; background-color: #1e1e1e; margin-top:20px;">
                            <span class="text-white fs-1"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                        </div>
                    <?php endif; ?>
                    <h5 class="card-title"><?php echo e($user->name); ?></h5>
                    <p class="text-muted mb-1"><?php echo e($user->department ?: 'Department not set'); ?></p>
                    <p class="text-muted mb-3"><?php echo e($user->bio ?: 'No bio added yet'); ?></p>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary rounded-pill px-4 py-1">Edit Profile</a>
                    </div>
                </div>
            </div>

            <!-- Societies -->
            
        </div>
        <div class="main-section col-md-6">
            <!-- Create Post -->
            <div class="card shadow-sm p-3 pb-5 mb-4" style="max-width: 600px; margin: auto; border-bottom: 1px solid #797979; border-radius: 0px;">
                <form method="POST" action="<?php echo e(route('admin_post.create')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
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
            <?php if(count($posts) > 0): ?>
                <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <?php if($post->user->profile_picture): ?>
                                    <img src="<?php echo e(asset('images/profile/' . $post->user->profile_picture)); ?>" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        <span class="text-white"><?php echo e(strtoupper(substr($post->user->name, 0, 1))); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0"><?php echo e($post->user->name); ?></h6>
                                    <small class="text-muted"><?php echo e($post->created_at->diffForHumans()); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo e($post->content); ?></p>
                            <?php if($post->image): ?>
                                <div class="mt-3">
                                    <img src="<?php echo e(asset($post->image)); ?>" style="max-height: 300px; max-width: 100%; object-fit: cover; border-radius: 10px; cursor: zoom-in;"
                                    alt="Post Image" class="img-fluid rounded mb-3" data-bs-toggle="modal"
                                    data-bs-target="#imageModal<?php echo e($post->id); ?>">
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="imageModal<?php echo e($post->id); ?>" tabindex="-1" aria-labelledby="imageModalLabel<?php echo e($post->id); ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-transparent border-0">
                                            <div class="modal-body text-center p-0">
                                                <img src="<?php echo e(asset($post->image)); ?>" alt="Zoomed Image" class="img-fluid rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <form action="<?php echo e(route('posts.like', $post->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border: none;">
                                            
                                            <i class="bi bi-heart-fill"></i> 20
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-secondary comment-toggle" data-post-id="<?php echo e($post->id); ?>" style="border: none;">
                                        <i class="bi bi-chat"></i> <?php echo e($post->comments->count()); ?>

                                    </button>
                                </div>
                                <?php if($user->role == 'admin' || $user->role == 'dev'): ?>
                                    <div class="dropdown">
                                        <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" style="cursor: pointer;"></i>
                                    <ul class="dropdown-menu">
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
                                            <img src="<?php echo e(asset($post->image)); ?>"
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

        <div class="col-md-3">
            <!-- Announcements -->
            <div class="card mt-5 mb-4 text-center" >
                <div class="card-header">
                    <h5 class="card-title mb-0">Admin Access Portal</h5>
                </div>
                <div class="card-body border-0">
                    <a class="btn btn-outline-secondary cursor-pointer text-white-50" style="text-decoration: none;" href="<?php echo e(route('admin.portal')); ?>">Enter Portal</a>
                </div>
            </div>
            

            <!-- Academic Resources -->
            
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH A:\New folder\InstituteConnect\resources\views/dashboard.blade.php ENDPATH**/ ?>