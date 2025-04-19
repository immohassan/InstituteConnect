<?php $__env->startSection('title', 'Academic Results'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Academic Results</h2>
        <p class="text-muted">View your academic performance and semester results.</p>
    </div>
    <div class="col-md-6">
        <form id="results-filter-form" action="<?php echo e(route('resources.results')); ?>" method="GET" class="d-flex justify-content-end">
            <div class="input-group" style="max-width: 400px;">
                <select name="year" class="form-select">
                    <option value="">Select Year</option>
                    <?php $__currentLoopData = $allResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($result->year); ?>" <?php echo e(request('year', $currentYear) == $result->year ? 'selected' : ''); ?>>
                            Year <?php echo e($result->year); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="semester" class="form-select">
                    <option value="">Select Semester</option>
                    <?php $__currentLoopData = $allResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($result->semester); ?>" <?php echo e(request('semester', $currentSemester) == $result->semester ? 'selected' : ''); ?>>
                            Semester <?php echo e($result->semester); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>

<?php if($currentYear && $currentSemester): ?>
    <div class="alert alert-info">
        <h5 class="alert-heading">Viewing results for Year <?php echo e($currentYear); ?>, Semester <?php echo e($currentSemester); ?></h5>
        <p class="mb-0">GPA: <strong><?php echo e(number_format($gpa, 2)); ?></strong></p>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Results</h5>
            </div>
            <div class="card-body">
                <?php if($results->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped results-table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Credit Hours</th>
                                    <th>Marks</th>
                                    <th>Grade</th>
                                    <th>GPA</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalCredits = 0; ?>
                                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $totalCredits += $result->subject->credit_hours; ?>
                                    <tr>
                                        <td><?php echo e($result->subject->code); ?></td>
                                        <td><?php echo e($result->subject->name); ?></td>
                                        <td><?php echo e($result->subject->credit_hours); ?></td>
                                        <td><?php echo e($result->marks); ?></td>
                                        <td class="grade-cell"><?php echo e($result->grade); ?></td>
                                        <td><?php echo e(number_format($result->gpa, 2)); ?></td>
                                        <td><?php echo e($result->comments ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong><?php echo e($totalCredits); ?></strong></td>
                                    <td colspan="2"></td>
                                    <td><strong><?php echo e(number_format($gpa, 2)); ?></strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x text-muted display-4"></i>
                        <p class="mt-3 mb-0 text-muted">No results found for the selected semester.</p>
                        <?php if($allResults->count() > 0): ?>
                            <p class="text-muted">Try selecting a different semester from the filter above.</p>
                        <?php else: ?>
                            <p class="text-muted">Results will be published by your academic department soon.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">GPA Scale Reference</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Grade</th>
                                <th>Grade Point</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A+</td>
                                <td>4.0</td>
                                <td>90-100%</td>
                            </tr>
                            <tr>
                                <td>A</td>
                                <td>4.0</td>
                                <td>85-89%</td>
                            </tr>
                            <tr>
                                <td>A-</td>
                                <td>3.7</td>
                                <td>80-84%</td>
                            </tr>
                            <tr>
                                <td>B+</td>
                                <td>3.3</td>
                                <td>75-79%</td>
                            </tr>
                            <tr>
                                <td>B</td>
                                <td>3.0</td>
                                <td>70-74%</td>
                            </tr>
                            <tr>
                                <td>B-</td>
                                <td>2.7</td>
                                <td>65-69%</td>
                            </tr>
                            <tr>
                                <td>C+</td>
                                <td>2.3</td>
                                <td>60-64%</td>
                            </tr>
                            <tr>
                                <td>C</td>
                                <td>2.0</td>
                                <td>55-59%</td>
                            </tr>
                            <tr>
                                <td>C-</td>
                                <td>1.7</td>
                                <td>50-54%</td>
                            </tr>
                            <tr>
                                <td>D+</td>
                                <td>1.3</td>
                                <td>45-49%</td>
                            </tr>
                            <tr>
                                <td>D</td>
                                <td>1.0</td>
                                <td>40-44%</td>
                            </tr>
                            <tr>
                                <td>F</td>
                                <td>0.0</td>
                                <td>0-39%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Academic Standing</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>GPA Range</th>
                                <th>Academic Standing</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>3.7 - 4.0</td>
                                <td>
                                    <span class="badge bg-success">Exceptional</span>
                                    <small class="text-muted d-block">First Class Honors</small>
                                </td>
                            </tr>
                            <tr>
                                <td>3.3 - 3.69</td>
                                <td>
                                    <span class="badge bg-success">Excellent</span>
                                    <small class="text-muted d-block">Upper Second Class Honors</small>
                                </td>
                            </tr>
                            <tr>
                                <td>3.0 - 3.29</td>
                                <td>
                                    <span class="badge bg-primary">Very Good</span>
                                    <small class="text-muted d-block">Lower Second Class Honors</small>
                                </td>
                            </tr>
                            <tr>
                                <td>2.7 - 2.99</td>
                                <td>
                                    <span class="badge bg-info">Good</span>
                                    <small class="text-muted d-block">Third Class Honors</small>
                                </td>
                            </tr>
                            <tr>
                                <td>2.0 - 2.69</td>
                                <td>
                                    <span class="badge bg-warning text-dark">Satisfactory</span>
                                    <small class="text-muted d-block">Pass</small>
                                </td>
                            </tr>
                            <tr>
                                <td>Less than 2.0</td>
                                <td>
                                    <span class="badge bg-danger">Probation</span>
                                    <small class="text-muted d-block">Academic Warning</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <?php if($gpa > 0): ?>
                    <div class="alert alert-info mt-3 mb-0">
                        <h6 class="alert-heading">Your Current Standing</h6>
                        <?php if($gpa >= 3.7): ?>
                            <p class="mb-0">With a GPA of <?php echo e(number_format($gpa, 2)); ?>, you are in <strong>Exceptional</strong> academic standing. Congratulations!</p>
                        <?php elseif($gpa >= 3.3): ?>
                            <p class="mb-0">With a GPA of <?php echo e(number_format($gpa, 2)); ?>, you are in <strong>Excellent</strong> academic standing. Great job!</p>
                        <?php elseif($gpa >= 3.0): ?>
                            <p class="mb-0">With a GPA of <?php echo e(number_format($gpa, 2)); ?>, you are in <strong>Very Good</strong> academic standing. Keep it up!</p>
                        <?php elseif($gpa >= 2.7): ?>
                            <p class="mb-0">With a GPA of <?php echo e(number_format($gpa, 2)); ?>, you are in <strong>Good</strong> academic standing.</p>
                        <?php elseif($gpa >= 2.0): ?>
                            <p class="mb-0">With a GPA of <?php echo e(number_format($gpa, 2)); ?>, you are in <strong>Satisfactory</strong> academic standing. Consider improving your study habits.</p>
                        <?php else: ?>
                            <p class="mb-0">With a GPA of <?php echo e(number_format($gpa, 2)); ?>, you are on <strong>Academic Probation</strong>. Please consult with an academic advisor.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH A:\New folder\InstituteConnect\resources\views/resources/results.blade.php ENDPATH**/ ?>