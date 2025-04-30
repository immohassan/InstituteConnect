<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="<?php echo e(route('users_post.create')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <textarea name="content" class="form-control border-0" rows="3" placeholder="What's on your mind?"
                        style="resize: none; font-size: 16px; box-shadow: none;"></textarea>

                    <!-- Preview Area -->
                    <div id="preview" class="mt-2 d-flex gap-2 flex-wrap"></div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <!-- Attachment Button -->
                            <label for="attachment" class="btn btn-light btn-sm rounded-pill px-3"
                                style="font-size: 14px; cursor: pointer;">
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
        </div>
    </div>
</div>
<?php /**PATH E:\PuConnect\InstituteConnect\resources\views/posts/create.blade.php ENDPATH**/ ?>