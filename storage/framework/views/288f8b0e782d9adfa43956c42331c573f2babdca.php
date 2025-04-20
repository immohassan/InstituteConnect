<div class="card post-card mb-4">
    <div class="post-header">
        <div class="d-flex align-items-center">
            <?php if($post->user->profile_picture): ?>
                <a href="<?php echo e(route('profile.show', $post->user)); ?>">
                    <img src="<?php echo e(Storage::url($post->user->profile_picture)); ?>" alt="<?php echo e($post->user->name); ?>" class="avatar avatar-md">
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('profile.show', $post->user)); ?>">
                    <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center">
                        <?php echo e(strtoupper(substr($post->user->name, 0, 1))); ?>

                    </div>
                </a>
            <?php endif; ?>
            <div class="post-meta ms-3">
                <div class="d-flex align-items-center">
                    <a href="<?php echo e(route('profile.show', $post->user)); ?>" class="text-dark text-decoration-none">
                        <h6 class="post-author mb-0"><?php echo e($post->user->name); ?></h6>
                    </a>
                    <?php if($post->society): ?>
                        <span class="mx-2">•</span>
                        <a href="<?php echo e(route('societies.show', $post->society)); ?>" class="text-decoration-none">
                            <small class="text-primary"><?php echo e($post->society->name); ?></small>
                        </a>
                    <?php endif; ?>
                </div>
                <small class="post-time text-muted"><?php echo e($post->created_at->diffForHumans()); ?></small>
            </div>
        </div>
    </div>
    
    <div class="post-content">
        <p><?php echo e(Str::limit($post->content, 500)); ?></p>
        
        <?php if(Str::length($post->content) > 500): ?>
            <a href="<?php echo e(route('posts.show', $post)); ?>" class="text-primary">Read more</a>
        <?php endif; ?>
        
        <?php if($post->image): ?>
            <div class="text-center">
                <img src="<?php echo e(Storage::url($post->image)); ?>" class="post-image" alt="Post image">
            </div>
        <?php endif; ?>
    </div>
    
    <div class="post-actions">
        <div class="d-flex justify-content-between w-100">
            <div>
                <?php if(auth()->check() && auth()->user()->hasLiked($post)): ?>
                    <form action="<?php echo e(route('posts.unlike', $post)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-action active">
                            <i class="bi bi-hand-thumbs-up-fill"></i> Liked (<?php echo e($post->likes->count()); ?>)
                        </button>
                    </form>
                <?php else: ?>
                    <form action="<?php echo e(route('posts.like', $post)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-action">
                            <i class="bi bi-hand-thumbs-up"></i> Like (<?php echo e($post->likes->count()); ?>)
                        </button>
                    </form>
                <?php endif; ?>
                
                <a href="<?php echo e(route('posts.show', $post)); ?>" class="btn btn-action">
                    <i class="bi bi-chat"></i> Comment (<?php echo e($post->comments->count()); ?>)
                </a>
            </div>
            
            <div>
                <?php if($post->user_id === auth()->id() || auth()->user()->isSuperAdmin()): ?>
                    <a href="<?php echo e(route('posts.edit', $post)); ?>" class="btn btn-action">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if($post->comments->count() > 0): ?>
        <div class="comment-list">
            <?php $__currentLoopData = $post->comments->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="comment-item">
                    <div class="comment-avatar">
                        <?php if($comment->user->profile_picture): ?>
                            <img src="<?php echo e(Storage::url($comment->user->profile_picture)); ?>" alt="<?php echo e($comment->user->name); ?>" class="avatar avatar-sm">
                        <?php else: ?>
                            <div class="avatar avatar-sm bg-primary text-white d-flex align-items-center justify-content-center">
                                <?php echo e(strtoupper(substr($comment->user->name, 0, 1))); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <a href="<?php echo e(route('profile.show', $comment->user)); ?>" class="comment-author text-decoration-none text-dark">
                                <?php echo e($comment->user->name); ?>

                            </a>
                            <span class="comment-time"><?php echo e($comment->created_at->diffForHumans()); ?></span>
                        </div>
                        <div class="comment-body">
                            <?php echo e($comment->content); ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <?php if($post->comments->count() > 3): ?>
                <div class="text-center mt-2">
                    <a href="<?php echo e(route('posts.show', $post)); ?>" class="text-primary">View all <?php echo e($post->comments->count()); ?> comments</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <form action="<?php echo e(route('comments.store', $post)); ?>" method="POST" class="comment-form">
        <?php echo csrf_field(); ?>
        <input type="text" name="content" placeholder="Write a comment..." required>
        <button type="submit" class="btn btn-primary btn-sm">Post</button>
    </form>
</div>
<?php /**PATH A:\New folder\InstituteConnect\resources\views/components/post-card.blade.php ENDPATH**/ ?>