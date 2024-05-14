@extends('layouts.app', ['activePage' => 'delivery_person'])

@section('title', 'Create A Delivery Person')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Create Delivery Person') }}</h1>
            <div class="section-header-breadcrumb">
                @if (Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ url('admin/delivery_person') }}">{{ __('Delivery Person') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('create a Delivery person') }}</div>
                @endif
                @if (Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item"><a
                            href="{{ url('vendor/deliveryPerson') }}">{{ __('Delivery Person') }}</a></div>
                    <div class="breadcrumb-item">{{ __('create a Delivery person') }}</div>
                @endif
            </div>
        </div>

        <div class="section-body">
            @if ($errors->any())
                <div class="alert alert-primary alert-dismissible show fade">
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
            <h2 class="section-title">{{ __('delivery person management') }}</h2>
            <p class="section-lead">{{ __('create Delivery person') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/delivery_person') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-5">
                                    <label for="Promo code name">{{ __('Delivery Person image') }}</label>
                                    <div class="logoContainer">
                                        <img id="image" src="{{ url('images/upload/impageplaceholder.png') }}"
                                            width="180" height="150">
                                    </div>
                                    <div class="fileContainer sprite">
                                        <span>{{ __('Image') }}</span>
                                        <input type="file" name="image" value="Choose File" id="previewImage"
                                            data-id="add" accept=".png, .jpg, .jpeg, .svg">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="First name">{{ __('First Name') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="first_name"
                                        class="form-control @error('first_name') is_invalide @enderror"
                                        placeholder="{{ __('First Name') }}" value="{{ old('first_name') }}"
                                        required="">

                                    @error('first_name')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('last name') }}">{{ __('Last Name') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="last_name"
                                        class="form-control @error('last_name') is_invalide @enderror"
                                        placeholder="{{ __('Last Name') }}" value="{{ old('last_name') }}" required="">
                                    @error('last_name')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('Email') }}">{{ __('Email Address') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="email_id" value="{{ old('email_id') }}"
                                        class="form-control @error('email_id') is_invalide @enderror"
                                        placeholder="{{ __('Email Address') }}">
                                    @error('email_id')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('contact') }}">{{ __('Contact') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <div class="row">
                                        <div class="col-md-3 p-0">
                                            <select name="phone_code" required class="form-control select2">
                                                @foreach ($phone_codes as $phone_code)
                                                    <option value="+{{ $phone_code->phonecode }}">
                                                        +{{ $phone_code->phonecode }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-9 p-0">
                                            <input type="number" value="{{ old('contact') }}" name="contact"
                                                value="{{ old('contact') }}" required
                                                class="form-control  @error('contact') is_invalide @enderror">
                                            @error('contact')
                                                <span class="custom_error" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('delivery_zone') }}">{{ __('Delivery Zone') }}<span
                                            class="text-danger">&nbsp;*</span></label>

                                    <select class="form-control select2 @error('delivery_zone_id')  @enderror"
                                        name="delivery_zone_id">
                                        @foreach ($delivery_zones as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('delivery_zone_id')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('full_address') }}">{{ __('Full Address') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <textarea name="full_address" class="form-control">{{ old('full_address') }}</textarea>
                                    @error('full_address')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="{{ __('max_user') }}">{{ __('Status') }}</label><br>
                                    <label class="switch">
                                        <input type="checkbox" name="status">
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="{{ __('max_user') }}">{{ __('Is online') }}</label><br>
                                    <label class="switch">
                                        <input type="checkbox" name="is_online">
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="c-grey-900">{{ __('Vehical Information') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mT-30">
                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="First name">{{ __('Vahicle Type') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <select name="vehicle_type"
                                        class="form-control select2 @error('vehicle_type')  @enderror">
                                        @foreach ($vehicals as $vehical)
                                            <option value={{ $vehical->vehical_type }}>{{ $vehical->vehical_type }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('vehicle_type')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('Vahicle number') }}">{{ __('Vahicle number') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="vehicle_number"
                                        class="form-control @error('vehicle_number') is_invalide @enderror"
                                        placeholder="{{ __('Vahicle Number') }}" value="{{ old('vehicle_number') }}"
                                        required="">

                                    @error('vehicle_number')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('License number') }}">{{ __('License number') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="licence_number" value="{{ old('licence_number') }}"
                                        class="form-control @error('licence_number') is_invalide @enderror"
                                        placeholder="{{ __('Licence Number') }}">

                                    @error('licence_number')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5">
                                    <label for="Image">{{ __('national identity Document') }}</label>
                                    <div class="logoContainer">
                                        <img id="national_identity"
                                            src="{{ url('images/upload/impageplaceholder.png') }}" width="180"
                                            height="150">
                                    </div>
                                    <div class="fileContainer">
                                        <span>{{ __('Image') }}</span>
                                        <input type="file" name="national_identity" value="Choose File"
                                            id="previewnational_identity" accept=".png, .jpg, .jpeg, .svg">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <label for="Image">{{ __('License Document') }}</label>
                                    <div class="logoContainer">
                                        <img id="licence_doc" src="{{ url('images/upload/impageplaceholder.png') }}"
                                            width="180" height="150">
                                    </div>
                                    <div class="fileContainer">
                                        <span>{{ __('Image') }}</span>
                                        <input type="file" name="licence_doc" value="Choose File"
                                            id="previewlicence_doc" accept=".png, .jpg, .jpeg, .svg">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"
                                    type="submit">{{ __('Add Delivery person') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
