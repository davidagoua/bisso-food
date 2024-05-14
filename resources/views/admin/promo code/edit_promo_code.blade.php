@extends('layouts.app', ['activePage' => 'promo_code'])

@section('title', 'Edit Promo Code')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Edit promo code') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ url('admin/promo_code') }}">{{ __('promo code') }}</a></div>
                <div class="breadcrumb-item">{{ __('Edit promo code') }}</div>
            </div>
        </div>
        <h2 class="section-title">{{ __('Promo code Management system') }}</h2>
        <p class="section-lead">{{ __('Edit promo code') }}</p>
        <div class="card">
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
            <div class="card-body">
                <form class="container-fuild myform" action="{{ url('admin/promo_code/' . $promoCode->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <div class="logoContainer">
                                <img id="image" src="{{ $promoCode->image }}" width="180" height="150">
                            </div>
                            <div class="fileContainer sprite">
                                <span>{{ __('Image') }}</span>
                                <input type="file" name="image" value="Choose File" id="previewImage" data-id="edit"
                                    accept=".png, .jpg, .jpeg, .svg">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label for="Promo code name">{{ __('Promo Code Name') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is_invalide @enderror"
                                placeholder="{{ __('Promo Code Name') }}" value="{{ $promoCode->name }}" required="">
                            @error('name')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-5">
                            <label for="{{ __('Promo code') }}">{{ __('Promo Code') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input type="text" name="promo_code"
                                class="form-control @error('promo_code') is_invalide @enderror" id="promo_code"
                                placeholder="{{ __('promo Code') }}" value="{{ $promoCode->promo_code }}" required="">
                            @error('promo_code')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label for="vendor">{{ __('Promo code valid for this Vendor') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <select name="vendor_id[]" class="select2 form-control" multiple>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}"
                                        {{ in_array($vendor->id, explode(',', $promoCode->vendor_id)) ? 'selected' : '' }}>
                                        {{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="vendor">{{ __('Promo code valid for this Customer') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <select name="customer_id[]" class="select2 form-control" multiple>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ in_array($user->id, explode(',', $promoCode->customer_id)) ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-5 mt-3">
                            <input type="checkbox" id="customer_app"
                                {{ $promoCode->display_customer_app == 1 ? 'checked' : '' }} name="display_customer_app">
                            <label for="customer_app">{{ __('Show in customer App') }}</label>
                        </div>

                        <div class="col-md-3 mb-5 mt-3">
                            <input type="checkbox" {{ $promoCode->isFlat == 1 ? 'checked' : '' }} id="chkbox"
                                name="isFlat">
                            <label for="chkbox">{{ __('Flat Discount') }}</label>
                        </div>

                        <div class="col-md-3 mb-5 {{ $promoCode->isFlat == 1 ? 'hide' : '' }} discountType">
                            <label for="{{ __('Discount type') }}">{{ __('Discount type') }}</label>
                            <select name="discountType" id="Discount type" class="form-control">
                                <option value="percentage"
                                    {{ $promoCode->discountType == 'percentage' ? 'selected' : '' }}>
                                    {{ __('percentage') }}</option>
                                <option value="amount" {{ $promoCode->discountType == 'amount' ? 'selected' : '' }}>
                                    {{ __('amount') }}</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-5 {{ $promoCode->isFlat == 1 ? 'hide' : '' }} max_disc_amount">
                            <label for="{{ __('Maximum Discount amount') }}">{{ __('Enter Discount') }}</label>
                            <input type="text" name="discount"
                                class="form-control floatTextBox @error('discount') is_invalide @enderror"
                                id="Maximum Discount amount" placeholder="{{ __('Enter Discount') }}"
                                value="{{ $promoCode->discount }}">
                        </div>

                        @error('discount')
                            <span class="custom_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="col-md-6 mb-5 {{ $promoCode->isFlat == 0 ? 'hide' : '' }} flatDiscount">
                            <label for="{{ __('Flat Discount') }}">{{ __('Flat Discount') }}</label>
                            <input type="text" name="flatDiscount"
                                class="form-control floatTextBox @error('flatDiscount') is_invalide @enderror"
                                id="flatDiscount" placeholder="{{ __('Enter Flat Discount In Amount') }}"
                                value="{{ $promoCode->flatDiscount }}">
                        </div>

                        @error('flatDiscount')
                            <span class="custom_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <label for="vendor">{{ __('Maximum discount amount') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input type="text" name="max_disc_amount" value="{{ $promoCode->max_disc_amount }}"
                                class="form-control floatTextBox" required
                                placeholder="{{ __('Enter Maximum Discount') }}" value="{{ old('discount') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label
                                for="{{ __('Promo Code redeem start/end Period') }}">{{ __('Promo Code redeem start/end Period') }}</label><br>
                            <input type="text" value="{{ $promoCode->start_end_date }}" id="update_start_end_date"
                                name="update_start_end_date" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-5">
                            <label for="{{ __('max_count') }}">{{ __('Promo Code redeem count') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <select class="form-control" name="max_count">
                                <option value="1" {{ $promoCode->max_count == 1 ? 'selected' : '' }}>
                                    {{ __('1') }}</option>
                                <option value="2" {{ $promoCode->max_count == 2 ? 'selected' : '' }}>
                                    {{ __('2') }}</option>
                                <option value="3" {{ $promoCode->max_count == 3 ? 'selected' : '' }}>
                                    {{ __('3') }}</option>
                                <option value="4" {{ $promoCode->max_count == 4 ? 'selected' : '' }}>
                                    {{ __('4') }}</option>
                                <option value="5" {{ $promoCode->max_count == 5 ? 'selected' : '' }}>
                                    {{ __('5') }}</option>
                                <option value="6" {{ $promoCode->max_count == 6 ? 'selected' : '' }}>
                                    {{ __('6') }}</option>
                                <option value="7" {{ $promoCode->max_count == 7 ? 'selected' : '' }}>
                                    {{ __('7') }}</option>
                                <option value="8" {{ $promoCode->max_count == 8 ? 'selected' : '' }}>
                                    {{ __('8') }}</option>
                                <option value="9" {{ $promoCode->max_count == 9 ? 'selected' : '' }}>
                                    {{ __('9') }}</option>
                                <option value="10" {{ $promoCode->max_count == 10 ? 'selected' : '' }}>
                                    {{ __('10') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label for="{{ __('min_order_amount') }}">{{ __('Minimum order amount') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input type="text" name="min_order_amount" value="{{ $promoCode->min_order_amount }}"
                                class="form-control floatTextBox @error('min_order_amount') is_invalide @enderror"
                                placeholder="{{ __('Minimum Order Amount') }}" required>
                            @error('min_order_amount')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-5">
                            <label for="{{ __('max_user') }}">{{ __('Coupon Valid For First X User') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input type="number" min=1 name="max_user" value="{{ $promoCode->max_user }}"
                                class="form-control @error('max_user') is_invalide @enderror"
                                placeholder="{{ __('Coupon Valid For First X User') }}">
                            @error('max_user')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label for="{{ __('max_order') }}">{{ __('Promo Code Valid For First X Order') }}<span
                                    class="text-danger">&nbsp;*</span></label>

                            <select class="form-control" name="max_order">
                                <option value="1" {{ $promoCode->max_order == '1' ? 'selected' : '' }}>
                                    {{ __('1') }}</option>
                                <option value="2" {{ $promoCode->max_order == '2' ? 'selected' : '' }}>
                                    {{ __('2') }}</option>
                                <option value="3" {{ $promoCode->max_order == '3' ? 'selected' : '' }}>
                                    {{ __('3') }}</option>
                                <option value="4" {{ $promoCode->max_order == '4' ? 'selected' : '' }}>
                                    {{ __('4') }}</option>
                                <option value="5" {{ $promoCode->max_order == '5' ? 'selected' : '' }}>
                                    {{ __('5') }}</option>
                            </select>
                            @error('max_order')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="{{ __('coupen_type') }}">{{ __('Coupen Type') }}<span
                                    class="text-danger">&nbsp;*</span></label>

                            <select class="form-control" name="coupen_type">
                                <option value="both" {{ $promoCode->coupen_type == 'both' ? 'selected' : '' }}>
                                    {{ __('both') }}</option>
                                <option value="delivery" {{ $promoCode->coupen_type == 'delivery' ? 'selected' : '' }}>
                                    {{ __('delivery') }}</option>
                                <option value="pickup" {{ $promoCode->coupen_type == 'pickup' ? 'selected' : '' }}>
                                    {{ __('pickup') }}</option>
                            </select>
                            @error('max_order')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label for="{{ __('max_user') }}">{{ __('Promo code description') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <textarea name="description" class="form-control" placeholder="{{ __('Promo Code Description') }}">{{ $promoCode->description }}</textarea>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label for="{{ __('max_user') }}">{{ __('Promo code display text') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <textarea name="display_text" class="form-control" placeholder="{{ __('Promo Code Display Text') }}">{{ $promoCode->display_text }}</textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary"
                            type="submit">{{ __('Update Promo code') }}</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </section>

@endsection
