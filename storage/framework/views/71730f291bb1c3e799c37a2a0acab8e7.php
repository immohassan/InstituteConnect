
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/mobile.css')); ?>">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
<?php $__env->stopPush(); ?>
<?php
    if(Auth::user()->role == 'user'){
        Auth::logout();
        return redirect('/home');
    }
?>
<?php $__env->startSection('content'); ?>
<div class="main-heading">Admin Access Portal</div>
<div class="container main-section-2" id="post-container">
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>User Name</th>
                <th style="width: 30%">Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($user->name); ?></td>
                <td><?php echo e(ucfirst($user->role)); ?></td>
                <td>
                    <i class="bi bi-pencil-square" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo e($user->id); ?>" style="cursor: pointer;"></i>
                    <i class="bi bi-trash2-fill" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($user->id); ?>" style="cursor: pointer;"></i>
                </td>
            </tr>
            <div class="modal fade" id="deleteModal<?php echo e($user->id); ?>" tabindex="-1"
                aria-labelledby="deleteModalLabel<?php echo e($user->id); ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-sm rounded-3 p-3"
                        style="max-width: 500px; margin: auto;">
                        <div class="modal-body text-center">
                            <h5 class="mb-3">Are you sure you want to delete this user?</h5>
                            <p class="text-muted small mb-4">This action cannot be undone.</p>
                            <form action="<?php echo e(route('user.delete', $user->id)); ?>" method="POST">
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

            <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal<?php echo e($user->id); ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo e($user->id); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('user.update', $user->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel<?php echo e($user->id); ?>">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name<?php echo e($user->id); ?>" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name<?php echo e($user->id); ?>" name="name" value="<?php echo e($user->name); ?>" required>
                    </div>
                    <!-- Role Dropdown -->
                    <div class="mb-3">
                        <label for="role<?php echo e($user->id); ?>" class="form-label">Role</label>
                        <select class="form-select" id="role<?php echo e($user->id); ?>" name="role" required>
                            <?php $__currentLoopData = ['User', 'Dev', 'Admin', 'Sub-admin', 'Super-Admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($roleOption); ?>" <?php echo e(ucfirst($user->role) === $roleOption ? 'selected' : ''); ?>><?php echo e($roleOption); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="<?php echo e(route('user.add')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Email</label>
                                <input type="Email" class="form-control" id="email" name="email" value="" required>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="" required>
                            </div>
                            <!-- Role Dropdown -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <?php $__currentLoopData = ['User', 'Dev', 'Admin', 'Sub-admin', 'Super-Admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($roleOption); ?>"><?php echo e($roleOption); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </table>
    <a href="#" 
    class="create-post-btn btn btn-primary rounded-circle shadow" 
    data-bs-toggle="modal" 
    data-bs-target="#addUserModal"
    data-bs-placement="left"
    title="Create User">
    <i class="bi bi-plus-lg"></i>
</a>  
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
<script>
    $(document).ready( function () {
    $('#myTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true
    });
} );
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH A:\New folder\InstituteConnect\resources\views/admin/portal.blade.php ENDPATH**/ ?>