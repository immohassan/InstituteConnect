
<?php $__env->startSection('title','Search | Campus Connect'); ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/search.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="main-heading">Search</div>
<div class="container main-section" id="post-container">
    <div class="">
        <input type="text" class="form-control search-bar" id="user-search" placeholder="Search">
    </div>
    <div class="mt-1 text-secondary mb-5">
        Follow suggestions
    </div>
    
    <div id="search-loader" class="text-center my-3" style="display: none;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="suggestions">
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
    <div class=" suggestion-block mt-2 d-flex align-items-center justify-content-between p-3 rounded shadow-sm" style="background-color: #1a1a1a; cursor: pointer;" data-url="<?php echo e(route('profile.show', ['id' => $user->id])); ?>" >
        <div class="d-flex align-items-center">
            <img src="<?php echo e($user->profile_picture ? asset('images/profile/' . $user->profile_picture) : asset('images/blank-profile.webp')); ?>" alt="User Avatar" class="rounded-circle me-3" width="48" height="48">
            <div>
                <div class="d-flex align-items-center">
                    <strong class="text-white me-1"><?php echo e($user->name); ?></strong>
                    <?php if($user->role == 'dev'): ?>
                    <i class="devicon-devicon-plain" title="Developer of the App"></i>
                    <?php endif; ?>
                </div>
                <small class="user-department"><?php echo e($user->department ? $user->department : "No Department Yet"); ?></small>
            </div>
        </div>

        <?php if(Auth::user()->id == $user->id): ?>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-light btn-sm px-4" style="text-decoration: none;">
                        Edit Profile
                    </a>
                <?php else: ?>
                    <button id="" 
                            class="btn btn-outline-light btn-sm px-4 follow-btn" 
                            data-user-id="<?php echo e($user->id); ?>">
                        <?php echo e(auth()->user()->following->contains($user->id) ? 'Unfollow' : 'Follow'); ?>

                    </button>
                <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $(document).on('click', '.suggestion-block', function () {
    const url = $(this).data('url');
    window.location.href = url;
});

    $('#user-search').on('keyup', function () {
    const query = $(this).val();

    if (query.length > 1) {
        $('#search-loader').show(); // Show loader
        $.ajax({
            url: "<?php echo e(route('users.search')); ?>",
            type: "GET",
            data: { name: query },
            success: function (data) {
                $('.suggestions').html(data);
            },
            complete: function () {
                $('#search-loader').hide(); // Hide loader
            }
        });
    } else {
        // Reload static suggestions when input is cleared
        $.ajax({
            url: '<?php echo e(route("users.static")); ?>', // <- new route for static suggestions
            type: 'GET',
            success: function (data) {
                $('.suggestions').html(data);
            }
        });
    }
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
                // Toggle follow/unfollow text
                button.text(isFollowing ? 'Follow' : 'Unfollow');

                // Update followers count
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
                            contents: "Hey "+res.ReceptorUserName+"! " +res.InitiatorName+" followed you!",
                            subscriptionIds: res.postUserSubscriptionId,
                            url: window.location.href // or the post URL
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
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PuConnect\InstituteConnect\resources\views/search/search.blade.php ENDPATH**/ ?>