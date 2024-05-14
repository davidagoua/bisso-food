@extends('layouts.app', ['activePage' => 'delivery_zone'])

@section('title', 'Create A Delivery Zone')

@section('content')

    <section class="section">

        <div class="section-header">
            <h1>{{ __('Delivery zone') }}</h1>
            <div class="section-header-breadcrumb">
                @if (Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ url('admin/delivery_zone') }}">{{ __('Delivery zone') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('create a Delivery zone') }}</div>
                @endif

                @if (Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item"><a href="{{ url('vendor/deliveryZone') }}">{{ __('Delivery zone') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('create a Delivery zone') }}</div>
                @endif
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('Delivery Zone Management') }}</h2>
            <p class="section-lead">{{ __('create Delivery zone') }}</p>
            <div class="card p-3">
                <div class="card-body">
                    <form class="container-fuild myform" action="{{ url('admin/delivery_zone') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Delivery zone">{{ __('Delivery zone name') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is_invalide @enderror"
                                    placeholder="{{ __('Delivery Zone Name') }}" value="{{ old('name') }}"
                                    required="">
                                @error('name')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact">{{ __('Admin email') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is_invalide @enderror" id="email"
                                    placeholder="{{ __('Email') }}" value="{{ old('email') }}" required="">
                                @error('email')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admin_name">{{ __('admin name') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" class="form-control @error('admin_name') is_invalide @enderror"
                                    name="admin_name" id="admin_name" value="{{ old('admin_name') }}"
                                    placeholder="{{ __('Admin Name') }}" required="">

                                @error('admin_name')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact">{{ __('contact') }}<span class="text-danger">&nbsp;*</span></label>
                                <input type="number" min=1 name="contact"
                                    class="form-control @error('contact') is-invalid @enderror"
                                    id="contact"value="{{ old('contact') }}" placeholder="{{ __('Contact') }}"
                                    required="">

                                @error('contact')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-primary" type="submit">{{ __('Add delivery zone') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
