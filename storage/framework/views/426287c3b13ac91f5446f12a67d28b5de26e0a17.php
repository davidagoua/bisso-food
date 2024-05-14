<div class="main-sidebar" style="overflow: auto;">
    <aside id="sidebar-wrapper  p-3">
        <div class="sidebar-brand">
            <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                    <?php
                        $vendor = App\Models\Vendor::where('user_id',auth()->user()->id)->first();
                    ?>
                <?php endif; ?>
                <a href="<?php echo e(url('vendor/vendor_home')); ?>">
                    <img src="<?php echo e($vendor->vendor_logo); ?>" class="rounded" width="150" height="150" alt="">
                </a>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="<?php echo e(url('vendor/vendor_home')); ?>">
                        <img src="<?php echo e($vendor->vendor_logo); ?>" class="rounded" width="20" height="20" alt="">
                    </a>
                </div>
            <?php endif; ?>

            <?php
                $icon = App\Models\GeneralSetting::find(1)->company_black_logo;
            ?>

            <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                
                    <a href="<?php echo e(url('admin/home')); ?>">
                        <img src="<?php echo e(url('images/upload/'.$icon)); ?>" width="150" height="150">
                    </a>
                
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="<?php echo e(url('admin/home')); ?>">
                        <img src="<?php echo e(url('images/upload/'.$icon)); ?>" width="20" height="20">
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <ul class="sidebar-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_dashboard')): ?>
                <li class="<?php echo e($activePage == 'home' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('admin/home')); ?>">
                        <i class="fas fa-columns text-primary"></i>
                        <span><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_dashboard')): ?>
                <li class="<?php echo e($activePage == 'home' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('vendor/vendor_home')); ?>">
                        <i class="fas fa-columns text-warning"></i>
                        <span><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cuisine_access')): ?>
                <li class="<?php echo e($activePage == 'cuisine' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('admin/cuisine')); ?>">
                        <i class="fas fa-utensils text-danger"></i>
                        <span class="nav-link-text"><?php echo e(__('cuisine')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_vendor_access')): ?>
            <li class="<?php echo e($activePage == 'vendor' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/vendor')); ?>">
                    <i class="fas fa-user-secret text-info"></i>
                    <span class="nav-link-text"><?php echo e(__('vendor')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery_zone_access')): ?>
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                    <li class="<?php echo e($activePage == 'delivery_zone' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(url('admin/delivery_zone')); ?>">
                            <i class="fas fa-users text-success"></i><span><?php echo e(__('Delivery zone')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                    <?php if(Session::get('vendor_driver') == 1): ?>
                    <li class="<?php echo e($activePage == 'delivery_zone' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(url('vendor/deliveryZone')); ?>">
                            <i class="fas fa-users text-success"></i><span><?php echo e(__('Delivery zone')); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order_access')): ?>
            <li class="<?php echo e($activePage == 'order' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/order')); ?>">
                    <i class="fas fa-sort text-dark"></i>
                    <span><?php echo e(__('Order')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery_person_access')): ?>
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                    <li class="<?php echo e($activePage == 'delivery_person' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(url('admin/delivery_person')); ?>">
                            <i class="fab fa-red-river text-danger"></i>
                            <span class="nav-link-text"><?php echo e(__('Delivery person')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                    <?php if(Session::get('vendor_driver') == 1): ?>
                        <li class="<?php echo e($activePage == 'delivery_person' ? 'active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(url('vendor/deliveryPerson')); ?>">
                                <i class="fab fa-red-river text-danger"></i>
                                <span class="nav-link-text"><?php echo e(__('Delivery person')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('promo_code_access')): ?>
            <li class="<?php echo e($activePage == 'promo_code' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/promo_code')); ?>">
                    <i class="fas fa-tags text-info"></i>
                    <span class="nav-link-text"><?php echo e(__('Promo code')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_access')): ?>
            <li class="<?php echo e($activePage == 'user' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/user')); ?>">
                    <i class="fas fa-users text-dark"></i>
                    <span class="nav-link-text"><?php echo e(__('user')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('faq_access')): ?>
            <li class="<?php echo e($activePage == 'faq' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/faq')); ?>">
                    <i class="far fa-address-card text-primary"></i>
                    <span class="nav-link-text"><?php echo e(__('FAQ')); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_reports')): ?>
                <li class="dropdown <?php echo e($activePage == 'notification_template' ? 'active' : ''); ?> || <?php echo e($activePage == 'send_notification' ? 'active' : ''); ?>">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-address-card text-danger"></i>
                        <span><?php echo e(__('Notifications')); ?></span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="<?php echo e($activePage == 'notification_template' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/notification_template')); ?>"><?php echo e(__('Notification Template')); ?></a></li>
                        <li class="<?php echo e($activePage == 'send_notification' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/send_notification')); ?>"><?php echo e(__('Send Notification')); ?></a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('banner_access')): ?>
                <li class="<?php echo e($activePage == 'banner' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('admin/banner')); ?>">
                    <i class="fab fa-artstation text-info"></i>
                    <span class="nav-link-text"><?php echo e(__('Banner Management')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('language_access')): ?>
                <li class="<?php echo e($activePage == 'language' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('admin/language')); ?>">
                        <i class="fas fa-language text-success"></i>
                        <span class="nav-link-text"><?php echo e(__('Language')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tax_access')): ?>
                <li class="<?php echo e($activePage == 'tax' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('admin/tax')); ?>">
                        <i class="fas fa-comments-dollar text-danger"></i>
                        <span><?php echo e(__('Tax')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role_access')): ?>
                <li class="<?php echo e($activePage == 'role' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('admin/roles')); ?>">
                        <i class="fas fa-adjust"></i>
                        <span class="nav-link-text"><?php echo e(__('role and permissions')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('feedback_support')): ?>
            <li class="<?php echo e($activePage == 'feedback' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/feedback')); ?>">
                    <i class="fas fa-comment text-dark"></i>
                    <span class="nav-link-text"><?php echo e(__('Feedback and support')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_reports')): ?>
                <li class="dropdown <?php echo e($activePage == 'user_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'order_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'vendor_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'driver_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'earning_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'wallet_transaction_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'deposit_report' ? 'active' : ''); ?>">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-file-alt text-warning"></i>
                        <span><?php echo e(__('Reports')); ?></span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="<?php echo e($activePage == 'user_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/user_report')); ?>"><?php echo e(__('User reports')); ?></a></li>
                        <li class="<?php echo e($activePage == 'order_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/order_report')); ?>"><?php echo e(__('Order reports')); ?></a></li>
                        <li class="<?php echo e($activePage == 'wallet_transaction_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/wallet_withdraw_report')); ?>"><?php echo e(__('Wallet withdraw reports')); ?></a></li>
                        <li class="<?php echo e($activePage == 'deposit_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/wallet_deposit_report')); ?>"><?php echo e(__('Wallet Deposit reports')); ?></a></li>
                        <li class="<?php echo e($activePage == 'vendor_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/vendor_report')); ?>"><?php echo e(__('Vendor reports')); ?></a></li>
                        <li class="<?php echo e($activePage == 'driver_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('admin/driver_report')); ?>"><?php echo e(__('Delivery persons reports')); ?></a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('refund_access')): ?>
            <li class="<?php echo e($activePage == 'refund' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/refund')); ?>">
                    <i class="fas fa-shekel-sign text-danger"></i>
                    <span><?php echo e(__('Refund')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_setting')): ?>
            <li class="<?php echo e($activePage == 'setting' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/setting')); ?>">
                    <i class="fas fa-cog text-success"></i>
                    <span class="nav-link-text"><?php echo e(__('setting')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_order_access')): ?>
            <li class="<?php echo e($activePage == 'order' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('vendor/Orders')); ?>">
                    <i class="fas fa-sort text-info"></i>
                    <span><?php echo e(__('Order')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_menu_access')): ?>
            <li class="<?php echo e($activePage == 'vendor_menu' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('vendor/vendor_menu/')); ?>">
                    <i class="fas fa-bars  "></i>
                    <span><?php echo e(__('Menu Category')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_reviews')): ?>
            <li class="<?php echo e($activePage == 'rattings' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('vendor/rattings')); ?>">
                    <i class="fas fa-star text-danger"></i>
                    <span><?php echo e(__('Reviews and ratings')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_discount_access')): ?>
            <li class="<?php echo e($activePage == 'vendor_discount' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('vendor/vendor_discount')); ?>">
                    <i class="fas fa-tags text-dark"></i>
                    <span><?php echo e(__('Vendor discount')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_financeDetails')): ?>
            <li class="<?php echo e($activePage == 'finance_details' ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('vendor/vendor/vendor_finance_details')); ?>">
                    <i class="fas fa-wallet text-info"></i>
                    <span><?php echo e(__('Finance Details')); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_reports')): ?>
                <li class="dropdown <?php echo e($activePage == 'user_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'order_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'vendor_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'driver_report' ? 'active' : ''); ?> || <?php echo e($activePage == 'earning_report' ? 'active' : ''); ?>">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-file-alt"></i>
                        <span><?php echo e(__('Reports')); ?></span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="<?php echo e($activePage == 'user_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('vendor/user_report')); ?>"><?php echo e(__('User reports')); ?></a></li>
                        <li class="<?php echo e($activePage == 'order_report' ? 'active' : ''); ?>"><a href="<?php echo e(url('vendor/order_report')); ?>"><?php echo e(__('Order reports')); ?></a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vendor_bank_details')): ?>
                <li class="<?php echo e($activePage == 'bank_details' ? 'active' : ''); ?>">
                    <a class="nav-link" href="<?php echo e(url('vendor/bank_details/')); ?>">
                        <i class="fas fa-money-check-alt text-warning"></i>
                        <span><?php echo e(__('Bank details')); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(Gate::check('vendor_deliveryTimeslots') || Gate::check('vendor_pickupTimeslots') || Gate::check('vendor_sellingTimeslots')): ?>
                <li class="dropdown <?php echo e($activePage == 'delivery_timeslot' ? 'active' : ''); ?> || <?php echo e($activePage == 'pickup_timeslot' ? 'active' : ''); ?> || <?php echo e($activePage == 'selling_timeslot' ? 'active' : ''); ?>">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-ellipsis-h text-dark"></i>
                        <span><?php echo e(__('Timeslots')); ?></span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="<?php echo e($activePage == 'delivery_timeslot' ? 'active' : ''); ?>"><a href="<?php echo e(url('vendor/vendor/delivery_timeslot')); ?>"><?php echo e(__('delivery timeslots')); ?></a></li>
                        <li class="<?php echo e($activePage == 'pickup_timeslot' ? 'active' : ''); ?>"><a href="<?php echo e(url('vendor/vendor/pickup_timeslot')); ?>"><?php echo e(__('Pick up timeslots')); ?></a></li>
                        <li class="<?php echo e($activePage == 'selling_timeslot' ? 'active' : ''); ?>"><a href="<?php echo e(url('vendor/vendor/selling_timeslot')); ?>"><?php echo e(__('selling timeslots')); ?></a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </aside>
</div>
<?php /**PATH /var/www/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>