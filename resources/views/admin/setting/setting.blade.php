@extends('layouts.app',['activePage' => 'setting'])

@section('title','Setting')

@section('content')

<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('Settings')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Settings')}}</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Settings and Management')}}</h2>
        <p class="section-lead">{{__('Set your panel up to date with general settings')}}</p>
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('General')}}</h4>
                        <p>{{__('General settings such as, site title, site description, address and so on.')}}</p>
                        <a href="{{ url('admin/general_setting') }}" class="card-cta">{{__('Change Setting ')}}<i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('Payment Setting')}}</h4>
                        <p>{{__('Change the payment modes for the transaction.')}}</p>
                        <a href="{{ url('admin/payment_setting') }}" class="card-cta">{{__('Change Setting')}}
                            <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('Email')}}</h4>
                        <p>{{__('Email SMTP settings, notifications and others related to email.')}}</p>
                        <a href="{{ url('admin/notification_setting') }}" class="card-cta">{{__('Change Setting')}}<i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fas fa-power-off"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('System')}}</h4>
                        <p>{{__('Andriod ane IOS version settings.')}}</p>
                        <a href="{{ url('admin/version_setting')}}" class="card-cta">{{__('Change Setting')}} <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="far fa-file-alt"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('Static Pages')}}</h4>
                        <p>{{__('Bussiness static pages like help about us privacy policy etc.')}}</p>
                        <a href="{{ url('admin/static_pages')}}" class="card-cta">{{__('Change Setting')}} <i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fas fa-sort"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('Order Related settings')}}</h4>
                        <p>{{__('Order related settings.')}}</p>
                        <a href="{{ url('admin/order_setting')}}" class="card-cta">{{__('Change Setting')}} <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fab fa-red-river"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('Delivery person setting')}}</h4>
                        <p>{{__('Bussiness static pages like help about us privacy policy etc.')}}</p>
                        <a href="{{ url('admin/delivery_person_setting')}}" class="card-cta">{{__('Change Setting')}} <i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('User / vendor Verification setting')}}</h4>
                        <p>{{__('Bussiness static pages like help about us privacy policy etc.')}}</p>
                        <a href="{{ url('admin/verification_setting')}}" class="card-cta">{{__('Change Setting')}} <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-large-icons">
                    <div class="card-icon bg-primary text-white">
                        <i class="fab fa-elementor"></i>
                    </div>
                    <div class="card-body">
                        <h4>{{__('License Code')}}</h4>
                        <p>{{__('Bussiness License name and code.')}}</p>
                        <a href="{{ url('admin/license_setting')}}" class="card-cta">{{__('Change Setting')}} <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
