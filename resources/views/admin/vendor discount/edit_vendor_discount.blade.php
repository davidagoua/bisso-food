@extends('layouts.app', ['activePage' => 'vendor'])

@section('title', 'Edit Vendor Discount')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('edit vendor discount') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a
                        href="{{ url('admin/vendor/' . $vendorDiscount->vendor_id) }}">{{ App\Models\Vendor::find($vendorDiscount->vendor_id)->name }}</a>
                </div>
                <div class="breadcrumb-item active"><a
                        href="{{ url('admin/vendor_discount/' . $vendorDiscount->vendor_id) }}">{{ __('vendor discount') }}</a>
                </div>
                <div class="breadcrumb-item">{{ __('edit vendor discount') }}</div>
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
            <h2 class="section-title">{{ __('Vendor discount') }}</h2>
            <p class="section-lead">{{ __('edit Vendor discount') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/vendor_discount/' . $vendorDiscount->id) }}"
                method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="Promo code name">{{ __('Discount image') }}</label>
                                <div class="logoContainer">
                                    <img id="image" src="{{ url('images/upload/' . $vendorDiscount->image) }}"
                                        width="180" height="150">
                                </div>
                                <div class="fileContainer sprite">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" data-id="edit" name="image" value="Choose File"
                                        id="previewImage" accept=".png, .jpg, .jpeg, .svg">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label for="Discount type">{{ __('Discount type') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <select name="type" class="form-control">
                                    <option value="{{ 'percentage' }}"
                                        {{ $vendorDiscount->type == 'percentage' ? 'selected' : '' }}>
                                        {{ __('percentage') }}</option>
                                    <option value="{{ 'amount' }}"
                                        {{ $vendorDiscount->type == 'amount' ? 'selected' : '' }}>{{ __('amount') }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-5">
                                <label for="{{ __('Discount') }}">{{ __('Discount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="discount"
                                    class="form-control @error('discount') is_invalide @enderror"
                                    placeholder="{{ __('Discount') }}" value="{{ $vendorDiscount->discount }}"
                                    required="">

                                @error('discount')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label
                                    for="{{ __('Discount start/end Period') }}">{{ __('Discount start/end Period') }}<span
                                        class="text-danger">&nbsp;*</span></label><br>
                                <input type="text" name="start_end_date" value="{{ $vendorDiscount->start_end_date }}"
                                    class="form-control" />
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="max_discount_amount">{{ __('Maximum Discount amount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="max_discount_amount"
                                    value="{{ $vendorDiscount->max_discount_amount }}"
                                    class="form-control floatTextBox @error('max_discount_amount') is_invalide @enderror"
                                    placeholder="{{ __('Maximum Discount Amount') }}">

                                @error('max_discount_amount')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label for="Description">{{ __('Description') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <textarea name="description" placeholder="{{ __('Description') }}" class="form-control">{{ $vendorDiscount->description }}</textarea>
                                @error('description')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="min_item_amount">{{ __('Minimum Item amount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="min_item_amount" value="{{ $vendorDiscount->min_item_amount }}"
                                    class="form-control floatTextBox @error('min_item_amount') is_invalide @enderror"
                                    placeholder="{{ __('Minimum Item Amount') }}">

                                @error('min_item_amount')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <input type="submit" value="{{ __('update Discount') }}" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
