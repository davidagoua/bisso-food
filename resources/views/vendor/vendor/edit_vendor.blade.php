@extends('layouts.app', ['activePage' => 'vendor'])

@section('title', 'Vendor Profile')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Vendor profile') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('vendor profile') }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('Vendor profile') }}</h2>
            <p class="section-lead">{{ __('Vendor profile') }}</p>
            <form class="container-fuild myform" action="{{ url('vendor/vendor/' . $vendor->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h6 class="c-grey-900">{{ __('Vendor') }}</h6>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Promo code name">{{ __('Vendor image') }}</label>
                                <div class="logoContainer">
                                    <img id="image" src="{{ url($vendor->image) }}" width="180" height="150"
                                        class="rounded-lg p-2">
                                </div>
                                <div class="fileContainer sprite">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" data-id="edit" name="image" value="Choose File"
                                        id="previewImage" accept=".png, .jpg, .jpeg, .svg">
                                </div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="Image">{{ __('Vendor logo') }}</label>
                                <div class="logoContainer">
                                    <img id="licence_doc" src="{{ $vendor->vendor_logo }}" width="200" height="182"
                                        class="rounded-lg p-2">
                                </div>
                                <div class="fileContainer">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" data-id="edit" name="vendor_logo" value="Choose File"
                                        id="previewlicence_doc" accept=".png, .jpg, .jpeg, .svg">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Vendor name">{{ __('Vendor Name') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is_invalide @enderror"
                                    placeholder="{{ __('Vendor Name') }}" value="{{ $vendor->name }}" required=""
                                    style="text-transform: none;">

                                @error('name')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="{{ __('Email') }}">{{ __('Email Address') }}</label>
                                <input type="text" name="email_id" value="{{ $vendor->email_id }}"
                                    class="form-control @error('email_id') is_invalide @enderror"
                                    placeholder="{{ __('Email id') }}" style="text-transform: none;" readonly>

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
                                                    {{ $user->phone_code == $phone_code->phonecode ? 'selected' : '' }}>
                                                    +{{ $phone_code->phonecode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-9 p-0">
                                        <input type="number" value="{{ $vendor->contact }}" name="contact"
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
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="c-grey-900">{{ __('Select Tags (For Restaurants it will be cuisines)') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mT-30">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="First name">{{ __('Tags') }}<span
                                            class="text-danger">&nbsp;*</span></label>

                                    <select class="select2 form-control" name="cuisine_id[]" multiple="true">
                                        @foreach ($cuisines as $cuisine)
                                            <option value="{{ $cuisine->id }}"
                                                {{ in_array($cuisine->id, explode(',', $vendor->cuisine_id)) ? 'selected' : '' }}>
                                                {{ $cuisine->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cuisine_id')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="c-grey-900">{{ __('Location') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mT-30">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="row">
                                        <div class="pac-card col-md-12 mb-3" id="pac-card">
                                            <label for="pac-input">{{ __('Location based on latitude/lontitude') }}<span
                                                    class="text-danger">&nbsp;*</span></label>
                                            <div id="pac-container">
                                                <input id="pac-input" name="map_address"
                                                    value="{{ $vendor->map_address }}" type="text"
                                                    class="form-control" placeholder="Enter a location" />
                                                @if ($vendor->lat == null && $vendor->lang == null)
                                                    <input type="hidden" name="lat" value="22.3039" id="lat">
                                                    <input type="hidden" name="lang" value="70.8022" id="lang">
                                                @else
                                                    <input type="hidden" name="lat" value="{{ $vendor->lat }}"
                                                        id="lat">
                                                    <input type="hidden" name="lang" value="{{ $vendor->lang }}"
                                                        id="lang">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="min_order_amount">{{ __('Vendor full address') }}<span
                                                    class="text-danger">&nbsp;*</span></label>
                                            <input type="text" class="form-control" name="address"
                                                value="{{ $vendor->address }}"
                                                placeholder="{{ __('Vendor full address') }}" id="location">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="map"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="">{{ __('Other Information') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mT-30">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_order_amount">{{ __('Minimum order amount') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="min_order_amount"
                                        placeholder="{{ __('Minimum order amount') }}"
                                        value="{{ $vendor->min_order_amount }}" required
                                        class="form-control floatTextBox @error('min_order_amount') invalid @enderror">

                                    @error('min_order_amount')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="for_two_person">{{ __('Cost of two person') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="for_two_person"
                                        placeholder="{{ __('Cost of two person') }}"
                                        value="{{ $vendor->for_two_person }}" required
                                        class="form-control floatTextBox @error('for_two_person') invalid @enderror">

                                    @error('min_order_amount')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="avg_delivery_time">{{ __('Avg. delivery time(in min)') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="number" name="avg_delivery_time"
                                        value="{{ $vendor->avg_delivery_time }}"
                                        placeholder="{{ __('Avg delivery time(in min)') }}" required
                                        class="form-control @error('avg_delivery_time') invalid @enderror">

                                    @error('avg_delivery_time')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="license_number">{{ __('Business License number') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" name="license_number" value="{{ $vendor->license_number }}"
                                        required placeholder="{{ __('Business License number') }}"
                                        class="form-control @error('license_number') is-invalid @enderror">

                                    @error('license_number')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vendor_type">{{ __('Vendor type') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <select name="vendor_type" class="form-control">
                                        <option value="all" {{ $vendor->vendor_type == 'all' ? 'selected' : '' }}>
                                            {{ __('All') }}</option>
                                        <option value="veg" {{ $vendor->vendor_type == 'veg' ? 'selected' : '' }}>
                                            {{ __('pure veg') }}</option>
                                        <option value="non_veg" {{ $vendor->vendor_type == 'non_veg' ? 'selected' : '' }}>
                                            {{ __('none veg') }}</option>
                                        <option value="non_applicable"
                                            {{ $vendor->vendor_type == 'non_applicable' ? 'selected' : '' }}>
                                            {{ __('non applicable') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="admin_commision_value">{{ __('Time slots') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <select name="time_slot" class="form-control">
                                        <option value="15" {{ $vendor->time_slot == '15' ? 'selected' : '' }}>
                                            {{ __('15 mins') }}</option>
                                        <option value="30" {{ $vendor->time_slot == '30' ? 'selected' : '' }}>
                                            {{ __('30 mins') }}</option>
                                        <option value="45" {{ $vendor->time_slot == '45' ? 'selected' : '' }}>
                                            {{ __('45 mins') }}</option>
                                        <option value="60" {{ $vendor->time_slot == '60' ? 'selected' : '' }}>
                                            {{ __('1 hour') }}</option>
                                        <option value="120" {{ $vendor->time_slot == '120' ? 'selected' : '' }}>
                                            {{ __('2 hour') }}</option>
                                        <option value="180" {{ $vendor->time_slot == '180' ? 'selected' : '' }}>
                                            {{ __('3 hour') }}</option>
                                        <option value="240" {{ $vendor->time_slot == '240' ? 'selected' : '' }}>
                                            {{ __('4 hour') }}</option>
                                        <option value="300" {{ $vendor->time_slot == '300' ? 'selected' : '' }}>
                                            {{ __('5 hour') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tax">{{ __('GSTIN(%)') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <input type="text" required name="tax" value="{{ $vendor->tax }}"
                                        placeholder="{{ __('Resturant tax in %') }}" class="form-control floatTextBox">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tax">{{ __('vendor language') }}</label>
                                    <select name="vendor language" class="form-control">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->name }}"
                                                {{ $language->name == $vendor->vendor_language ? 'selected' : '' }}>
                                                {{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="{{ __('status') }}">{{ __('Status') }}</label><br>
                                    <label class="switch">
                                        <input type="checkbox" name="status"
                                            {{ $vendor->status == 1 ? 'checked' : '' }}>
                                        <div class="slider"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"
                                    type="submit">{{ __('Update Vendor') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('js')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\GeneralSetting::first()->map_key }}&callback=initMap&libraries=places&v=weekly"
        defer></script>
    <script src="{{ asset('js/map.js') }}"></script>
@endsection
