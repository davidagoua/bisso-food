<?php $__env->startSection('title', 'create a user'); ?>

<?php $__env->startSection('content'); ?>

    <section class="section">
        <div class="section-header">
            <h1><?php echo e(__('Create new user')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/user')); ?>"><?php echo e(__('user')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('Create user')); ?></div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title"><?php echo e(__('User management panel')); ?></h2>
            <p class="section-lead"><?php echo e(__('Create user')); ?></p>
            <div class="card">
                <div class="card-body">
                    <form class="container-fuild myform" action="<?php echo e(url('admin/user')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name"><?php echo e(__('user name')); ?></label>
                                <input type="text" name="name"
                                    class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id=""
                                    placeholder="<?php echo e(__('user name')); ?>" value="<?php echo e(old('name')); ?>" required=""
                                    style="text-transform: none;">
                                <?php $__errorArgs = ['title'];
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
                            <div class="col-md-12 mb-3">
                                <label for="email"><?php echo e(__('email')); ?></label>
                                <input type="email" name="email_id"
                                    class="form-control <?php $__errorArgs = ['email_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"P
                                    placeholder="<?php echo e(__('email')); ?>" value="<?php echo e(old('email')); ?>" required=""
                                    style="text-transform: none;">
                                <?php $__errorArgs = ['email_id'];
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
                            <div class="col-md-12 mb-3">
                                <label for="phone"><?php echo e(__('phone')); ?></label>
                                <input type="number" name="phone"
                                    class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id=""
                                    placeholder="<?php echo e(__('phone')); ?>" value="<?php echo e(old('phone')); ?>" required=""
                                    style="text-transform: none;">
                                <?php $__errorArgs = ['phone'];
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
                            <div class="col-md-12 mb-3">
                                <label for="password"><?php echo e(__('password')); ?></label>
                                <input type="password" name="password"
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id=""
                                    placeholder="<?php echo e(__('* * * * * *')); ?>" value="<?php echo e(old('password')); ?>" required=""
                                    style="text-transform: none;">
                                <?php $__errorArgs = ['password'];
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
                            <div class="col-md-12 mb-3">
                                <label for="role"><?php echo e(__('Roles')); ?></label>
                                <select class="form-control select2" name="roles[]" id="">
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>"
                                            <?php echo e($role->title == 'vendor' ? 'disabled' : ''); ?>><?php echo e($role->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit"><?php echo e(__('Add user')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['activePage' => 'user'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/user/create_user.blade.php ENDPATH**/ ?>