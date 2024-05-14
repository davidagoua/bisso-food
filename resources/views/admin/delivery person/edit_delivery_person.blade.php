@extends('layouts.app', ['activePage' => 'delivery_person'])

@section('title', 'Edit Delivery Person')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Edit delivery person') }}</h1>
            <div class="section-header-breadcrumb">
                @if (Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ url('admin/delivery_person') }}">{{ __('Delivery Person') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Edit a Delivery person') }}</div>
                @endif
                @if (Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ url('vendor/deliveryPerson') }}">{{ $delivery_person->first_name . ' - ' . $delivery_person->last_name }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Edit a Delivery person') }}</div>
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
            <p class="section-lead">{{ __('Edit delivery person') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/delivery_person/' . $delivery_person->id) }}"
                method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h6 class="c-grey-900">{{ __('Delivery Person personal information') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="Promo code name">{{ __('Delivery Person image') }}</label>
                                <div class="logoContainer">
                                    <img id="image" src="{{ $delivery_person->image }}" width="180" height="150">
                                </div>
                                <div class="fileContainer sprite">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" name="image" value="Choose File" id="previewImage"
                                        data-id="edit" accept=".png, .jpg, .jpeg, .svg">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label for="First name">{{ __('First Name') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is_invalide @enderror"
                                    placeholder="{{ __('First Name') }}" value="{{ $delivery_person->first_name }}"
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
                                    placeholder="{{ __('Last name') }}" value="{{ $delivery_person->last_name }}"
                                    required="">
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
                                <input type="text" name="email_id" value="{{ $delivery_person->email_id }}"
                                    class="form-control @error('email_id') is_invalide @enderror"
                                    placeholder="{{ __('Email Address') }}" readonly>

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
                                                <option value="+{{ $phone_code->phonecode }}"
                                                    {{ $delivery_person->phone_code == $phone_code->phonecode ? 'selected' : '' }}>
                                                    +{{ $phone_code->phonecode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-9 p-0">
                                        <input type="number" value="{{ $delivery_person->contact }}" name="contact"
                                            required class="form-control  @error('contact') is_invalide @enderror">
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

                                <select class="form-control  select2 @error('delivery_zone_id')  @enderror"
                                    name="delivery_zone_id">
                                    @foreach ($delivery_zones as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $delivery_person->delivery_zone_id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
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

                                <textarea name="full_address" class="form-control">{{ $delivery_person->full_address }}</textarea>
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
                                    <input type="checkbox" name="status"
                                        {{ $delivery_person->status == '1' ? 'checked' : '' }}>
                                    <div class="slider"></div>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label for="{{ __('max_user') }}">{{ __('Is online') }}</label><br>
                                <label class="switch">
                                    <input type="checkbox" name="is_online"
                                        {{ $delivery_person->is_online == '1' ? 'checked' : '' }}>
                                    <div class="slider"></div>
                                </label>
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
                                        <option value="car"
                                            {{ $delivery_person->vehicle_type == 'car' ? 'selected' : '' }}>
                                            {{ __('Car') }}</option>
                                        <option value="bike"
                                            {{ $delivery_person->vehicle_type == 'bike' ? 'selected' : '' }}>
                                            {{ __('bike') }}</option>
                                    </select>

                                    @error('vehicle_type')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-5">
                                    <label for="{{ __('Vehicle number') }}">{{ __('Vehicle number') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="vehicle_number"
                                        class="form-control @error('vehicle_number') is_invalide @enderror"
                                        placeholder="{{ __('vehicle Number') }}"
                                        value="{{ $delivery_person->vehicle_number }}" required="">

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
                                    <input type="text" name="licence_number"
                                        value="{{ $delivery_person->licence_number }}"
                                        class="form-control @error('licence_number') is_invalide @enderror"
                                        placeholder="{{ __('licence number') }}">

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
                                            src="{{ url('images/upload/' . $delivery_person->national_identity) }}"
                                            width="180" height="150">
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
                                        <img id="licence_doc"
                                            src="{{ url('images/upload/' . $delivery_person->licence_doc) }}"
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
                                    type="submit">{{ __('Update Delivery person') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
