

<?php $__env->startSection('content'); ?>
<?php
    $posts = $posts->filter(function($post) use ($user) {
        return $post->user_id === $user->id;
    });
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <!-- User Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <?php if($user->profile_picture): ?>
                        <img src="<?php echo e(asset('images/profile/' . $user->profile_picture)); ?>" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;" alt="<?php echo e($user->name); ?>'s profile">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; background-color: #1e1e1e;">
                            <span class="text-white fs-1"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                        </div>
                    <?php endif; ?>
                    <h5 class="card-title" style="color: #1E1E1E;"><?php echo e($user->name); ?></h5>
                    <p class="text-muted mb-1"><?php echo e($user->department ?: 'Department not set'); ?></p>
                    <p class="text-muted mb-3"><?php echo e($user->bio ?: 'No bio added yet'); ?></p>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary rounded-pill px-4 py-1">Edit Profile</a>
                    </div>
                </div>
            </div>

            <!-- Societies -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Your Societies</h5>
                </div>
                <div class="card-body">
                    <?php if(count($societies) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php $__currentLoopData = $societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $society): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo e($society->name); ?>

                                    <span class="badge bg-primary rounded-pill" ><?php echo e($society->pivot->role ?? 'member'); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">You are not part of any societies yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Create Post -->
            <div class="card border-0 shadow-sm rounded-3 p-3 mb-4" style="max-width: 600px; margin: auto;">
                <form method="POST" action="<?php echo e(route('users_post.create')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <textarea 
                        name="content" 
                        class="form-control border-0" 
                        rows="3" 
                        placeholder="What's on your mind?" 
                        style="resize: none; font-size: 16px; box-shadow: none;"></textarea>
            
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
                        <div class="card-header bg-white">
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
                                <?php if($post->user_id === $user->id || $user->isAdmin() || $user->isSuperAdmin()): ?>
                                <div class="d-flex gap-2">
                                    <?php if($post->user_id === $user->id): ?>
                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-outline-secondary" title="Edit" data-bs-toggle="modal" data-bs-target="#editPostModal<?php echo e($post->id); ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    <?php endif; ?>
                            
                                    <!-- Delete Button -->
                                    <button class="btn btn-sm btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($post->id); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
                        <div class="card-footer bg-white comment-section" id="comments-<?php echo e($post->id); ?>" style="display: none;">
                            <?php if(count($post->comments) > 0): ?>
                                <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex mb-3">
                                        <?php if($comment->user->profile_picture): ?>
                                            <img src="<?php echo e(asset('images/profile/' . $comment->user->profile_picture)); ?>" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <span class="text-white"><?php echo e(strtoupper(substr($comment->user->name, 0, 1))); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <div class="bg-light rounded-3 p-2">
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
                            
                            <!-- Comment Form -->
                            <form action="<?php echo e(route('comments.store')); ?>" method="POST">
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
                                            <input type="text" class="form-control form-control-sm" name="content" placeholder="Write a comment...">
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
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    <?php if(count($announcements) > 0): ?>
                        <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-bottom pb-3 mb-3">
                                <h6><?php echo e($announcement->title); ?></h6>
                                <p class="text-muted small mb-1"><?php echo e($announcement->created_at->format('M d, Y')); ?> by <?php echo e($announcement->user->name); ?></p>
                                <p class="small"><?php echo e(Str::limit($announcement->content, 100)); ?></p>
                                <a href="<?php echo e(route('announcements.show', $announcement->id)); ?>" class="btn btn-sm btn-link p-0">Read more</a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted">No announcements available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Academic Resources -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Academic Resources</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('resources.results')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-bar-graph text-primary me-2"></i>
                                Results
                            </div>
                            <span class="badge rounded-pill" style="background-color: #ff6b6b; color: white;">New</span>
                        </a>
                        <a href="<?php echo e(route('resources.attendance')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-calendar-check text-primary me-2"></i>
                                Attendance
                            </div>
                        </a>
                        <a href="<?php echo e(route('subjects.index')); ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-book text-primary me-2"></i>
                            Subjects
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function () {
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PuConnect\InstituteConnect\resources\views/dashboard.blade.php ENDPATH**/ ?>