<?php $__env->startSection('title','Tax'); ?>

<?php $__env->startSection('content'); ?>

<section class="section">
    <?php if(Session::has('msg')): ?>
        <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <div class="section-header">
        <h1><?php echo e(__('Tax')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('Tax')); ?></div>
            </div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Tax Management System')); ?></h2>
        <p class="section-lead"><?php echo e(__('Define All Tax.')); ?></p>
        <div class="card">
            <div class="card-header">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tax_add')): ?>
                    <div class="w-100">
                        <a href="<?php echo e(url('admin/tax/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add tax')); ?></a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th><?php echo e(__('tax name')); ?></th>
                            <th><?php echo e(__('tax type')); ?></th>
                            <th><?php echo e(__('Tax')); ?></th>
                            <th><?php echo e(__('Enable')); ?></th>
                            <?php if(Gate::check('tax_edit') && Gate::check('tax_delete')): ?>
                                <th><?php echo e(__('Action')); ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $taxs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input name="id[]" value="<?php echo e($tax->id); ?>" id="<?php echo e($tax->id); ?>" data-id="<?php echo e($tax->id); ?>" class="sub_chk" type="checkbox" />
                                    <label for="<?php echo e($tax->id); ?>"></label>
                                </td>
                                <th><?php echo e($loop->iteration); ?></th>
                                <td><?php echo e($tax->name); ?></td>
                                <td><?php echo e($tax->type); ?></td>
                                <?php if($tax->type == 'amount'): ?>
                                    <td><?php echo e($currency); ?><?php echo e($tax->tax); ?></td>
                                <?php endif; ?>
                                <?php if($tax->type == 'percentage'): ?>
                                    <td><?php echo e($tax->tax); ?>%</td>
                                <?php endif; ?>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status" onclick="change_status('admin/tax',<?php echo e($tax->id); ?>)" <?php echo e(($tax->status == 1) ? 'checked' : ''); ?>>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                <?php if(Gate::check('tax_edit') && Gate::check('tax_delete')): ?>
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tax_edit')): ?>
                                            <a href="<?php echo e(url('admin/tax/'.$tax->id.'/edit')); ?>" class="btn btn-primary" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Edit')); ?>"><i class="fas fa-pencil-alt"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tax_delete')): ?>
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/tax',<?php echo e($tax->id); ?>,'Tax')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('tax_multi_delete','Tax')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'tax'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/tax/tax.blade.php ENDPATH**/ ?>