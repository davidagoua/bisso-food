<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto" action="">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="javascript:void(0);" data-toggle="sidebar" class="nav-link nav-link-lg">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
    </form>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>

    <ul class="navbar-nav navbar-right">
        <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
            <?php
                $vendor = App\Models\Vendor::where('user_id',auth()->user()->id)->first();
                $toDay = App\Models\Notification::where([['user_type','vendor'],['user_id',$vendor->id]])->whereBetween('created_at', [Carbon\Carbon::now()->format('Y-m-d')." 00:00:00",  Carbon\Carbon::now()->format('Y-m-d')." 23:59:59"])->get();
                $notifications = App\Models\Notification::where([['user_type','vendor'],['user_id',$vendor->id]])->get()->take(5);
            ?>
            <a href="javascript:void(0);" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg" aria-expanded="false"><i class="far fa-bell"><span class="notification-counter"><?php echo e(count($toDay)); ?></span></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header"><?php echo e(__('Notifications')); ?></div>
                <div class="dropdown-list-content dropdown-list-icons" tabindex="2" style="overflow: hidden; outline: none;">
                    <?php if(count($notifications) > 0): ?>
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="javascript:void(0);" class="dropdown-item">
                                <div class="dropdown-item-icon bg-danger text-white">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    <?php echo e($notification->message); ?>

                                    <div class="time"><?php echo e($notification->created_at->diffForHumans()); ?></div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <h6 class="text-center"><?php echo e(__('No notifications found for today')); ?></h6>
                    <?php endif; ?>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="<?php echo e(url('vendor/notification')); ?>"><?php echo e(__('View all')); ?><i class="fas fa-chevron-right"></i></a>
                </div>
                <div id="ascrail2001" class="nicescroll-rails nicescroll-rails-vr"
                    style="width: 9px; z-index: 1000; cursor: default; position: absolute; top: 58px; left: 341px; height: 350px; opacity: 0.3; display: block;">
                    <div class="nicescroll-cursors"
                        style="position: relative; top: 0px; float: right; width: 7px; height: 306px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px;">
                    </div>
                </div>
                <div id="ascrail2001-hr" class="nicescroll-rails nicescroll-rails-hr"
                    style="height: 9px; z-index: 1000; top: 399px; left: 0px; position: absolute; cursor: default; display: none; width: 341px; opacity: 0.3;">
                    <div class="nicescroll-cursors"
                        style="position: absolute; top: 0px; height: 7px; width: 350px; background-color: rgb(66, 66, 66); border: 1px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px; left: 0px;">
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
            <?php
                $lans = App\Models\Language::where('status',1)->get();
                $icon = \App\Models\Language::where('name',session('locale'))->first();
                if($icon)
                {
                    $lang_image = $icon->image;
                }
                else
                {
                    $lang_image="/images/upload/english.png";
                }
            ?>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown dropdown-list-toggle">
                    <a href="javascript:void(0);" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                        <img src="<?php echo e(url($lang_image)); ?>" width="40px" height="20px" alt="" class="rounded-lg">
                    </a>
                    <div class="dropdown-menu dropdown-list dropdown-menu-right w-auto">
                    <div class="dropdown-list-content dropdown-list-message h-auto">
                        <?php $__currentLoopData = $lans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('admin/change_language/'.$lan->name)); ?>" class="dropdown-item">
                        <div class="dropdown-item-avatar">
                            <img alt="image" src="<?php echo e($lan->image); ?>" class="rounded-lg">
                        </div>
                        <div class="dropdown-item-desc">
                            <b><?php echo e($lan->name); ?></b>
                        </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    </div>
                </li>
            </ul>
        <?php endif; ?>

        <li class="dropdown">
            <a href="javascript:void(0);" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-sm-none d-lg-inline-block"><?php echo e(__('Hi, ')); ?>

                    <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                        <?php echo e(Auth::user()->name); ?>

                    <?php else: ?>
                        <?php echo e($vendor->name); ?>

                    <?php endif; ?>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title"><?php echo e(__('Welcome, ')); ?><?php echo e(Auth::user()->name); ?></div>
                <?php if(Auth::user()->load('roles')->roles->contains('title', 'admin')): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_profile_access')): ?>
                        <a href="<?php echo e(url('admin/admin_profile')); ?>" class="dropdown-item has-icon">
                            <i class="far fa-user"></i> <?php echo e(__('Profile Settings')); ?>

                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                    $vendor = App\Models\Vendor::where('user_id',auth()->user()->id)->first();
                ?>

                <?php if(Auth::user()->load('roles')->roles->contains('title', 'vendor')): ?>
                <a href="<?php echo e(url('vendor/update_vendor')); ?>" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> <?php echo e(__('Profile Settings')); ?>

                </a>
                <a href="<?php echo e(url('vendor/printer_setting')); ?>" class="dropdown-item has-icon">
                    <i class="fas fa-print"></i> <?php echo e(__('Printer Settings')); ?>

                </a>
                <a href="<?php echo e(url('vendor/change_password')); ?>" class="dropdown-item has-icon">
                    <i class="fas fa-key"></i> <?php echo e(__('Change password')); ?>

                </a>
                <?php endif; ?>

                <div class="dropdown-divider"></div>
                <a href="<?php echo e(route('logout')); ?>" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <?php echo e(__('Logout')); ?>

                </a>
            </div>
        </li>
    </ul>
</nav>
<?php /**PATH /var/www/resources/views/layouts/topnav.blade.php ENDPATH**/ ?>