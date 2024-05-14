@extends('layouts.app', ['activePage' => 'vendor'])

@section('title', 'Create A Vendor Discount')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Create new discount') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a
                        href="{{ url('admin/vendor/' . $id) }}">{{ App\Models\Vendor::find($id)->name }}</a></div>
                <div class="breadcrumb-item active"><a
                        href="{{ url('admin/vendor_discount/' . $id) }}">{{ __('vendor discount') }}</a></div>
                <div class="breadcrumb-item">{{ __('create vendor discount') }}</div>
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
            <p class="section-lead">{{ __('create Vendor discount') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/vendor_discount') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="vendor_id" value="{{ $id }}">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="Promo code name">{{ __('Discount image') }}</label>
                                <div class="logoContainer">
                                    <img id="image" src="{{ url('images/upload/impageplaceholder.png') }}"
                                        width="180" height="150">
                                </div>
                                <div class="fileContainer sprite">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" name="image" accept=".jpg , .jpeg , .png" value="Choose File"
                                        data-id="add" id="previewImage">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label for="Discount type">{{ __('Discount type') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <select name="type" class="form-control">
                                    <option value="{{ 'percentage' }}">{{ __('percentage') }}</option>
                                    <option value="{{ 'amount' }}">{{ __('amount') }}</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-5">
                                <label for="{{ __('Discount') }}">{{ __('Discount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="discount"
                                    class="form-control floatTextBox @error('discount') is_invalide @enderror"
                                    placeholder="{{ __('Discount') }}" value="{{ old('discount') }}" required="">

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
                                <input type="text" name="start_end_date" class="form-control" />
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="max_discount_amount">{{ __('Maximum Discount amount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="max_discount_amount" value="{{ old('max_discount_amount') }}"
                                    required
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
                                <textarea name="description" placeholder="{{ __('Description') }}" required class="form-control">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="min_item_amount">{{ __('Minimum Item amount') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="min_item_amount" value="{{ old('min_item_amount') }}"
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
                            <input type="submit" value="{{ __('Add Discount') }}" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
