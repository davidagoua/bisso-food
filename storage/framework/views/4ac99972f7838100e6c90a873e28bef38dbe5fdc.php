<?php $__env->startSection('title','Feedback And Support'); ?>

<?php $__env->startSection('content'); ?>
<section class="section">
    <?php if(Session::has('msg')): ?>
        <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <div class="section-header">
        <h1><?php echo e(__('Feedback & support')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(__('Feedback & support')); ?></div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Feedback & support management system')); ?></h2>
        <p class="section-lead"><?php echo e(__('Show users feedback.')); ?></p>
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo e(__('User name')); ?></th>
                            <th><?php echo e(__('Image')); ?></th>
                            <th><?php echo e(__('Rate')); ?></th>
                            <th><?php echo e(__('Comment')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($feedback->user); ?></td>
                            <td>
                                <?php if($feedback->image != null): ?>
                                <?php
                                    $feedback_images = json_decode($feedback->image)
                                ?>
                                    <?php $__currentLoopData = $feedback_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback_image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img alt="image" src="<?php echo e(url('images/upload/'.$feedback_image)); ?>" class="rounded-circle" width="35" height="35">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php for($i = 1; $i < 6; $i++): ?>
                                    <?php if($feedback->rate >= $i): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </td>
                            <td><?php echo e($feedback->comment); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'feedback'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/admin setting/feedback.blade.php ENDPATH**/ ?>