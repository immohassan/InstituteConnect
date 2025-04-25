
<?php $__env->startSection('title', 'Browse Societies'); ?>
<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/society.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="main-heading">Societies</div>
<div class="container main-section" id="post-container">
    <?php $__currentLoopData = range(1, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card position-relative mb-5" style=" background-color: #1d1d1d; color: white; border: 1px solid #363636">
        <!-- Banner -->
        <img src="<?php echo e(asset('images/blank-profile.webp')); ?>" class="card-img-top" alt="Banner" style="height: 100px; object-fit: cover;">
    
        <!-- Profile picture -->
        <img src="<?php echo e(asset('images/blank-profile.webp')); ?>" class="rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 border border-3 border-dark" style="width: 64px; height: 64px; object-fit: cover;" alt="Profile">
    
        <!-- Card Body -->
        <div class="card-body">
            <h5 class="card-title mb-1">Mariam Bano</h5>
            <p class="card-text small mb-1 text-white-50">
                I Help Businesses Create, Grow, and Convert | Social Media Manager
            </p>
            <p class="card-text text-secondary" style="font-size: 0.85rem;">6,714 followers</p>
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">+ Follow</button>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/society.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH A:\New folder\InstituteConnect\resources\views/society/index.blade.php ENDPATH**/ ?>