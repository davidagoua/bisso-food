<?php $__env->startSection('title', 'Create A Delivery Zone'); ?>

<?php $__env->startSection('content'); ?>

    <section class="section">

        <div class="section-header">
            <h1><?php echo e(__('Delivery zone')); ?></h1>
            <div class="section-header-breadcrumb">
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                    <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                    <div class="breadcrumb-item"><a href="<?php echo e(url('admin/delivery_zone')); ?>"><?php echo e(__('Delivery zone')); ?></a>
                    </div>
                    <div class="breadcrumb-item"><?php echo e(__('create a Delivery zone')); ?></div>
                <?php endif; ?>

                <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                    <div class="breadcrumb-item active"><a href="<?php echo e(url('vendor/vendor_home')); ?>"><?php echo e(__('Dashboard')); ?></a>
                    </div>
                    <div class="breadcrumb-item"><a href="<?php echo e(url('vendor/deliveryZone')); ?>"><?php echo e(__('Delivery zone')); ?></a>
                    </div>
                    <div class="breadcrumb-item"><?php echo e(__('create a Delivery zone')); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title"><?php echo e(__('Delivery Zone Management')); ?></h2>
            <p class="section-lead"><?php echo e(__('create Delivery zone')); ?></p>
            <div class="card p-3">
                <div class="card-body">
                    <form class="container-fuild myform" action="<?php echo e(url('admin/delivery_zone')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Delivery zone"><?php echo e(__('Delivery zone name')); ?><span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="name"
                                    class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="<?php echo e(__('Delivery Zone Name')); ?>" value="<?php echo e(old('name')); ?>"
                                    required="">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="custom_error" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact"><?php echo e(__('Admin email')); ?><span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="email" name="email"
                                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email"
                                    placeholder="<?php echo e(__('Email')); ?>" value="<?php echo e(old('email')); ?>" required="">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="custom_error" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admin_name"><?php echo e(__('admin name')); ?><span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="admin_name" id="admin_name" value="<?php echo e(old('admin_name')); ?>"
                                    placeholder="<?php echo e(__('Admin Name')); ?>" required="">

                                <?php $__errorArgs = ['admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="custom_error" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact"><?php echo e(__('contact')); ?><span class="text-danger">&nbsp;*</span></label>
                                <input type="number" min=1 name="contact"
                                    class="form-control <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="contact"value="<?php echo e(old('contact')); ?>" placeholder="<?php echo e(__('Contact')); ?>"
                                    required="">

                                <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="custom_error" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-primary" type="submit"><?php echo e(__('Add delivery zone')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['activePage' => 'delivery_zone'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/delivery zone/create_delivery_zone.blade.php ENDPATH**/ ?>