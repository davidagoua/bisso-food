<?php $__env->startSection('title','Language'); ?>

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h1><?php echo e(__('Language')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(__('Language')); ?></div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Language management')); ?></h2>
        <p class="section-lead"><?php echo e(__('Language')); ?></p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('language_add')): ?>
                        <a href="<?php echo e(url('admin/language/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add New')); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo e(__('language Image')); ?></th>
                                <th><?php echo e(__('Language')); ?></th>
                                <th><?php echo e(__('Direction')); ?></th>
                                <th><?php echo e(__('status')); ?></th>
                                <?php if(Gate::check('language_edit')): ?>
                                <th><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <img src="<?php echo e($language->image); ?>" width="50" height="50" class="rounded-lg" alt="">
                                </td>
                                <td><?php echo e($language->name); ?></td>
                                <td><?php echo e($language->direction); ?></td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status"
                                            onclick="change_status('admin/language',<?php echo e($language->id); ?>)"
                                            <?php echo e(($language->status == 1) ? 'checked' : ''); ?>>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                <?php if(Gate::check('language_edit')): ?>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('language_edit')): ?>
                                        <a href="<?php echo e(url('admin/language/'.$language->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Edit Language')); ?>"><i class="fas fa-pencil-alt"></i></a>
                                    <?php endif; ?>
                                    <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/language',<?php echo e($language->id); ?>,'Language')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'language'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/language/language.blade.php ENDPATH**/ ?>