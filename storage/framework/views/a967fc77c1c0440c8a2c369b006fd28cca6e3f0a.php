<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <script type="text/javascript">
        window.onload = () => {
            $(".main-sidebar").niceScroll();
        }
    </script>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(__('Admin dashboard')); ?></h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5><?php echo e(__('Daily Order Management')); ?></h5>
                        </div>
                        <div class="card-body">
                            <h3><?php echo e($today_orders); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5><?php echo e(__("Today's earning")); ?></h5>
                        </div>
                        <div class="card-body">
                            <h3><?php echo e($currency); ?><?php echo e($today_earnings); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5><?php echo e(__('Total orders')); ?></h5>
                        </div>
                        <div class="card-body">
                            <h3><?php echo e($total_orders); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5><?php echo e(__('Total earning')); ?></h5>
                        </div>
                        <div class="card-body">
                            <h3><?php echo e($currency); ?><?php echo e($total_earnings); ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-dark w-50"><?php echo e(__('Growth & progress')); ?></h4>
                            <div class="w-50 text-right">
                                <input type="text" name="filter_date_range" class="form-control">
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" id="home-tab3" data-toggle="tab" href="#home3"
                                        role="tab" aria-controls="home" aria-selected="false"><?php echo e(__('orders')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                        aria-controls="profile" aria-selected="true"><?php echo e(__('earnings')); ?></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent2">

                                <div class="tab-pane fade active show" id="home3" role="tabpanel"
                                    aria-labelledby="home-tab3">
                                    <canvas id="orderChart" class="chartjs-render-monitor"
                                        style="display: block; width: 580px; height: 250px"></canvas>
                                </div>
                                <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                                    <canvas id="earningChart" class="chartjs-render-monitor"
                                        style="display: block; width: 580px; height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo e(__('Avarage Time of restaurant')); ?></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="avarageTime" class="chartjs-render-monitor"
                                style="display: block; width: 580px; height: 250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo e(__('Top selling Items')); ?></h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo e(__('Items name')); ?></th>
                                        <th><?php echo e(__('vendor name')); ?></th>
                                        <th><?php echo e(__('Price')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $topItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th><?php echo e($loop->iteration); ?></th>
                                            <th>
                                                <a href="<?php echo e(url('admin/menu/' . $topItem->menu_id)); ?>"
                                                    class="nav-link active">
                                                    <?php echo e($topItem->itemName); ?>

                                                </a>
                                            </th>
                                            <th><?php echo e($topItem->vendor); ?></th>
                                            <th><?php echo e($currency); ?><?php echo e($topItem->price); ?></th>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><?php echo e(__('Top Selling Items')); ?></h4>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $topItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tickets-list">
                                    <a class="ticket-item">
                                        <div class="ticket-title text-muted">
                                            <h4><?php echo e($topItem->itemName); ?></h4>
                                        </div>
                                        <div class="ticket-info">
                                            <div><?php echo e($topItem->total); ?><?php echo e(' time served'); ?></div>
                                            <div class="w-100">
                                                <?php if($topItem->type == 'veg'): ?>
                                                    <img src="<?php echo e(url('images/veg.png')); ?>" class="float-right"
                                                        alt="">
                                                <?php else: ?>
                                                    <img src="<?php echo e(url('images/non-veg.png')); ?>" class="float-right"
                                                        alt="">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['activePage' => 'home'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/admin/home.blade.php ENDPATH**/ ?>