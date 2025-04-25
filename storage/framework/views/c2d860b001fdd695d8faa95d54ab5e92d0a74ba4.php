
<?php $__env->startSection('title', 'Events Calendar'); ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/events.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="main-heading">Upcoming Events</div>
<div class="container main-section" id="post-container">
    <div class="text-white fs-4 sub-heading">Events</div>
    <div class="container main-section2">
        <div class="calendar-container">
        <iframe src="https://calendar.google.com/calendar/embed?src=immohassan06%40gmail.com&ctz=Asia%2FKarachi" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        </div>
    </div>
    <a href="<?php echo e(route('posts.create')); ?>" class="create-post-btn btn btn-primary rounded-circle shadow" data-bs-toggle="tooltip"
    data-bs-placement="left"
    title="Create Post">                    
        <i class="bi bi-plus-lg"></i>
    </a>  
</div>
<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PuConnect\InstituteConnect\resources\views/events/events.blade.php ENDPATH**/ ?>