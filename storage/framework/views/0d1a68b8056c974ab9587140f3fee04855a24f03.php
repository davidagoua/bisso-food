<?php $__env->startSection('title','Cuisine'); ?>

<?php $__env->startSection('content'); ?>

<section class="section">
    <?php if(Session::has('msg')): ?>
        <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <div class="section-header">
        <h1><?php echo e(__('cuisines')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(__('Cuisine')); ?></div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Cuisine menu')); ?></h2>
        <p class="section-lead"><?php echo e(__('Add, Edit, Manage Cuisine. Admin can add cuisines such as Thai, Continental, Chinese, Italian, etc... And Restaurant owners can select one or more cuisines in their profile')); ?></p>
        <div class="card">
            <div class="card-header">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cuisine_add')): ?>
                    <div class="w-100">
                        <a href="<?php echo e(url('admin/cuisine/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('add new')); ?></a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th><?php echo e(__('Image')); ?></th>
                            <th><?php echo e(__('Cuisine name')); ?></th>
                            <th><?php echo e(__('Enable')); ?></th>
                            <?php if(Gate::check('cuisine_edit')): ?>
                                <th><?php echo e(__('Action')); ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $cuisines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuisine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input name="id[]" value="<?php echo e($cuisine->id); ?>" id="<?php echo e($cuisine->id); ?>" data-id="<?php echo e($cuisine->id); ?>" class="sub_chk" type="checkbox" />
                                    <label for="<?php echo e($cuisine->id); ?>"></label>
                                </td>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <img src="<?php echo e($cuisine->image); ?>" class="rounded" width="50" height="50" alt="">
                                </td>
                                <td><?php echo e($cuisine->name); ?></td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status" onclick="change_status('admin/cuisine',<?php echo e($cuisine->id); ?>)" <?php echo e(($cuisine->status == 1) ? 'checked' : ''); ?>>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                <?php if(Gate::check('cuisine_edit')): ?>
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cuisine_edit')): ?>
                                            <a href="<?php echo e(url('admin/cuisine/'.$cuisine->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cuisine_delete')): ?>
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/cuisine',<?php echo e($cuisine->id); ?>,'Cuisine')">
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
                <input type="button" value="Delete selected" onclick="deleteAll('cuisine_multi_delete','Cuisine')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'cuisine'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/cuisine/cuisine.blade.php ENDPATH**/ ?>