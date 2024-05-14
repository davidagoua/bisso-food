<?php $__env->startSection('title','user'); ?>

<?php $__env->startSection('content'); ?>

<section class="section">
    <?php if(Session::has('msg')): ?>
        <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <div class="section-header">
        <h1><?php echo e(__('users')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('User')); ?></div>
            </div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('User management panel')); ?></h2>
        <p class="section-lead"><?php echo e(__('Add, Edit, Manage Users.')); ?></p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    <a href="<?php echo e(url('admin/user/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add New')); ?></a>
                </div>
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
                            <th><?php echo e(__('user name')); ?></th>
                            <th><?php echo e(__('Role')); ?></th>
                            <th><?php echo e(__('phone')); ?></th>
                            <th><?php echo e(__('email')); ?></th>
                            <th><?php echo e(__('enable')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input name="id[]" value="<?php echo e($user->id); ?>" id="<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="sub_chk" type="checkbox" />
                                    <label for="<?php echo e($user->id); ?>"></label>
                                </td>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td>
                                    <?php $__currentLoopData = $user['roles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge badge-success"><?php echo e($item->title); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td><?php echo e($user->phone); ?></td>
                                <td class="text_transform_none"><?php echo e($user->email_id); ?></td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status" onclick="change_status('admin/user',<?php echo e($user->id); ?>)" <?php echo e(($user->status == 1) ? 'checked' : ''); ?>>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                <?php if(Gate::check('user_edit') && Gate::check('user_access') && Gate::check('user_delete')): ?>
                                <td class="d-flex">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_edit')): ?>
                                        <a href="<?php echo e(url('admin/user/'.$user->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1 <?php echo e($user->id == 1 ? 'disabled' : ''); ?>" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Edit user')); ?>"><i class="fas fa-pencil-alt"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_access')): ?>
                                        <a href="<?php echo e(url('admin/user/'.$user->id)); ?>" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('show user profile')); ?>" class="btn btn-primary btn-action mr-1 <?php echo e($user->id == 1 ? 'disabled' : ''); ?>"><i class="fas fa-eye"></i></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_delete')): ?>
                                        <a href="javascript:void(0);" class="table-action btn btn-danger btn-action <?php echo e($user->id == 1 ? 'disabled' : ''); ?>" onclick="deleteData('admin/user',<?php echo e($user->id); ?>,'User')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(url('admin/user/user_wallet/'.$user->id)); ?>" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('User Wallet')); ?>" class="btn btn-info btn-action ml-1 <?php echo e($user->id == 1 ? 'disabled' : ''); ?>"><i class="fas fa-wallet"></i></a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('user_multi_delete','user')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'user'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/user/user.blade.php ENDPATH**/ ?>