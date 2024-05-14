<?php $__env->startSection('title','Vendor'); ?>

<?php $__env->startSection('content'); ?>

<section class="section">
    <div class="section-header">
        <h1><?php echo e(__('Vendor')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(__('Vendor')); ?></div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Vendor Management System')); ?></h2>
        <p class="section-lead"><?php echo e(__('Add, Edit, Manage Vendors.')); ?></p>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    <?php echo e(session('error')); ?>

                </div>
            </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_vendor_add')): ?>
                    <div class="w-100">
                        <a href="<?php echo e(url('admin/vendor/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add New')); ?></a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th><?php echo e(__('vendor profile')); ?></th>
                            <th><?php echo e(__('vendor name')); ?></th>
                            <th><?php echo e(__('Location')); ?></th>
                            <th><?php echo e(__('Email')); ?></th>
                            <th><?php echo e(__('Enable')); ?></th>
                            <?php if(Gate::check('admin_vendor_edit')): ?>
                                <th><?php echo e(__('Action')); ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <input name="id[]" value="<?php echo e($vendor->id); ?>" id="<?php echo e($vendor->id); ?>" data-id="<?php echo e($vendor->id); ?>" class="sub_chk" type="checkbox" />
                                <label for="<?php echo e($vendor->id); ?>"></label>
                            </td>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td>
                                <img src="<?php echo e($vendor->image); ?>" width="50" height="50" class="rounded" alt="">
                            </td>
                            <th><?php echo e($vendor->name); ?></th>
                            <td><?php echo e($vendor->address); ?></td>
                            <td class="text_transform_none"><?php echo e($vendor->email_id); ?></td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" name="status" onclick="change_status('admin/vendor',<?php echo e($vendor->id); ?>)" <?php echo e(($vendor->status == 1) ? 'checked' : ''); ?>>
                                    <div class="slider"></div>
                                </label>
                            </td>

                            <?php if(Gate::check('admin_vendor_edit')): ?>
                                <td class="d-flex justify-content-center">
                                    <a href="<?php echo e(url('admin/vendor/'.$vendor->id)); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('show vendor')); ?>"><i class="fas fa-eye"></i></a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_vendor_edit')): ?>
                                        <a href="<?php echo e(url('admin/vendor/'.$vendor->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Edit')); ?>"><i class="fas fa-pencil-alt"></i></a>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_vendor_delete')): ?>
                                        <a href="javascript:void(0);" class="table-action btn btn-danger btn-action mr-1" onclick="deleteData('admin/vendor',<?php echo e($vendor->id); ?>,'Vendor')" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Delete')); ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo e(url('admin/login-to-vendor/'. $vendor->id)); ?>" class="btn btn-primary btn-action" data-toggle="tooltip" data-original-title="<?php echo e(__('Login')); ?>">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('vendor_multi_delete','Vendor')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'vendor'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/vendor/vendor.blade.php ENDPATH**/ ?>