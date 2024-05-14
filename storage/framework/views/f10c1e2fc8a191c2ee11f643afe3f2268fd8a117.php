<?php $__env->startSection('title','Order'); ?>

<?php $__env->startSection('content'); ?>

<section class="section">
    <div class="section-header">
        <h1><?php echo e(__('Orders')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(url('admin/home')); ?>"><?php echo e(__('Dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(__('order')); ?></div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title"><?php echo e(__('Order page')); ?></h2>
        <p class="section-lead"><?php echo e(__('Order')); ?></p>
        <div class="card">
            <div class="card-header">
                <h4><?php echo e(__('Order')); ?></h4>
            </div>
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th><?php echo e(__('Order Id')); ?></th>
                            <th><?php echo e(__('Vendor name')); ?></th>
                            <th><?php echo e(__('User Name')); ?></th>
                            <th><?php echo e(__('Date')); ?></th>
                            <th><?php echo e(__('Time')); ?></th>
                            <th><?php echo e(__('Order Status')); ?></th>
                            <th><?php echo e(__('Payment status')); ?></th>
                            <th><?php echo e(__('Payment type')); ?></th>
                            <th><?php echo e(__('View')); ?></th>
                            <th><?php echo e(__('Invoice')); ?></th>
                            <th><?php echo e(__('Delete')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <input name="id[]" value="<?php echo e($order->id); ?>" id="<?php echo e($order->id); ?>" data-id="<?php echo e($order->id); ?>" class="sub_chk" type="checkbox" />
                                    <label for="<?php echo e($order->id); ?>"></label>
                                </td>
                                <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($order->order_id); ?></td>
                                <td><?php echo e($order['vendor']->name); ?></td>
                                <td><?php echo e($order['user']->name); ?></td>
                                <td><?php echo e($order->date); ?></td>
                                <td><?php echo e($order->time); ?></td>
                                <td>
                                    <?php if($order->order_status == 'PENDING'): ?>
                                        <span class="badge badge-pill pending"><?php echo e(__('PENDING')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'APPROVE'): ?>
                                        <span class="badge badge-pill approve"><?php echo e(__('APPROVE')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'REJECT'): ?>
                                        <span class="badge badge-pill reject"><?php echo e(__('REJECT')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'CANCEL'): ?>
                                        <span class="badge badge-pill cancel"><?php echo e(__('CANCEL')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'PICKUP'): ?>
                                        <span class="badge badge-pill pickup"><?php echo e(__('PICKUP')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'DELIVERED'): ?>
                                        <span class="badge badge-pill delivered"><?php echo e(__('DELIVERED')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'COMPLETE'): ?>
                                        <span class="badge badge-pill complete"><?php echo e(__('COMPLETE')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'PREPARE_FOR_ORDER'): ?>
                                        <span class="badge badge-pill preparre-food"><?php echo e(__('PREPARE FOR ORDER')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'READY_FOR_ORDER'): ?>
                                        <span class="badge badge-pill ready_for_food"><?php echo e(__('READY FOR ORDER')); ?></span>
                                    <?php endif; ?>

                                    <?php if($order->order_status == 'ACCEPT'): ?>
                                        <span class="badge badge-pill accept"><?php echo e(__('ACCEPT')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($order->payment_status == 1): ?>
                                        <div class="span"><?php echo e(__('payment complete')); ?></div>
                                    <?php endif; ?>

                                    <?php if($order->payment_status == 0): ?>
                                        <div class="span"><?php echo e(__('payment not complete')); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($order->payment_type); ?></td>
                                <td>
                                    <a href="<?php echo e(url('vendor/order/'.$order->id)); ?>" onclick="show_order(<?php echo e($order->id); ?>)" data-toggle="modal" data-target="#view_order"><?php echo e(__('View Order')); ?></a>
                                </td>
                                <th>
                                    <a href="<?php echo e(url('admin/order/invoice/'.$order->id)); ?>" data-toggle="tooltip" title="" data-original-title="<?php echo e(__('order invoice')); ?>"><i class="fas fa-file-invoice-dollar"></i></a>
                                </th>
                                <th>
                                    <a href="javascript:void(0);" class="table-action btn btn-danger btn-action" onclick="deleteData('admin/order',<?php echo e($order->id); ?>,'Order')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </th>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('order_multi_delete','Order')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

<div class="modal right fade" id="view_order" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="staticBackdropLabel"><?php echo e(__('View order')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th><?php echo e(__('Order Id')); ?></th>
                        <td class="show_order_id"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('User name')); ?></th>
                        <td class="show_user_name"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('date')); ?></th>
                        <td class="show_date"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('time')); ?></th>
                        <td class="show_time"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('Delivery At')); ?></th>
                        <td class="show_delivery_at"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('Discount')); ?></th>
                        <td class="show_discount"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('Total Amount')); ?></th>
                        <td class="show_total_amount"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('Admin Commission')); ?></th>
                        <td class="show_admin_commission"></td>
                    </tr>
                    <tr>
                        <th><?php echo e(__('Vendor Commission')); ?></th>
                        <td class="show_vendor_amount"></td>
                    </tr>
                </table>
                <h6><?php echo e(__('tax')); ?></h6>
                <table class="table TaxTable">
                </table>
                <h6><?php echo e(__('Items')); ?></h6>
                <table class="table show_order_table">
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app',['activePage' => 'order'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/order/order.blade.php ENDPATH**/ ?>