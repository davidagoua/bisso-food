<?php $__env->startSection('title', 'Notification Template'); ?>

<?php $__env->startSection('content'); ?>

    <section class="section">
        <?php if(Session::has('msg')): ?>
            <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <div class="section-header">
            <h1><?php echo e(__('Notification Template')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('Notification template')); ?></div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                            if (session::has('locale')) {
                                $lang = session()->get('locale');
                                if ($lang == 'spanish') {
                                    $item = App\Models\NotificationTemplate::where('title', 'book order')->first();
                                    $item->mail_content = $item->spanish_mail_content;
                                    $item->notification_content = $item->spanish_notification_content;
                                } else {
                                    $item = App\Models\NotificationTemplate::where('title', 'book order')->first();
                                }
                            } else {
                                $item = App\Models\NotificationTemplate::where('title', 'book order')->first();
                            }
                        ?>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <ul class="nav nav-pills nav-pills-rose nav-pills-icons flex-column" role="tablist">
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item">
                                                <a class="nav-link mt-1 w-100 h-100 <?php echo e($loop->iteration == 1 ? 'active show' : ''); ?>"
                                                    onclick="notificationTemplateEdit(<?php echo e($value->id); ?>)"
                                                    data-toggle="tab" href="#link110" role="tablist"><?php echo e($value->title); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="tab-content">
                                        <div class="tab-pane active show" id="link110">
                                            <form class="myform" method="post"
                                                action="<?php echo e('notification_template/' . $item->id); ?>"
                                                class="edit_notification_template_form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="row">
                                                    <div class="col">
                                                        <h4 id="heading"><?php echo e($item->title); ?></h4>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr"><?php echo e(__('Title')); ?></label>
                                                            <input type="text"
                                                                class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="title" value="<?php echo e($item->title); ?>" id="title"
                                                                style="width:100%; text-transform: none" readonly>

                                                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr"><?php echo e(__('Subject')); ?></label>
                                                            <input type="text"
                                                                class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="subject" value="<?php echo e($item->subject); ?>" id="subject"
                                                                style="width:100%; text-transform: none">

                                                            <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr"><?php echo e(__('Notification content')); ?></label>
                                                            <textarea name="notification_content" id="notification_content" name="notification_content"
                                                                class="form-control text_transform_none" cols="30" rows="10"><?php echo e($item->notification_content); ?></textarea>

                                                            <?php $__errorArgs = ['notification_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($message); ?></strong>
                                                                </span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr"><?php echo e(__('mail content')); ?></label>
                                                            <textarea name="mail_content" class="form-control textarea_editor text_transform_none" cols="10" rows="10"
                                                                id="mail_content"><?php echo e($item->mail_content); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <div class="text-center">
                                                                <input type="submit" value="<?php echo e(__('Save')); ?>"
                                                                    class="btn btn-primary">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['activePage' => 'notification_template'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/notification template/notification_template.blade.php ENDPATH**/ ?>