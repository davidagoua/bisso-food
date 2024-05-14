@extends('layouts.app', ['activePage' => 'bank_details'])

@section('title', 'vendor bank details')

@section('content')
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Bank Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Vendor bank details') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Vendor Bank Detail Record Management') }}</h2>
            <p class="section-lead">{{ __('Add and Manage the Bank Details') }}</p>
            <div class="card">
                <form class="myform" action="{{ url('vendor/add_vendor_bank_details') }}" method="post">
                    @csrf
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                <label for="Delivery zone">{{ __('Bank name') }}</label>
                                <input type="text" name="bank_name"
                                    class="form-control @error('bank_name') is_invalide @enderror"
                                    placeholder="{{ __('Bank Name') }}" value="{{ old('bank_name') }}" required=""
                                    style="text-transform: none;">

                                @error('bank_name')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="Delivery zone">{{ __('branch name') }}</label>
                                <input type="text" name="branch_name"
                                    class="form-control @error('branch_name') is_invalide @enderror"
                                    placeholder="{{ __('Branch Name') }}" value="{{ old('branch_name') }}" required=""
                                    style="text-transform: none;">

                                @error('branch_name')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="Delivery zone">{{ __('CLABE') }}</label>
                                <input type="text" name="clabe"
                                    class="form-control @error('clabe') is_invalide @enderror"
                                    placeholder="{{ __('CLABE') }}" value="{{ old('clabe') }}" required=""
                                    style="text-transform: none;">

                                @error('clabe')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="Delivery zone">{{ __('account number') }}</label>
                                <input type="number" name="account_number"
                                    class="form-control @error('account_number') is_invalide @enderror"
                                    placeholder="{{ __('Account Number') }}" value="{{ old('account_number') }}"
                                    required="" style="text-transform: none;">

                                @error('account_number')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="ifsc_code">{{ __('IFSC code') }}</label>
                                <input type="text" name="ifsc_code"
                                    class="form-control @error('ifsc_code') is_invalide @enderror"
                                    placeholder="{{ __('IFSC code') }}" value="{{ old('ifsc_code') }}" required=""
                                    style="text-transform:uppercase !important">

                                @error('ifsc_code')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="{{ __('Add Bank details') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
