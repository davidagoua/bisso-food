<?php $__env->startSection('title','Refund'); ?>

<?php $__env->startSection('content'); ?>
<section class="section">
    <?php if(Session::has('msg')): ?>
        <?php echo $__env->make('layouts.msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <div class="section-header">
        <h1><?php echo e(__('Refund')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(__('Refund')); ?></div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Refund')); ?></h2>
        <p class="section-lead"><?php echo e(__('Refund Management')); ?></p>
        <div class="card">
            <div class="card-header">
                <h4><?php echo e(__('Refund')); ?></h4>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo e(__('order id')); ?></th>
                            <th><?php echo e(__('user name')); ?></th>
                            <th><?php echo e(__('user bank details')); ?></th>
                            <th><?php echo e(__('Refund reason')); ?></th>
                            <th><?php echo e(__('Refund Amount')); ?></th>
                            <th><?php echo e(__('Refund status')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($refund->order['order_id']); ?></td>
                            <td><?php echo e($refund->user['name']); ?></td>
                            <td>
                                <a href="" onclick="user_bank_details(<?php echo e($refund->user_id); ?>)" data-toggle="modal" data-target="#view_order"><?php echo e(__('User bank Details')); ?></a>
                            </td>
                            <td><?php echo e($refund->refund_reason); ?></td>
                            <td><?php echo e($currency); ?><?php echo e($refund->order['amount']); ?></td>
                            <td>
                                <select name="refundStatus" onchange="refundStatus(<?php echo e($refund->id); ?>)" id=<?php echo e($refund->id); ?> class="form-control" <?php echo e($refund->refund_status != 'PENDING' ? 'disabled' : ''); ?>>
                                    <option value="<?php echo e('PENDING'); ?>" <?php echo e($refund->refund_status == 'PENDING' ? 'selected' : ''); ?>><?php echo e(__('Pending')); ?></option>
                                    <option value="<?php echo e('ACCEPT'); ?>" <?php echo e($refund->refund_status == 'ACCEPT' ? 'selected' : ''); ?>><?php echo e(__('Accept')); ?></option>
                                    <option value="<?php echo e('CANCEL'); ?>" <?php echo e($refund->refund_status == 'CANCEL' ? 'selected' : ''); ?>><?php echo e(__('Cancel')); ?></option>
                                    <option value="<?php echo e('COMPLETE'); ?>" <?php echo e($refund->refund_status == 'COMPLETE' ? 'selected' : ''); ?>><?php echo e(__('Complete')); ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal right fade" id="view_order" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="staticBackdropLabel"><?php echo e(__('User bank Details')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th><?php echo e(__('user name')); ?></th>
                        <td class="user_name"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('Ifsc Code')); ?></th>
                        <td class="ifsc_code"></td>
                    </tr>

                    <tr>
                        <th><?php echo e(__('Account name')); ?></th>
                        <td class="account_name"></td>
                    </tr>

                    <tr>
                        <th><?php echo e(__('Account number')); ?></th>
                        <td class="account_number"></td>
                    </tr>

                    <tr>
                        <th><?php echo e(__('MICR code')); ?></th>
                        <td class="micr_code"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'refund'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/refund/refund.blade.php ENDPATH**/ ?>