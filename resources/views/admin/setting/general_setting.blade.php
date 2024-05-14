@extends('layouts.app', ['activePage' => 'setting'])

@section('title', 'Genereal Setting')

@section('content')

    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif

        <div class="section-header">
            <h1>{{ __('General Settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ url('admin/setting') }}">{{ __('Setting') }}</a></div>
                <div class="breadcrumb-item">{{ __('general setting') }}</div>
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
            <h2 class="section-title">{{ __('general setting') }}</h2>
            <p class="section-lead">{{ __('Customise your General Settings') }}</p>
            <div class="card p-2">
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab">
                            <form class="myform" action="{{ url('admin/update_general_setting') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <h5 class="mt-3">{{ __('General setting') }}</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6 mb-5">
                                        <label for="Image">{{ __('Company black logo') }}</label>
                                        <div class="logoContainer">
                                            <img id="licence_doc" src="{{ $general_setting->blacklogo }}" width="180"
                                                height="150">
                                        </div>
                                        <div class="fileContainer">
                                            <span>{{ __('Image') }}</span>
                                            <input type="file" name="company_black_logo" value="Choose File"
                                                id="previewlicence_doc" data-id="edit" accept=".png, .jpg, .jpeg, .svg">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Image">{{ __('Company Favicon') }}</label>
                                        <div class="logoContainer">
                                            <img id="imgFavicon"
                                                src="{{ url('images/upload/' . $general_setting->favicon) }}"
                                                width="180" height="150">
                                        </div>
                                        <div class="fileContainer">
                                            <span>{{ __('Image') }}</span>
                                            <input type="file" name="favicon" value="Choose File" data-id="edit"
                                                id="previewFaviconImg" accept=".png, .jpg, .jpeg, .svg">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="name">{{ __('business name') }}</label>
                                        <input type="text" name="business_name" class="form-control"
                                            value="{{ $general_setting->business_name }}"
                                            placeholder="{{ __('Business Name') }}">
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label for="contact">{{ __('country') }}</label>
                                        <select name="country" class="form-control select2">
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}"
                                                    {{ $general_setting->country == $country->name ? 'selected' : '' }}>
                                                    (+{{ $country->phonecode }})
                                                    &nbsp;{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-12">
                                        <label for="timezone">{{ __('timezone') }}</label>
                                        <select class="form-control select2" name="timezone">
                                            @foreach ($timezones as $timezone)
                                                <option value="{{ $timezone->TimeZone }}"
                                                    {{ $general_setting->timezone == $timezone->TimeZone ? 'selected' : '' }}>
                                                    {{ $timezone->UTC_DST_offset }}&nbsp;&nbsp;{{ $timezone->TimeZone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="tax_id">{{ __('Currency') }}</label>
                                        <select class="form-control select2 @error('currency') is-invalid @enderror"
                                            data-toggle="select" title="select currency" name="currency"
                                            data-placeholder="Select A Currency" id="currency">
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->code }}"
                                                    {{ $general_setting->currency == $currency->code ? 'selected' : '' }}>
                                                    {{ $currency->country }}&nbsp;&nbsp;({{ $currency->currency }})&nbsp;&nbsp;({{ $currency->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="google key">{{ __('Google map key') }}
										<a href="https://developers.google.com/maps/documentation" title="Help"
                                                target="_blank">
                                                <i class="fa fa-info-circle"></i>
                                            </a>
										</label>
                                        <input type="text" name="map_key" class="form-control hide_value"
                                            value="{{ $general_setting->map_key }}" style="text-transform: none;">
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <label
                                            for="">{{ __('radius (How many kms of vendor is shown in the app)') }}</label>
                                        <input type="number" name="radius" class="form-control"
                                            value="{{ $general_setting->radius }}">
                                    </div>
                                </div>

                                <h5 class="mt-5">{{ __('bussiness hours time') }}</h5>
                                <hr>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="start time">{{ __('start time') }}</label>
                                        <input type="time" class="form-control"
                                            value="{{ $general_setting->start_time }}" name="start_time">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end time">{{ __('end time') }}</label>
                                        <input type="time" class="form-control"
                                            value="{{ $general_setting->end_time }}" name="end_time">
                                    </div>
                                </div>

                                <h5 class="mt-5">{{ __('bussiness Availability') }}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="max_user">{{ __('Status') }}</label><br>
                                        <label class="switch">
                                            <input type="checkbox" name="business_availability"
                                                {{ $general_setting->business_availability == 1 ? 'checked' : '' }}>
                                            <div class="slider"></div>
                                        </label>
                                    </div>
                                    <div
                                        class="col-md-6 business_avai_msg {{ $general_setting->business_availability == 1 ? 'hide' : '' }}">
                                        <label for="message">{{ __('Message') }}</label>
                                        <textarea name="message" class="form-control">{{ $general_setting->message }}</textarea>
                                    </div>
                                </div>

                                <h5 class="mt-5">{{ __('Tax') }}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="max_user">{{ __('All items price included in tax') }}</label><br>
                                        <label class="switch">
                                            <input type="checkbox" name="isItemTax"
                                                {{ $general_setting->isItemTax == 1 ? 'checked' : '' }}>
                                            <div class="slider"></div>
                                        </label>
                                    </div>
                                    <div class="col-md-6 {{ $general_setting->isItemTax == 1 ? 'hide' : '' }} txtItemTax">
                                        <label for="gstin">{{ __('GSTIN(%)') }}</label>
                                        <input type="text" name="item_tax" value="{{ $general_setting->item_tax }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <h5 class="mt-5">{{ __('Other') }}</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="max_user">{{ __('Takeaway feature') }}</label><br>
                                        <label class="switch">
                                            <input type="checkbox" name="isPickup"
                                                {{ $general_setting->isPickup == 1 ? 'checked' : '' }}>
                                            <div class="slider"></div>
                                        </label>
                                    </div>
                                </div>
                                <h5 class="mt-5">{{ __('Site color changes') }}</h5>
                                <hr>
                                <div class="form-group">
                                    <label>{{ __('Site color') }}</label>
                                    <input id="cp1" name="site_color" type="text" class="form-control"
                                        value="{{ $general_setting->site_color }}" />
                                </div>

                                <div class="mt-5 text-center">
                                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
