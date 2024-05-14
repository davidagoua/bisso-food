<?php $__env->startSection('title','FAQ'); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <?php if(Session::has('msg')): ?>
            <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <div class="section-header">
            <h1><?php echo e(__('FAQ')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
                <div class="breadcrumb-item"><?php echo e(__('FAQ')); ?></div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title"><?php echo e(__('FAQ')); ?></h2>
            <p class="section-lead"><?php echo e(__('FAQs Management')); ?></p>
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        <a href="<?php echo e(url('admin/faq/create')); ?>" class="btn btn-primary float-right"><?php echo e(__('Add New')); ?></a>
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
                                <th><?php echo e(__('Question')); ?></th>
                                <th><?php echo e(__('Answer')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <input name="id[]" value="<?php echo e($faq->id); ?>" id="<?php echo e($faq->id); ?>" data-id="<?php echo e($faq->id); ?>" class="sub_chk" type="checkbox" />
                                <label for="<?php echo e($faq->id); ?>"></label>
                            </td>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($faq->question); ?></td>
                            <td><?php echo e($faq->answer); ?></td>
                            <td>
                                <a href="<?php echo e(url('admin/faq/'.$faq->id.'/edit')); ?>" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('Edit')); ?>"><i class="fas fa-pencil-alt"></i></a>
                                <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/faq',<?php echo e($faq->id); ?>,'Faq')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <input type="button" value="Delete selected" onclick="deleteAll('faq_multi_delete','Faq')" class="btn btn-primary">
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'faq'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/faq/faq.blade.php ENDPATH**/ ?>