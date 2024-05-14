@extends('layouts.app', ['activePage' => 'admin_setting'])

@section('title', 'Admin Profile')

@section('content')
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif

    @if (Session::has('message'))
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {{ Session::get('message') }}
            </div>
        </div>
    @endif

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Admin profile') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Admin profile') }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('Admin profile') }}</h2>
            <p class="section-lead">{{ __('Admin profile') }}</p>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="myform" method="post" action="{{ url('admin/update_admin_profile') }}" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    <h6 class="heading-small text-muted mb-4">{{ __('Admin Information') }}</h6>
                    <input type="hidden" name="id" value="{{ $admin->id }}">
                    <div class="text-center">
                        <img src="{{ $admin->image }}" id="update_image" width="180" height="150" />
                    </div>
                    <div class="form-group">
                        <div class="file-upload p-2">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image"
                                    accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                                <label class="custom-file-label" for="customFileLang">{{ __('Select file') }}</label>
                            </div>
                        </div>
                        @error('image')
                            <span class="custom_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                            <input type="text" name="name" id="input-name" class="form-control"
                                placeholder="{{ __('Name') }}" value="{{ $admin->name }}" required=""
                                autofocus="">

                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                            <input type="email" name="email" id="input-email" class="form-control"
                                placeholder="{{ __('Email') }}" value="{{ $admin->email_id }}" readonly>
                        </div>

                        <div class="text-center">
                            <input type="submit" value="{{ __('Save') }}" class="btn btn-primary">
                        </div>
                    </div>
                </form>
                <hr class="my-4">

                <form class="myform" method="post" action="{{ url('admin/update_password') }}">
                    @csrf

                    <input type="hidden" name="id" value="">

                    <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>

                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label class="form-control-label"
                                for="input-current-password">{{ __('Current Password') }}</label>
                            <input type="password" name="old_password" id="input-current-password" class="form-control"
                                placeholder="{{ __('Current Password') }}" required="">

                            @error('old_password')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                            <input type="password" name="password" id="input-password" class="form-control"
                                placeholder="{{ __('New Password') }}" required="">
                            @error('password')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-control-label"
                                for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                            <input type="password" name="password_confirmation" id="input-password-confirmation"
                                class="form-control" placeholder="{{ __('Confirm New Password') }}" required="">

                            @error('password_confirmation')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-4">{{ __('Change password') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
