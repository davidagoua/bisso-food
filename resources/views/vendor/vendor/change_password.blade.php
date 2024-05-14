@extends('layouts.app', ['activePage' => 'vendor'])

@section('title', 'Change Password')

@section('content')

    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Change password') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Change password') }}</div>
            </div>
        </div>

        <div class="section-body">
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
            <h2 class="section-title">{{ __('Vendor password') }}</h2>
            <p class="section-lead">{{ __('Change password') }}</p>
            <div class="card">
                <div class="card-body">
                    <form class="container-fuild myform" action="{{ url('vendor/update_pwd') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="Old_password">{{ __('old password') }}</label>
                                <input type="password" name="old_password" placeholder="* * * * * *"
                                    class="form-control @error('old_password') is-invalid @enderror">

                                @error('old_password')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="new_password">{{ __('New password') }}</label>
                                <input type="password" name="password" placeholder="* * * * * *"
                                    class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="confirm_password">{{ __('confirm new password') }}</label>
                                <input type="password" name="password_confirmation" placeholder="* * * * * *"
                                    class="form-control @error('password_confirmation') is-invalid @enderror">

                                @error('password_confirmation')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="{{ __('Update password') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
