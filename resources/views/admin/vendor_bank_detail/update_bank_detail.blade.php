@extends('layouts.app', ['activePage' => 'bank_details'])

@section('title', 'Vendor Bank Details')

@section('content')
    <section class="section">
        <div class="section-header">

            <h1>{{ __('Bank Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a
                        href="{{ url('admin/vendor/' . $vendor->id) }}">{{ App\Models\Vendor::find($vendor->id)->name }}</a>
                </div>
                <div class="breadcrumb-item">{{ __('Vendor bank details') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Vendor bank details') }}</h2>
            <p class="section-lead">{{ __('bank details') }}</p>
            <div class="card">
                <form class="myform" action="{{ url('admin/update_bank_details/' . $bank_details->id) }}" method="post">
                    @csrf

                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                <label for="Delivery zone">{{ __('Bank name') }}</label>
                                <input type="text" name="bank_name"
                                    class="form-control @error('bank_name') is_invalide @enderror"
                                    placeholder="{{ __('Bank Name') }}" value="{{ $bank_details->bank_name }}"
                                    required="">

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
                                    placeholder="{{ __('Branch Name') }}" value="{{ $bank_details->branch_name }}"
                                    required="">

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
                                    placeholder="{{ __('CLABE') }}" value="{{ $bank_details->clabe }}" required="">

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
                                <input type="text" name="account_number"
                                    class="form-control @error('account_number') is_invalide @enderror"
                                    placeholder="{{ __('Account Number') }}" value="{{ $bank_details->account_number }}"
                                    required="">

                                @error('account_number')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="Delivery zone">{{ __('IFSC code') }}</label>
                                <input type="text" name="ifsc_code" style="text-transform:uppercase !important"
                                    class="form-control @error('ifsc_code') is_invalide @enderror"
                                    placeholder="{{ __('IFSC code') }}" value="{{ $bank_details->ifsc_code }}"
                                    required="" style="text-transform:uppercase !important">

                                @error('ifsc_code')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="{{ __('Update Bank details') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
