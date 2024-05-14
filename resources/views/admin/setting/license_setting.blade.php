@extends('layouts.app', ['activePage' => 'setting'])

@section('title', 'License Setting')

@section('setting')
    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif
        <div class="section-header">
            <h1>{{ __('License settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ url('admin/setting') }}">{{ __('Setting') }}</a></div>
                <div class="breadcrumb-item">{{ __('License setting') }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('License Setting') }}</h2>
            <p class="section-lead">{{ __('License setting') }}</p>
            @if (session::has('error_msg'))
                <div class="alert alert-danger alert-dismissible fade show message_alert error_alert" role="alert">
                    {{ session::get('error_msg') }} <br>
                </div>
            @endif
            <form class="myform" action="{{ url('admin/update_license') }}" method="post">
                @csrf
                <div class="card p-2">
                    <div class="card-body">
                        <h5 class="mt-3">{{ __('License setting') }}</h5>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="licensecode">{{ __('License Code') }}</label>
                                <input type="text" required name="license_code" class="form-control"
                                    value="{{ $general_setting->license_code }}"
                                    {{ $general_setting->license_verify == 1 ? 'disabled' : '' }}
                                    style="text-transform: none;">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="client_name">{{ __('License client name') }}</label>
                                <input type="text" required name="client_name" class="form-control"
                                    value="{{ $general_setting->client_name }}"
                                    {{ $general_setting->license_verify == 1 ? 'disabled' : '' }}
                                    style="text-transform: none;">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12 text-center">
                                <input type="submit" value="{{ __('Update') }}"
                                    {{ $general_setting->license_verify == 1 ? 'disabled' : '' }} class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
