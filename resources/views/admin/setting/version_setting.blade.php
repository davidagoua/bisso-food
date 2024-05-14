@extends('layouts.app', ['activePage' => 'setting'])

@section('title', 'Version Setting')

@section('content')

    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Version Setting') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ url('admin/setting') }}">{{ __('Setting') }}</a></div>
                <div class="breadcrumb-item">{{ __('Version Setting') }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <span
                    class="text-danger font-weight-bold">{{ __("* * Please don't change value of version code this settings will use for developer only") }}</span>
            </div>
            <div class="card-body">
                <form class="myform" action="{{ url('admin/update_version_setting') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group">
                                <label>{{ __('IOS User Version') }}</label>
                                <input class="form-control text_transform_none" type="text" name="ios_customer_version"
                                    value="{{ $general_setting->ios_customer_version }}">
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group ">
                                <label class="control-label">{{ __('IOS Driver Version') }}</label>
                                <input class="form-control text_transform_none" type="text" name="ios_driver_version"
                                    value="{{ $general_setting->ios_driver_version }}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group ">
                                <label class="control-label">{{ __('IOS User App URL') }}</label>
                                <input class="form-control text_transform_none" type="text" name="ios_customer_app_url"
                                    value="{{ $general_setting->ios_customer_app_url }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group ">
                                <label class="control-label">{{ __('IOS Driver App URL') }}</label>
                                <input class="form-control text_transform_none" type="text" name="ios_driver_app_url"
                                    id="ios_driver_app_url" value="{{ $general_setting->ios_driver_app_url }}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group ">
                                <label class="control-label">{{ __('Android User Version') }}</label>
                                <input class="form-control text_transform_none" type="text"
                                    name="android_customer_version"
                                    value="{{ $general_setting->android_customer_version }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group ">
                                <label class="control-label">{{ __('Android Driver Version') }}</label>
                                <input class="form-control text_transform_none" type="text" name="android_driver_version"
                                    id="android_driver_version" value="{{ $general_setting->android_driver_version }}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group ">
                                <label class="control-label">{{ __('Android User App URL') }}</label>
                                <input class="form-control text_transform_none" type="text"
                                    name="android_customer_app_url"
                                    value="{{ $general_setting->android_customer_app_url }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-6 mb40 pr3">
                            <div class="form-group ">
                                <label class="control-label">{{ __('Android Driver App URL') }}</label>
                                <input class="form-control text_transform_none" type="text" name="android_driver_app_url"
                                    value="{{ $general_setting->android_driver_app_url }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12">
                            <div class="mtb15 text-center">
                                <button type="submit" class="btn btn-primary" type="button">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
