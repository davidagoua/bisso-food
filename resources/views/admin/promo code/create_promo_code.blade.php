@extends('layouts.app', ['activePage' => 'promo_code'])

@section('title', 'Create A Promo Code')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Create new promo code') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ url('admin/promo_code') }}">{{ __('Promo code') }}</a></div>
                <div class="breadcrumb-item">{{ __('create a promo code') }}</div>
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
            <h2 class="section-title">{{ __('Promo code Management system') }}</h2>
            <p class="section-lead">{{ __('create promo code') }}</p>
            <div class="card">
                <div class="card-body">
                    <form class="container-fuild myform" action="{{ url('admin/promo_code') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <div class="logoContainer">
                                    <img id="image" src="{{ url('images/upload/impageplaceholder.png') }}"
                                        width="180" height="150">
                                </div>
                                <div class="fileContainer sprite">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" name="image" accept=".jpg,.png,.jpeg" value="Choose File"
                                        id="previewImage" dataid="add" accept=".png, .jpg, .jpeg, .svg">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label for="Promo code name">{{ __('promo code name') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is_invalide @enderror"
                                    placeholder="{{ __('Promo Code Name') }}" value="{{ old('name') }}" required="">

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
                                    placeholder="{{ __('Promo Code') }}" value="{{ old('promo_code') }}" required="">
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
                                        <option value="{{ $vendor->id }}" selected>{{ $vendor->name }}</option>
                                    @endforeach
                                </select>

                                @error('vendor_id')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-5">
                                <label for="vendor">{{ __('Promo code valid for this Customer') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <select name="customer_id[]" class="select2 form-control" multiple>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-5 mt-3">
                                <input type="checkbox" id="customer_app" name="display_customer_app">
                                <label for="customer_app">{{ __('Show in customer App') }}</label>
                            </div>

                            <div class="col-md-3 mb-5 mt-3">
                                <input type="checkbox" id="chkbox" name="isFlat">
                                <label for="chkbox">{{ __('Flat Discount') }}</label>
                            </div>

                            <div class="col-md-3 mb-5 discountType">
                                <label for="{{ __('Discount type') }}">{{ __('Discount type') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <select name="discountType" id="Discount type" class="form-control">
                                    <option value="percentage">{{ __('percentage') }}</option>
                                    <option value="amount">{{ __('amount') }}</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-5 max_disc_amount">
                                <label for="vendor">{{ __('Enter Discount') }}</label>
                                <input type="text" name="discount" class="form-control floatTextBox"
                                    placeholder="{{ __('Enter Discount') }}" value="{{ old('discount') }}">
                            </div>

                            <div class="col-md-6 mb-5 hide flatDiscount">
                                <label for="{{ __('Flat Discount') }}">{{ __('Flat Discount') }}</label>
                                <input type="text" name="flatDiscount"
                                    class="form-control floatTextBox @error('flatDiscount') is_invalide @enderror"
                                    id="flatDiscount" placeholder="{{ __('Enter Flat Discount In Amount') }}"
                                    value="{{ old('flatDiscount') }}">
                            </div>

                            @error('flatDiscount')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="{{ __('Maximum Discount amount') }}">{{ __('Maximum Discount Amount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="max_disc_amount"
                                    class="form-control @error('max_disc_amount') is_invalide @enderror floatTextBox"
                                    id="Maximum Discount amount" placeholder="{{ __('Maximum Discount Amount') }}"
                                    value="{{ old('max_disc_amount') }}">
                            </div>

                            @error('max_disc_amount')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label
                                    for="{{ __('Promo Code redeem start/end Period') }}">{{ __('Promo Code Redeem Start/End Period') }}<span
                                        class="text-danger">&nbsp;*</span></label><br>
                                <input type="text" name="start_end_date" class="form-control" />
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="{{ __('max_count') }}">{{ __('Promo Code Redeem Count') }}</label>
                                <select class="form-control" name="max_count">
                                    <option value="1">{{ __('1') }}</option>
                                    <option value="2">{{ __('2') }}</option>
                                    <option value="3">{{ __('3') }}</option>
                                    <option value="4">{{ __('4') }}</option>
                                    <option value="5">{{ __('5') }}</option>
                                    <option value="6">{{ __('6') }}</option>
                                    <option value="7">{{ __('7') }}</option>
                                    <option value="8">{{ __('8') }}</option>
                                    <option value="9">{{ __('9') }}</option>
                                    <option value="10">{{ __('10') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label for="{{ __('min_order_amount') }}">{{ __('Minimum Order Amount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="min_order_amount" value="{{ old('min_order_amount') }}"
                                    class="form-control floatTextBox @error('min_order_amount') is_invalide @enderror"
                                    placeholder="{{ __('Minimum Order Amount') }}">
                                @error('min_order_amount')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="{{ __('max_user') }}">{{ __('Coupon Valid For First X User') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="number" name="max_user" value="{{ old('max_user') }}"
                                    class="form-control floatTextBox @error('max_user') is_invalide @enderror"
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
                                    <option value="1">{{ __('1') }}</option>
                                    <option value="2">{{ __('2') }}</option>
                                    <option value="3">{{ __('3') }}</option>
                                    <option value="4">{{ __('4') }}</option>
                                    <option value="5">{{ __('5') }}</option>
                                </select>
                                @error('max_order')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-5">
                                <label for="{{ __('coupen_type') }}">{{ __('Coupon Type') }}<span
                                        class="text-danger">&nbsp;*</span></label>

                                <select class="form-control" name="coupen_type">
                                    <option value="both">{{ __('both') }}</option>
                                    <option value="delivery">{{ __('delivery') }}</option>
                                    <option value="pickup">{{ __('pickup') }}</option>
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
                                <label for="{{ __('max_user') }}">{{ __('Promo Code description') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <textarea name="description" class="form-control" placeholder="{{ __('Promo Code Description') }}">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="{{ __('max_user') }}">{{ __('Promo code display text') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <textarea name="display_text" class="form-control" placeholder="{{ __('Promo Code Display Text') }}">{{ old('display_text') }}</textarea>
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

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary"
                                type="submit">{{ __('Add Promo code') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
