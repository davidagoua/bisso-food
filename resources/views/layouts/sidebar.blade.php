<div class="main-sidebar" style="overflow: auto;">
    <aside id="sidebar-wrapper  p-3">
        <div class="sidebar-brand">
            @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    @php
                        $vendor = App\Models\Vendor::where('user_id',auth()->user()->id)->first();
                    @endphp
                @endif
                <a href="{{ url('vendor/vendor_home')}}">
                    <img src="{{ $vendor->vendor_logo }}" class="rounded" width="150" height="150" alt="">
                </a>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="{{ url('vendor/vendor_home') }}">
                        <img src="{{ $vendor->vendor_logo }}" class="rounded" width="20" height="20" alt="">
                    </a>
                </div>
            @endif

            @php
                $icon = App\Models\GeneralSetting::find(1)->company_black_logo;
            @endphp

            @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                {{-- <div class="sidebar-brand"> --}}
                    <a href="{{ url('admin/home')}}">
                        <img src="{{ url('images/upload/'.$icon)}}" width="150" height="150">
                    </a>
                {{-- </div> --}}
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="{{ url('admin/home')}}">
                        <img src="{{ url('images/upload/'.$icon)}}" width="20" height="20">
                    </a>
                </div>
            @endif
        </div>

        <ul class="sidebar-menu">
            @can('admin_dashboard')
                <li class="{{ $activePage == 'home' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/home') }}">
                        <i class="fas fa-columns text-primary"></i>
                        <span>{{__('Dashboard')}}</span>
                    </a>
                </li>
            @endcan

            @can('vendor_dashboard')
                <li class="{{ $activePage == 'home' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('vendor/vendor_home') }}">
                        <i class="fas fa-columns text-warning"></i>
                        <span>{{__('Dashboard')}}</span>
                    </a>
                </li>
            @endcan

            @can('cuisine_access')
                <li class="{{ $activePage == 'cuisine' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/cuisine') }}">
                        <i class="fas fa-utensils text-danger"></i>
                        <span class="nav-link-text">{{__('cuisine')}}</span>
                    </a>
                </li>
            @endcan

            @can('admin_vendor_access')
            <li class="{{ $activePage == 'vendor' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/vendor') }}">
                    <i class="fas fa-user-secret text-info"></i>
                    <span class="nav-link-text">{{__('vendor')}}</span>
                </a>
            </li>
            @endcan

            @can('delivery_zone_access')
                @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <li class="{{ $activePage == 'delivery_zone' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/delivery_zone') }}">
                            <i class="fas fa-users text-success"></i><span>{{__('Delivery zone')}}</span>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    @if (Session::get('vendor_driver') == 1)
                    <li class="{{ $activePage == 'delivery_zone' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('vendor/deliveryZone') }}">
                            <i class="fas fa-users text-success"></i><span>{{__('Delivery zone')}}</span>
                        </a>
                    </li>
                    @endif
                @endif
            @endcan

            @can('order_access')
            <li class="{{ $activePage == 'order' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/order') }}">
                    <i class="fas fa-sort text-dark"></i>
                    <span>{{__('Order')}}</span>
                </a>
            </li>
            @endcan

            @can('delivery_person_access')
                @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <li class="{{ $activePage == 'delivery_person' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/delivery_person') }}">
                            <i class="fab fa-red-river text-danger"></i>
                            <span class="nav-link-text">{{__('Delivery person')}}</span>
                        </a>
                    </li>
                @endif
                @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    @if (Session::get('vendor_driver') == 1)
                        <li class="{{ $activePage == 'delivery_person' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('vendor/deliveryPerson') }}">
                                <i class="fab fa-red-river text-danger"></i>
                                <span class="nav-link-text">{{__('Delivery person')}}</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endcan

            @can('promo_code_access')
            <li class="{{ $activePage == 'promo_code' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/promo_code') }}">
                    <i class="fas fa-tags text-info"></i>
                    <span class="nav-link-text">{{__('Promo code')}}</span>
                </a>
            </li>
            @endcan

            @can('user_access')
            <li class="{{ $activePage == 'user' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/user') }}">
                    <i class="fas fa-users text-dark"></i>
                    <span class="nav-link-text">{{__('user')}}</span>
                </a>
            </li>
            @endcan

            @can('faq_access')
            <li class="{{ $activePage == 'faq' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/faq') }}">
                    <i class="far fa-address-card text-primary"></i>
                    <span class="nav-link-text">{{__('FAQ')}}</span>
                </a>
            </li>
            @endcan
            @can('admin_reports')
                <li class="dropdown {{ $activePage == 'notification_template' ? 'active' : ''}} || {{ $activePage == 'send_notification' ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-address-card text-danger"></i>
                        <span>{{__('Notifications')}}</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="{{ $activePage == 'notification_template' ? 'active' : ''}}"><a href="{{ url('admin/notification_template') }}">{{__('Notification Template')}}</a></li>
                        <li class="{{ $activePage == 'send_notification' ? 'active' : ''}}"><a href="{{ url('admin/send_notification') }}">{{__('Send Notification')}}</a></li>
                    </ul>
                </li>
            @endcan

            @can('banner_access')
                <li class="{{ $activePage == 'banner' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/banner') }}">
                    <i class="fab fa-artstation text-info"></i>
                    <span class="nav-link-text">{{__('Banner Management')}}</span>
                    </a>
                </li>
            @endcan

            @can('language_access')
                <li class="{{ $activePage == 'language' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/language') }}">
                        <i class="fas fa-language text-success"></i>
                        <span class="nav-link-text">{{__('Language')}}</span>
                    </a>
                </li>
            @endcan

            @can('tax_access')
                <li class="{{ $activePage == 'tax' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/tax') }}">
                        <i class="fas fa-comments-dollar text-danger"></i>
                        <span>{{__('Tax')}}</span>
                    </a>
                </li>
            @endcan

            @can('role_access')
                <li class="{{ $activePage == 'role' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/roles') }}">
                        <i class="fas fa-adjust"></i>
                        <span class="nav-link-text">{{__('role and permissions')}}</span>
                    </a>
                </li>
            @endcan

            @can('feedback_support')
            <li class="{{ $activePage == 'feedback' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/feedback') }}">
                    <i class="fas fa-comment text-dark"></i>
                    <span class="nav-link-text">{{__('Feedback and support')}}</span>
                </a>
            </li>
            @endcan

            @can('admin_reports')
                <li class="dropdown {{ $activePage == 'user_report' ? 'active' : ''}} || {{ $activePage == 'order_report' ? 'active' : ''}} || {{ $activePage == 'vendor_report' ? 'active' : ''}} || {{ $activePage == 'driver_report' ? 'active' : ''}} || {{ $activePage == 'earning_report' ? 'active' : ''}} || {{ $activePage == 'wallet_transaction_report' ? 'active' : ''}} || {{ $activePage == 'deposit_report' ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-file-alt text-warning"></i>
                        <span>{{__('Reports')}}</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="{{ $activePage == 'user_report' ? 'active' : ''}}"><a href="{{ url('admin/user_report') }}">{{__('User reports')}}</a></li>
                        <li class="{{ $activePage == 'order_report' ? 'active' : ''}}"><a href="{{ url('admin/order_report') }}">{{__('Order reports')}}</a></li>
                        <li class="{{ $activePage == 'wallet_transaction_report' ? 'active' : ''}}"><a href="{{ url('admin/wallet_withdraw_report') }}">{{__('Wallet withdraw reports')}}</a></li>
                        <li class="{{ $activePage == 'deposit_report' ? 'active' : ''}}"><a href="{{ url('admin/wallet_deposit_report') }}">{{__('Wallet Deposit reports')}}</a></li>
                        <li class="{{ $activePage == 'vendor_report' ? 'active' : ''}}"><a href="{{ url('admin/vendor_report') }}">{{__('Vendor reports')}}</a></li>
                        <li class="{{ $activePage == 'driver_report' ? 'active' : ''}}"><a href="{{ url('admin/driver_report') }}">{{__('Delivery persons reports')}}</a></li>
                    </ul>
                </li>
            @endcan

            @can('refund_access')
            <li class="{{ $activePage == 'refund' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/refund') }}">
                    <i class="fas fa-shekel-sign text-danger"></i>
                    <span>{{__('Refund')}}</span>
                </a>
            </li>
            @endcan

            @can('admin_setting')
            <li class="{{ $activePage == 'setting' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/setting') }}">
                    <i class="fas fa-cog text-success"></i>
                    <span class="nav-link-text">{{__('setting')}}</span>
                </a>
            </li>
            @endcan

            @can('vendor_order_access')
            <li class="{{ $activePage == 'order' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('vendor/Orders') }}">
                    <i class="fas fa-sort text-info"></i>
                    <span>{{__('Order')}}</span>
                </a>
            </li>
            @endcan

            @can('vendor_menu_access')
            <li class="{{ $activePage == 'vendor_menu' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('vendor/vendor_menu/') }}">
                    <i class="fas fa-bars  "></i>
                    <span>{{__('Menu Category')}}</span>
                </a>
            </li>
            @endcan

            @can('vendor_reviews')
            <li class="{{ $activePage == 'rattings' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('vendor/rattings') }}">
                    <i class="fas fa-star text-danger"></i>
                    <span>{{__('Reviews and ratings')}}</span>
                </a>
            </li>
            @endcan

            @can('vendor_discount_access')
            <li class="{{ $activePage == 'vendor_discount' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('vendor/vendor_discount') }}">
                    <i class="fas fa-tags text-dark"></i>
                    <span>{{__('Vendor discount')}}</span>
                </a>
            </li>
            @endcan

            @can('vendor_financeDetails')
            <li class="{{ $activePage == 'finance_details' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('vendor/vendor/vendor_finance_details') }}">
                    <i class="fas fa-wallet text-info"></i>
                    <span>{{__('Finance Details')}}</span>
                </a>
            </li>
            @endcan

            @can('vendor_reports')
                <li class="dropdown {{ $activePage == 'user_report' ? 'active' : ''}} || {{ $activePage == 'order_report' ? 'active' : ''}} || {{ $activePage == 'vendor_report' ? 'active' : ''}} || {{ $activePage == 'driver_report' ? 'active' : ''}} || {{ $activePage == 'earning_report' ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-file-alt"></i>
                        <span>{{__('Reports')}}</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="{{ $activePage == 'user_report' ? 'active' : ''}}"><a href="{{ url('vendor/user_report') }}">{{__('User reports')}}</a></li>
                        <li class="{{ $activePage == 'order_report' ? 'active' : ''}}"><a href="{{ url('vendor/order_report') }}">{{__('Order reports')}}</a></li>
                    </ul>
                </li>
            @endcan

            @can('vendor_bank_details')
                <li class="{{ $activePage == 'bank_details' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('vendor/bank_details/') }}">
                        <i class="fas fa-money-check-alt text-warning"></i>
                        <span>{{__('Bank details')}}</span>
                    </a>
                </li>
            @endcan

            @if(Gate::check('vendor_deliveryTimeslots') || Gate::check('vendor_pickupTimeslots') || Gate::check('vendor_sellingTimeslots'))
                <li class="dropdown {{ $activePage == 'delivery_timeslot' ? 'active' : ''}} || {{ $activePage == 'pickup_timeslot' ? 'active' : ''}} || {{ $activePage == 'selling_timeslot' ? 'active' : ''}}">
                    <a href="javascript:void(0);" class="nav-link has-dropdown">
                        <i class="fas fa-ellipsis-h text-dark"></i>
                        <span>{{__('Timeslots')}}</span>
                    </a>
                    <ul class="dropdown-menu" style="display: none;">
                        <li class="{{ $activePage == 'delivery_timeslot' ? 'active' : ''}}"><a href="{{ url('vendor/vendor/delivery_timeslot') }}">{{__('delivery timeslots')}}</a></li>
                        <li class="{{ $activePage == 'pickup_timeslot' ? 'active' : ''}}"><a href="{{ url('vendor/vendor/pickup_timeslot') }}">{{__('Pick up timeslots')}}</a></li>
                        <li class="{{ $activePage == 'selling_timeslot' ? 'active' : ''}}"><a href="{{ url('vendor/vendor/selling_timeslot') }}">{{__('selling timeslots')}}</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </aside>
</div>
