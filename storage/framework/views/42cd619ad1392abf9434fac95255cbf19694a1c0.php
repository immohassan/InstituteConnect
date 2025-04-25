
<?php $__env->startSection('title', 'Resources'); ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/resources.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="main-heading">Resources</div>
<div class="container main-section" id="post-container">
    <?php $__currentLoopData = range(1, 8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($i % 2 == 1): ?> <div class="row g-4"> <?php endif; ?>

        <!-- Semester Card -->
        <div class="col-md-6 mb-4">
            <div class="folder-card d-flex justify-content-between align-items-start p-3"
                data-bs-toggle="collapse"
                data-bs-target="#semester-<?php echo e($i); ?>"
                style="cursor: pointer;">
                <div>
                    <i class="bi bi-folder-fill text-secondary"></i>
                    <h6 class="mt-2 mb-1 text-white">
                        <?php echo e($i); ?><?php echo e($i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th'))); ?> Semester
                    </h6>
                </div>
                <i class="bi bi-three-dots-vertical text-white"></i>
            </div>

            <!-- Collapsible content under each card -->
            <div class="collapse mt-4" id="semester-<?php echo e($i); ?>">
                <div class="row g-3">
                    <?php for($j = 1; $j <= 6; $j++): ?>
                    <div class="col-6">
                        <div class="folder-card p-3">
                            <i class="bi bi-journal-text text-info"></i>
                            <h6 class="mt-2 text-white">Subject <?php echo e($j); ?></h6>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

    <?php if($i % 2 == 0): ?> </div> <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PuConnect\InstituteConnect\resources\views/resources/resources.blade.php ENDPATH**/ ?>