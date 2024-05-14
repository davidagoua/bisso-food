<?php $__env->startSection('title', 'Create A Delivery Person'); ?>

<?php $__env->startSection('content'); ?>

    <section class="section">
        <div class="section-header">
            <h1><?php echo e(__('Create Delivery Person')); ?></h1>
            <div class="section-header-breadcrumb">
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                    <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                    <div class="breadcrumb-item"><a href="<?php echo e(url('admin/delivery_person')); ?>"><?php echo e(__('Delivery Person')); ?></a>
                    </div>
                    <div class="breadcrumb-item"><?php echo e(__('create a Delivery person')); ?></div>
                <?php endif; ?>
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                    <div class="breadcrumb-item active"><a href="<?php echo e(url('vendor/vendor_home')); ?>"><?php echo e(__('Dashboard')); ?></a>
                    </div>
                    <div class="breadcrumb-item"><a
                            href="<?php echo e(url('vendor/deliveryPerson')); ?>"><?php echo e(__('Delivery Person')); ?></a></div>
                    <div class="breadcrumb-item"><?php echo e(__('create a Delivery person')); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="section-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-primary alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($item); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
            <h2 class="section-title"><?php echo e(__('delivery person management')); ?></h2>
            <p class="section-lead"><?php echo e(__('create Delivery person')); ?></p>
            <form class="container-fuild myform" action="<?php echo e(url('admin/delivery_person')); ?>" method="post"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="card">
                    <div class="card-header">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-5">
                                    <label for="Promo code name"><?php echo e(__('Delivery Person image')); ?></label>
                                    <div class="logoContainer">
                                        <img id="image" src="<?php echo e(url('images/upload/impageplaceholder.png')); ?>"
                                            width="180" height="150">
                                    </div>
                                    <div class="fileContainer sprite">
                                        <span><?php echo e(__('Image')); ?></span>
                                        <input type="file" name="image" value="Choose File" id="previewImage"
                                            data-id="add" accept=".png, .jpg, .jpeg, .svg">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="First name"><?php echo e(__('First Name')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="first_name"
                                        class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="<?php echo e(__('First Name')); ?>" value="<?php echo e(old('first_name')); ?>"
                                        required="">

                                    <?php $__errorArgs = ['first_name'];
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

                                <div class="col-md-6 mb-5">
                                    <label for="<?php echo e(__('last name')); ?>"><?php echo e(__('Last Name')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="last_name"
                                        class="form-control <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="<?php echo e(__('Last Name')); ?>" value="<?php echo e(old('last_name')); ?>" required="">
                                    <?php $__errorArgs = ['last_name'];
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
                                <div class="col-md-6 mb-5">
                                    <label for="<?php echo e(__('Email')); ?>"><?php echo e(__('Email Address')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="email_id" value="<?php echo e(old('email_id')); ?>"
                                        class="form-control <?php $__errorArgs = ['email_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="<?php echo e(__('Email Address')); ?>">
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
                                <div class="col-md-6 mb-5">
                                    <label for="<?php echo e(__('contact')); ?>"><?php echo e(__('Contact')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <div class="row">
                                        <div class="col-md-3 p-0">
                                            <select name="phone_code" required class="form-control select2">
                                                <?php $__currentLoopData = $phone_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone_code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="+<?php echo e($phone_code->phonecode); ?>">
                                                        +<?php echo e($phone_code->phonecode); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-9 p-0">
                                            <input type="number" value="<?php echo e(old('contact')); ?>" name="contact"
                                                value="<?php echo e(old('contact')); ?>" required
                                                class="form-control  <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="<?php echo e(__('delivery_zone')); ?>"><?php echo e(__('Delivery Zone')); ?><span
                                            class="text-danger">&nbsp;*</span></label>

                                    <select class="form-control select2 <?php $__errorArgs = ['delivery_zone_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="delivery_zone_id">
                                        <?php $__currentLoopData = $delivery_zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php $__errorArgs = ['delivery_zone_id'];
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

                                <div class="col-md-6 mb-5">
                                    <label for="<?php echo e(__('full_address')); ?>"><?php echo e(__('Full Address')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <textarea name="full_address" class="form-control"><?php echo e(old('full_address')); ?></textarea>
                                    <?php $__errorArgs = ['full_address'];
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
                                <div class="col-md-6">
                                    <label for="<?php echo e(__('max_user')); ?>"><?php echo e(__('Status')); ?></label><br>
                                    <label class="switch">
                                        <input type="checkbox" name="status">
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="<?php echo e(__('max_user')); ?>"><?php echo e(__('Is online')); ?></label><br>
                                    <label class="switch">
                                        <input type="checkbox" name="is_online">
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="c-grey-900"><?php echo e(__('Vehical Information')); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="mT-30">
                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="First name"><?php echo e(__('Vahicle Type')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <select name="vehicle_type"
                                        class="form-control select2 <?php $__errorArgs = ['vehicle_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__currentLoopData = $vehicals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehical): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value=<?php echo e($vehical->vehical_type); ?>><?php echo e($vehical->vehical_type); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php $__errorArgs = ['vehicle_type'];
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

                                <div class="col-md-6 mb-5">
                                    <label for="<?php echo e(__('Vahicle number')); ?>"><?php echo e(__('Vahicle number')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="vehicle_number"
                                        class="form-control <?php $__errorArgs = ['vehicle_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="<?php echo e(__('Vahicle Number')); ?>" value="<?php echo e(old('vehicle_number')); ?>"
                                        required="">

                                    <?php $__errorArgs = ['vehicle_number'];
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
                                <div class="col-md-6 mb-5">
                                    <label for="<?php echo e(__('License number')); ?>"><?php echo e(__('License number')); ?><span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="licence_number" value="<?php echo e(old('licence_number')); ?>"
                                        class="form-control <?php $__errorArgs = ['licence_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is_invalide <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="<?php echo e(__('Licence Number')); ?>">

                                    <?php $__errorArgs = ['licence_number'];
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
                                <div class="col-md-6 mb-5">
                                    <label for="Image"><?php echo e(__('national identity Document')); ?></label>
                                    <div class="logoContainer">
                                        <img id="national_identity"
                                            src="<?php echo e(url('images/upload/impageplaceholder.png')); ?>" width="180"
                                            height="150">
                                    </div>
                                    <div class="fileContainer">
                                        <span><?php echo e(__('Image')); ?></span>
                                        <input type="file" name="national_identity" value="Choose File"
                                            id="previewnational_identity" accept=".png, .jpg, .jpeg, .svg">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label for="Image"><?php echo e(__('License Document')); ?></label>
                                    <div class="logoContainer">
                                        <img id="licence_doc" src="<?php echo e(url('images/upload/impageplaceholder.png')); ?>"
                                            width="180" height="150">
                                    </div>
                                    <div class="fileContainer">
                                        <span><?php echo e(__('Image')); ?></span>
                                        <input type="file" name="licence_doc" value="Choose File"
                                            id="previewlicence_doc" accept=".png, .jpg, .jpeg, .svg">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"
                                    type="submit"><?php echo e(__('Add Delivery person')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['activePage' => 'delivery_person'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/delivery person/create_delivery_person.blade.php ENDPATH**/ ?>