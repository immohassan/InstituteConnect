
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/profile.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/mobile.css')); ?>">
<style>
    .card{
    background-color: #1e1e1e !important;
    border:none;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<?php
    $posts = $posts->filter(function($post) use ($society) {
        return $post->society_id === $society->id;
    });
?>
<div class="container main-section" id="post-container">
    <img src="<?php echo e(asset('images/' . $society->cover_image)); ?>" class="card-img-top" alt="Banner" style="height: 140px;
    object-fit: cover;
    margin: -50px;
    margin-top: -70px;
    width: calc(100% + 100px);
    border-radius: 24px 24px 0px 0px;">
    <div class="container text-white py-4">
        <div class="d-flex align-items-top">
            <!-- Info Section -->
            <div class="ms-1 col-md-6">
                <p class="mb-1 user-name mt-5">
                <?php echo e($society->name); ?> 
                </p>
                <p>
                    <?php echo e($society->description ?: 'No bio added yet'); ?><br>
                    <span style="font-size: 14px"><strong id="followers-count"><?php echo e($society->followers); ?></strong> Followers</span>
                </p>
                
            </div>

            <!-- Profile Image Placeholder -->
            <div class="rounded-circle bg-secondary profile-pic" style="width: 100px; height: 100px;">
                <?php if($society->logo): ?>
                <img src="<?php echo e(asset('images/' . $society->logo)); ?>" alt="<?php echo e(strtoupper(substr($society->name, 0, 1))); ?>" style="object-fit: cover; height: 100px; width:100px" class="rounded-circle">
                <?php else: ?>
                <div class="d-flex align-items-center justify-content-center">
                    <span><?php echo e(strtoupper(substr($society->name, 0, 1))); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="user-institute">
            <i class="bi bi-patch-check-fill"></i>
            <?php echo e($society->email); ?>

        </div>
            <div class="edit-profile-btn d-flex align-content-center justify-content-between">
                <?php if(Auth::user()->role == 'sub-admin' || Auth::user()->role == 'dev'): ?>
                    <a href="<?php echo e(route('society.edit', ['id' => $society->id])); ?>" class="btn btn-outline-light px-4" style="text-decoration: none; border: 2px solid;">
                        Edit Society
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($society->id); ?>" class="btn btn-outline-danger px-4" style="text-decoration: none; border: 2px solid;">
                        Delete Society
                    </a>
                <?php else: ?>
                    <button id="" 
                            class="btn btn-outline-light px-4 follow-btn" 
                            data-user-id="<?php echo e($user->id); ?>">
                        <?php echo e(auth()->user()->following->contains($user->id) ? 'Unfollow' : 'Follow'); ?>

                    </button>
                <?php endif; ?>
            </div>  
            <div class="modal fade" id="deleteModal<?php echo e($society->id); ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo e($society->id); ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-sm rounded-3 p-3" style="max-width: 500px; margin: auto;">
                        <div class="modal-body text-center">
                            <h5 class="mb-3 text-black">Are you sure you want to delete this Society?</h5>
                            <p class="text-muted small mb-4">This action cannot be undone.</p>
                            <form action="<?php echo e(route('society.delete', $society->id)); ?>" method="POST">
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
            <div class="container section-breaker"></div>
        </div>


        <?php if(count($posts) > 0): ?>
                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="d-flex align-items-center profile-header"
                                    data-url="<?php echo e(route('profile.show', ['id' => $post->user->id])); ?>">
                                    <?php if($post->user->profile_picture): ?>
                                        <img src="<?php echo e(asset('images/profile/' . $post->user->profile_picture)); ?>"
                                            class="rounded-circle me-2"
                                            style="width: 40px; height: 40px; object-fit: cover; cursor:pointer;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2"
                                            style="width: 40px; height: 40px; cursor:pointer;">
                                            <span
                                                class="text-white"><?php echo e(strtoupper(substr($post->user->name, 0, 1))); ?></span>
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
                                <?php if($post->attachments->count()): ?>
                                    <div id="postCarousel<?php echo e($post->id); ?>" class="carousel slide mb-3"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php $__currentLoopData = $post->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="carousel-item <?php echo e($i == 0 ? 'active' : ''); ?>">
                                                    <?php if($media->file_type == 'image'): ?>
                                                        <img src="<?php echo e(asset($media->file_path)); ?>"
                                                            class="d-block w-100 rounded">
                                                    <?php elseif($media->file_type == 'video'): ?>
                                                        <video controls class="d-block w-100 rounded">
                                                            <source src="<?php echo e(asset($media->file_path)); ?>">
                                                        </video>
                                                    <?php else: ?>
                                                        <div class="p-4 text-center border">📄 <a
                                                                href="<?php echo e(asset($media->file_path)); ?>"
                                                                target="_blank">Download
                                                                <?php echo e(basename($media->file_path)); ?></a></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php if($post->attachments->count() > 1): ?>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#postCarousel<?php echo e($post->id); ?>" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#postCarousel<?php echo e($post->id); ?>" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php
                                            $userLiked = $post->likes->contains('user_id', auth()->id());
                                        ?>
                                        <form class="like-form d-inline" data-post-id="<?php echo e($post->id); ?>"
                                            data-user-id="<?php echo e(Auth::user()->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                style="border: none;">
                                                <i class="bi <?php echo e($userLiked ? 'bi-heart-fill' : 'bi-heart'); ?>"></i>
                                                <span class="like-count"><?php echo e($post->likes->count()); ?></span> Likes
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-outline-secondary comment-toggle"
                                            data-post-id="<?php echo e($post->id); ?>" style="border: none;">
                                            <i class="bi bi-chat"></i> <span class="comment-count"
                                                id="comment-count-<?php echo e($post->id); ?>"><?php echo e($post->comments->count()); ?></span>
                                        </button>

                                    </div>
                                    <?php if($post->user_id === $user->id): ?>
                                        <div class="dropdown">
                                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown"
                                                style="cursor: pointer;"></i>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editPostModal<?php echo e($post->id); ?>">
                                                        <i class="bi bi-pencil me-2"></i>Edit
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal<?php echo e($post->id); ?>">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="modal fade" id="editPostModal<?php echo e($post->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-transparent border-0">
                                        <div class="modal-body p-0">
                                            <div class="card border-0 shadow-sm rounded-3 p-3 mb-4" style="max-width: 600px; margin: auto;">
                                                <form method="POST" action="<?php echo e(route('posts.update', $post->id)); ?>" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                            
                                                    <textarea name="content" class="form-control border-0" rows="3"
                                                        style="resize: none; font-size: 16px; box-shadow: none;"><?php echo e($post->content); ?></textarea>
                            
                                                    
                                                    <div id="existingPreview<?php echo e($post->id); ?>" class="mt-2 d-flex gap-2 flex-wrap">
                                                        <?php $__currentLoopData = $post->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="position-relative">
                                                                <?php if($attachment->file_type === 'image'): ?>
                                                                    <img src="<?php echo e(asset($attachment->file_path)); ?>"
                                                                        style="max-height: 100px; max-width: 100px; object-fit: cover; border-radius: 5px;"
                                                                        class="img-thumbnail">
                                                                <?php elseif($attachment->file_type === 'video'): ?>
                                                                    <video style="max-height: 100px; max-width: 100px; border-radius: 5px;" controls>
                                                                        <source src="<?php echo e(asset($attachment->file_path)); ?>">
                                                                    </video>
                                                                <?php else: ?>
                                                                    <div class="border p-2 bg-light rounded small text-center" style="width: 100px;">
                                                                        📄 <a href="<?php echo e(asset($attachment->file_path)); ?>" target="_blank">Doc</a>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <button type="button"
                                                                    class="btn-close position-absolute top-0 end-0 remove-attachment-btn"
                                                                    data-id="<?php echo e($attachment->id); ?>" aria-label="Remove"></button>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                            
                                                    
                                                    <input type="hidden" name="removed_attachments" id="removedAttachments<?php echo e($post->id); ?>">
                            
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <div>
                                                            <label for="attachment<?php echo e($post->id); ?>"
                                                                class="btn btn-light btn-sm rounded-pill px-3"
                                                                style="font-size: 14px; cursor: pointer;">
                                                                @ Upload
                                                            </label>
                                                            <input type="file" name="attachment[]" id="attachment<?php echo e($post->id); ?>" multiple hidden>
                                                        </div>
                            
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-1"
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
                            <div class="modal fade" id="deleteModal<?php echo e($post->id); ?>" tabindex="-1"
                                aria-labelledby="deleteModalLabel<?php echo e($post->id); ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-sm rounded-3 p-3"
                                        style="max-width: 500px; margin: auto;">
                                        <div class="modal-body text-center">
                                            <h5 class="mb-3">Are you sure you want to delete this post?</h5>
                                            <p class="text-muted small mb-4">This action cannot be undone.</p>
                                            <form action="<?php echo e(route('posts.destroy', $post->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-4"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger rounded-pill px-4">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Comments Section (Hidden by default) -->
                            <div class="card-footer comment-section" id="comments-<?php echo e($post->id); ?>"
                                style="display: none;">
                                <div id="comment-list-<?php echo e($post->id); ?>">
                                    <?php if(count($post->comments) > 0): ?>
                                        <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="d-flex mb-1">
                                                <?php if($comment->user->profile_picture): ?>
                                                    <img src="<?php echo e(asset('images/profile/' . $comment->user->profile_picture)); ?>"
                                                        class="rounded-circle me-2"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2"
                                                        style="width: 32px; height: 32px;">
                                                        <span
                                                            class="text-white"><?php echo e(strtoupper(substr($comment->user->name, 0, 1))); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="flex-grow-1">
                                                    <div class="text-white rounded-3 p-2">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="fw-bold"><?php echo e($comment->user->name); ?></small>
                                                            <small
                                                                class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
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
                                            <img src="<?php echo e(asset('images/profile/' . $user->profile_picture)); ?>"
                                                class="rounded-circle me-2"
                                                style="width: 32px; height: 32px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2"
                                                style="width: 32px; height: 32px;">
                                                <span
                                                    class="text-white"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm comment-box"
                                                    name="content" placeholder="Write a comment...">
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
                <a href="#" class="create-post-btn btn btn-primary rounded-circle shadow" data-bs-toggle="modal"
data-bs-target="#createPostModal" data-bs-placement="left" title="Create Post">
<i class="bi bi-plus-lg"></i>
</a>
</div>
<?php echo $__env->make('posts.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>


<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        // Toggle comments
        $('.comment-toggle').on('click', function () {
        const postId = $(this).data('post-id');
        $(`#comments-${postId}`).toggle();
    });

    $('.remove-attachment-btn').on('click', function () {
            let attachmentId = $(this).data('id');
            let modalId = $(this).closest('.modal').attr('id');
            let postId = modalId.replace('editPostModal', '');
            let $hiddenInput = $('#removedAttachments' + postId);

            let current = $hiddenInput.val() ? $hiddenInput.val().split(',') : [];

            if (!current.includes(String(attachmentId))) {
                current.push(attachmentId);
                $hiddenInput.val(current.join(','));
            }

            $(this).parent().remove(); // Remove the attachment preview
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
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
                        url: "<?php echo e(route('notif.follow')); ?>",
                        method: 'GET',
                        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
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
            }
        });
    });
</script>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH A:\New folder\InstituteConnect\resources\views/society/show.blade.php ENDPATH**/ ?>