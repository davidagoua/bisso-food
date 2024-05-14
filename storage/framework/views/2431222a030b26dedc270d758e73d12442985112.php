<?php $__env->startSection('title', 'Language'); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(__('Language')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/language')); ?>"><?php echo e(__('Language')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('edit Language')); ?></div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title"><?php echo e(__('Language management')); ?></h2>
            <p class="section-lead"><?php echo e(__('Language')); ?></p>
            <form class="container-fuild myform" action="<?php echo e(url('admin/language/' . $language->id)); ?>" method="post"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="card p-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="language"><?php echo e(__('Language Image')); ?></label>
                                <div class="logoContainer">
                                    <img id="image" src="<?php echo e($language->image); ?>" width="180" height="150">
                                </div>
                                <div class="fileContainer sprite">
                                    <span><?php echo e(__('Image')); ?></span>
                                    <input type="file" name="image" value="Choose File" id="previewImage"
                                        data-id="edit" accept=".png, .jpg, .jpeg, .svg">

                                </div>
                                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="custom_error" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for=""><?php echo e(__('Name of Language')); ?>(<?php echo e(__("cann't edit")); ?>)<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="name"
                                    class="form-control text_transform_none <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e($language->name); ?>" required="true" readonly>
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
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for=""><?php echo e(__('Language Json File')); ?><span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="file" name="file" class="form-control" accept=".json">
                                <?php $__errorArgs = ['file'];
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
                                <label for=""><?php echo e(__('Language Direction')); ?><span
                                        class="text-danger">&nbsp;*</span></label>
                                <select name="direction" class="form-control">
                                    <option value="ltr" <?php echo e($language->direction == 'ltr' ? 'selected' : ''); ?>>
                                        <?php echo e(__('ltr')); ?></option>
                                    <option value="rtl" <?php echo e($language->direction == 'rtl' ? 'selected' : ''); ?>>
                                        <?php echo e(__('rtl')); ?></option>
                                </select>
                                <?php $__errorArgs = ['direction'];
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
                                <label for="status"><?php echo e(__('Status')); ?></label><br>
                                <label class="switch">
                                    <input type="checkbox" name="status" <?php echo e($language->status == 1 ? 'checked' : ''); ?>>
                                    <div class="slider"></div>
                                </label>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary"><?php echo e(__('edit Language')); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['activePage' => 'language'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/language/edit_language.blade.php ENDPATH**/ ?>