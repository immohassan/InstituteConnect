<?php $__env->startSection('content'); ?>
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
                <form method="POST" action="<?php echo e(route('posts.store')); ?>" enctype="multipart/form-data">
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <form action="<?php echo e(route('posts.like', $post->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm <?php echo e($user->hasLiked($post) ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                            <i class="bi bi-heart-fill"></i> <?php echo e($post->likes->count()); ?> Likes
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-secondary ms-2 comment-toggle" data-post-id="<?php echo e($post->id); ?>">
                                        <i class="bi bi-chat"></i> <?php echo e($post->comments->count()); ?> Comments
                                    </button>
                                </div>
                                <?php if($post->user_id === $user->id || $user->isAdmin() || $user->isSuperAdmin()): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo e($post->id); ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?php echo e($post->id); ?>">
                                            <?php if($post->user_id === $user->id): ?>
                                                <li><a class="dropdown-item" href="<?php echo e(route('posts.edit', $post->id)); ?>">Edit</a></li>
                                            <?php endif; ?>
                                            <li>
                                                <form action="<?php echo e(route('posts.destroy', $post->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
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

    document.getElementById('attachment').addEventListener('change', function (event) {
        const preview = document.getElementById('preview');
        preview.innerHTML = ''; // clear old previews
        Array.from(event.target.files).forEach(file => {
            const fileType = file.type;
            const reader = new FileReader();

            reader.onload = function (e) {
                if (fileType.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    img.className = 'rounded border';
                    preview.appendChild(img);
                } else {
                    const fileDiv = document.createElement('div');
                    fileDiv.textContent = file.name;
                    fileDiv.className = 'small text-muted border rounded p-1';
                    preview.appendChild(fileDiv);
                }
            };

            reader.readAsDataURL(file);
        });
    });

</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH A:\New folder\InstituteConnect\resources\views/dashboard.blade.php ENDPATH**/ ?>