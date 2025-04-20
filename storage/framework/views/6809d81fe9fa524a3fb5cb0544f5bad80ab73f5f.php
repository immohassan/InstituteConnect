<?php $__env->startSection('title', 'Posts Feed'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Posts Feed</h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create_posts')): ?>
            <a href="<?php echo e(route('posts.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create Post
            </a>
            <?php endif; ?>
        </div>

        <?php if(request('society_id')): ?>
            <?php
                $society = App\Models\Society::find(request('society_id'));
            ?>
            <?php if($society): ?>
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <?php if($society->logo): ?>
                            <img src="<?php echo e(Storage::url($society->logo)); ?>" alt="<?php echo e($society->name); ?>" class="avatar avatar-md me-3">
                        <?php else: ?>
                            <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                <?php echo e(strtoupper(substr($society->name, 0, 1))); ?>

                            </div>
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-0">Viewing posts from <?php echo e($society->name); ?></h5>
                            <a href="<?php echo e(route('posts.index')); ?>">Clear filter</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('components.post-card', ['post' => $post], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-newspaper text-muted display-4"></i>
                        <p class="mt-3 mb-0 text-muted">No posts to display yet.</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create_posts')): ?>
                            <a href="<?php echo e(route('posts.create')); ?>" class="btn btn-primary mt-3">Create your first post</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center mt-4">
            <?php echo e($posts->links()); ?>

        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Your Societies</h5>
            </div>
            <div class="card-body">
                <?php if(auth()->user()->societies->count() > 0): ?>
                    <div class="list-group">
                        <?php $__currentLoopData = auth()->user()->societies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $society): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('posts.index', ['society_id' => $society->id])); ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                <?php if($society->logo): ?>
                                    <img src="<?php echo e(Storage::url($society->logo)); ?>" alt="<?php echo e($society->name); ?>" class="avatar avatar-sm me-3">
                                <?php else: ?>
                                    <div class="avatar avatar-sm bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                        <?php echo e(strtoupper(substr($society->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0"><?php echo e($society->name); ?></h6>
                                    <small class="text-muted"><?php echo e(ucfirst($society->pivot->role)); ?></small>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">You are not a member of any society yet.</p>
                    <a href="<?php echo e(route('societies.index')); ?>" class="btn btn-outline-primary w-100">Browse Societies</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Announcements</h5>
            </div>
            <div class="card-body">
                <?php
                    $announcements = App\Models\Announcement::with('user', 'society')
                        ->where('status', 'active')
                        ->where(function($query) {
                            $query->whereNull('end_date')
                                ->orWhere('end_date', '>=', now());
                        })
                        ->latest()
                        ->take(3)
                        ->get();
                ?>
                
                <?php if($announcements->count() > 0): ?>
                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('components.announcement-card', ['announcement' => $announcement, 'compact' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('announcements.index')); ?>" class="btn btn-sm btn-outline-primary">View All Announcements</a>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">No announcements to display.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH A:\New folder\InstituteConnect\resources\views/posts/index.blade.php ENDPATH**/ ?>