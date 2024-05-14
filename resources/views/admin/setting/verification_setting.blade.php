@extends('layouts.app', ['activePage' => 'setting'])

@section('title', 'User Verification Setting')

@section('content')
    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif

        <div class="section-header">
            <h1>{{ __('User Verification settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ url('admin/setting') }}">{{ __('Setting') }}</a></div>
                <div class="breadcrumb-item">{{ __('User / Vendor verification setting') }}</div>
            </div>
        </div>
        <div class="section-body">
            <div class="alert alert-primary alert-dismissible show fade setting_alert" style="display: none;">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ __('select at least one sms or phone') }}
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        @foreach ($errors->all() as $item)
                            {{ $item }}
                        @endforeach
                    </div>
                </div>
            @endif
            <h2 class="section-title">{{ __('verification related setting') }}</h2>
            <p class="section-lead">{{ __('verification related setting') }}</p>
            <form class="myform" action="{{ url('admin/update_verification_seting') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('verification setting') }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="{{ __('verification') }}">{{ __('User and Vendor Verification') }}</label><br>
                                <label class="switch">
                                    <input type="checkbox" name="verification"
                                        {{ $general_setting->verification == 1 ? 'checked' : '' }}>
                                    <div class="slider"></div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label
                                    for="{{ __('verification_email') }}">{{ __('Verification via email ?') }}</label><br>
                                <label class="switch">
                                    <input type="checkbox" name="verification_email"
                                        {{ $general_setting->verification_email == '1' ? 'checked' : '' }}>
                                    <div class="slider"></div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label
                                    for="{{ __('verification_phone') }}">{{ __('Verification via phone ?') }}</label><br>
                                <label class="switch">
                                    <input type="checkbox" name="verification_phone"
                                        {{ $general_setting->verification_phone == '1' ? 'checked' : '' }}>
                                    <div class="slider"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Twilio Account setting') }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="twilio_acc_id">{{ __('Twilio account Id') }}</label><br>
                                <input type="text" value="{{ $general_setting->twilio_acc_id }}" name="twilio_acc_id"
                                    id="twilio_acc_id" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="verification">{{ __('Twilio auth Token') }}</label><br>
                                <input type="text" value="{{ $general_setting->twilio_auth_token }}"
                                    name="twilio_auth_token" id="twilio_auth_token" class="form-control">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="verification">{{ __('Twilio phone number') }}</label><br>
                                <input type="text" value="{{ $general_setting->twilio_phone_no }}"
                                    name="twilio_phone_no" id="twilio_phone_no" class="form-control">
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-toggle="modal" data-target="#testSmsModal">{{__('Test')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="testSmsModal" tabindex="-1" role="dialog"
        aria-labelledby="testSmsModalTitle" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ url('admin/check-sms') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Test SMS') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="modal-body">
                        <label class="col-form-label">{{ __('Recipient Phone Number for SMS Testing') }}</label>
                        <input type="number" name="sms_to" id="sms_to" value="" required
                            class="form-control @error('sms_to') is-invalid @enderror" placeholder="+44XXXX">
                        @error('sms_to')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="mt-2">
                            <label class="col-form-label">{{ __('Message') }}</label>
                            <input type="text" name="message" id="message" value="" required
                                class="form-control @error('message') is-invalid @enderror">
                            @error('message')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
