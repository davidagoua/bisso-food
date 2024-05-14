<?php $__env->startSection('title','promo code'); ?>

<?php $__env->startSection('content'); ?>

<section class="section">
    <?php if(Session::has('msg')): ?>
        <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <div class="section-header">
        <h1><?php echo e(__('Promo code')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(__('Promo Code')); ?></div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Promo code Management system')); ?></h2>
        <p class="section-lead"><?php echo e(__('Assign the best deals and promo codes for the Users.')); ?></p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('promo_code_add')): ?>
                        <a href="<?php echo e(url('admin/promo_code/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add New')); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        <input name="select_all" value="1" id="master" type="checkbox" />
                                        <label for="master"></label>
                                    </th>
                                    <th>#</th>
                                    <th><?php echo e(__('Promo code image')); ?></th>
                                    <th><?php echo e(__('Promo code name')); ?></th>
                                    <th><?php echo e(__('Promo code')); ?></th>
                                    <th><?php echo e(__('Start end period  (MM_DD_YYYY)')); ?></th>
                                    <th><?php echo e(__('Enable')); ?></th>
                                    <?php if(Gate::check('promo_code_edit') && Gate::check('promo_code_delete')): ?>
                                        <th><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $PromoCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $PromoCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input name="id[]" value="<?php echo e($PromoCode->id); ?>" id="<?php echo e($PromoCode->id); ?>" data-id="<?php echo e($PromoCode->id); ?>" class="sub_chk" type="checkbox" />
                                        <label for="<?php echo e($PromoCode->id); ?>"></label>
                                    </td>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <img src="<?php echo e($PromoCode->image); ?>" width="50" height="50" class="rounded" alt="">
                                    </td>
                                    <td><?php echo e($PromoCode->name); ?></td>
                                    <td><?php echo e($PromoCode->promo_code); ?></td>
                                    <td><?php echo e($PromoCode->start_end_date); ?></td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" name="status" onclick="change_status('admin/promo_code',<?php echo e($PromoCode->id); ?>)" <?php echo e(($PromoCode->status == 1) ? 'checked' : ''); ?>>
                                            <div class="slider"></div>
                                        </label>
                                    </td>
                                    <?php if(Gate::check('promo_code_edit') && Gate::check('promo_code_delete')): ?>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('promo_code_edit')): ?>
                                                <a href="<?php echo e(url('admin/promo_code/'.$PromoCode->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('promo_code_delete')): ?>
                                                <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/promo_code',<?php echo e($PromoCode->id); ?>,'Promo Code')">
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
                </div>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('promo_code_multi_delete','Promo Code')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'promo_code'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/promo code/promo_code.blade.php ENDPATH**/ ?>