<?php $__env->startSection('title','Delivery Zone'); ?>

<?php $__env->startSection('content'); ?>

<section class="section">
    <?php if(Session::has('msg')): ?>
    <script>
        var msg = "<?php echo Session::get('msg'); ?>"
            $(window).on('load', function()
            {
                iziToast.success({
                    message: msg,
                    position: 'topRight'
                });
            });
    </script>
    <?php endif; ?>
    <div class="section-header">
        <h1><?php echo e(__('Delivery zone')); ?></h1>
        <div class="section-header-breadcrumb">
            <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('Delivery zone')); ?></div>
            <?php endif; ?>
            <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                <div class="breadcrumb-item active"><a href="<?php echo e(url('vendor/vendor_home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('Delivery zone')); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Delivery Zone Management')); ?></h2>
        <p class="section-lead"><?php echo e(__('Add, Edit, Manage Delivery Zone.')); ?></p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery_zone_add')): ?>
                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                            <a href="<?php echo e(url('admin/delivery_zone/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add New')); ?></a>
                        <?php endif; ?>
                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                            <a href="<?php echo e(url('vendor/deliveryZone/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add New')); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    <input name="select_all" value="1" id="master" type="checkbox" />
                                    <label for="master"></label>
                                </th>
                                <th>#</th>
                                <th><?php echo e(__('Delivery Zone name')); ?></th>
                                <th><?php echo e(__('Contact')); ?></th>
                                <th><?php echo e(__('Admin Name')); ?></th>
                                <th><?php echo e(__('Email')); ?></th>
                                <th><?php echo e(__('Enable')); ?></th>
                                <?php if(Gate::check('delivery_zone_access')): ?>
                                <th><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $deliveryZones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryZone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input name="id[]" value="<?php echo e($deliveryZone->id); ?>" id="<?php echo e($deliveryZone->id); ?>" data-id="<?php echo e($deliveryZone->id); ?>" class="sub_chk" type="checkbox" />
                                    <label for="<?php echo e($deliveryZone->id); ?>"></label>
                                </td>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($deliveryZone->name); ?></td>
                                <td><?php echo e($deliveryZone->contact); ?></td>
                                <td><?php echo e($deliveryZone->admin_name); ?></td>
                                <td class="text_transform_none"><?php echo e($deliveryZone->email); ?></td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status"
                                            onclick="change_status('admin/delivery_zone',<?php echo e($deliveryZone->id); ?>)"
                                            <?php echo e(($deliveryZone->status == 1) ? 'checked' : ''); ?>>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                <?php if(Gate::check('delivery_zone_access')): ?>
                                <td class="d-flex justify-content-center">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery_zone_access')): ?>
                                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                                            <a href="<?php echo e(url('admin/delivery_zone_area/'.$deliveryZone->id)); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('View Delivery zone')); ?>"><i class="fas fa-eye"></i></a>
                                        <?php endif; ?>
                                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                                            <a href="<?php echo e(url('vendor/deliveryZoneArea/'.$deliveryZone->id)); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('View Delivery zone')); ?>"><i class="fas fa-eye"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery_zone_edit')): ?>
                                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                                            <a href="<?php echo e(url('admin/delivery_zone/'.$deliveryZone->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Edit')); ?>"><i class="fas fa-pencil-alt"></i></a>
                                        <?php endif; ?>
                                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                                            <a href="<?php echo e(url('vendor/deliveryZone/'.$deliveryZone->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Edit')); ?>"><i class="fas fa-pencil-alt"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery_zone_delete')): ?>
                                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/delivery_zone',<?php echo e($deliveryZone->id); ?>,'Delivery Zone')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('vendor/deliveryZone',<?php echo e($deliveryZone->id); ?>,'Delivery Zone')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('delivery_zone_multi_delete','Delivery Zone')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'delivery_zone'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/delivery zone/delivery_zone.blade.php ENDPATH**/ ?>