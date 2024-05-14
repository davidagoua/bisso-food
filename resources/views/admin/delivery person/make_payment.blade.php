@extends('layouts.app', ['activePage' => 'delivery person'])

@section('title', 'Delivery Person Payment')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ $driver->name }}&nbsp;{{ 'Settlement' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('delivery person payment') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="loading" style="display: none;"></div>
            <div class="content">
            </div>
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="duration" value="{{ $duration }}">
            <input type="hidden" name="hidden_currency" value="{{ $currency }}">
            <input type="hidden" name="driver_person" value="{{ $driver->id }}">
            <h2 class="section-title">{{ __('delivery person payment') }}</h2>
            <p class="section-lead">{{ __('delivery person payment') }}</p>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ __('Settlement amount  ') }}{{ App\Models\GeneralSetting::first()->currency_symbol }}{{ $amount }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                @if ($paymentSetting->paypal == 1)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="paypal" name="paymentradio"
                                                    class="custom-control-input">
                                                <label class="custom-control-label float-right"
                                                    for="paypal">{{ __('Paypal') }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($paymentSetting->razorpay == 1)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="razor" name="paymentradio"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="razor">{{ __('Razorpay') }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($paymentSetting->stripe == 1)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="stripe" name="paymentradio"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="stripe">{{ __('Stripe') }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($paymentSetting->flutterwave == 1)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="flutterwave" name="paymentradio"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="flutterwave">{{ __('Flutterwave') }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="cod" checked name="paymentradio"
                                                class="custom-control-input">
                                            <label class="custom-control-label" for="cod">{{ __('COD') }}</label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-9">
                    {{-- Paypal payment --}}
                    <div class="card hide paypal_card">
                        <div class="card-header">
                            <h4>{{ __('settlement with paypal') }}</h4>
                        </div>
                        <div class="card-body paypal_card_body">
                        </div>
                    </div>

                    {{-- Razorpay payment --}}
                    <div class="card hide razor_card">
                        <div class="card-header">
                            <h4>{{ __('settlement with Razorpay') }}</h4>
                        </div>
                        <div class="card-body">
                            <form id="rzp-footer-form" action="{{ url('razor') }}" method="POST">
                                @csrf
                                <br />
                                @php
                                    $razor_publish_key = App\Models\PaymentSetting::find(1)->razorpay_publish_key;
                                @endphp
                                <input type="hidden" name="RAZORPAY_KEY" id="RAZORPAY_KEY"
                                    value="{{ $razor_publish_key }}">
                                <div class="pay">
                                    <button class="razorpay-payment-button btn btn-primary" id="paybtn"
                                        type="button">{{ __('Pay with Razorpay') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Stripe payment --}}
                    <div class="card hide stripe_card">
                        <div class="card-header">
                            <h4>{{ __('settlement with stripe') }}</h4>
                        </div>
                        <div class="alert alert-warning alert-dismissible fade show hide stripe_alert" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <form class="myform" role="form" action="" method="post"
                                class="require-validation customform" data-cc-on-file="false"
                                data-stripe-publishable-key="{{ App\Models\PaymentSetting::find(1)->stripe_publish_key }}"
                                id="stripe-payment-form">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Email') }}</label>
                                            <input type="email" class="email form-control required"
                                                title="Enter Your Email" name="email" required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Card Information') }}</label>
                                            <input type="text" class="card-number required form-control"
                                                title="please input only number." pattern="[0-9]{16}" name="card-number"
                                                placeholder="1234 1234 1234 1234" title="Card Number" required />
                                            <div class="row" style="margin-top:-2px;">
                                                <div class="col-lg-6 pr-0">
                                                    <input type="text" class="expiry-date required form-control"
                                                        name="expiry-date" title="Expiration date"
                                                        title="please Enter data in MM/YY format."
                                                        pattern="(0[1-9]|10|11|12)/[0-9]{2}$" placeholder="MM/YY"
                                                        required />
                                                    <input type="hidden" class="card-expiry-month required form-control"
                                                        name="card-expiry-month" />
                                                    <input type="hidden" class="card-expiry-year required form-control"
                                                        name="card-expiry-year" />
                                                </div>

                                                <div class="col-lg-6 pl-0">
                                                    <input type="text" class="card-cvc required form-control"
                                                        title="please input only number." pattern="[0-9]{3}"
                                                        name="card-cvc" placeholder="CVC" title="CVC" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('Name on card') }}</label>
                                            <input type="text" class="required form-control" name="name"
                                                title="Name on Card" required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary mt-4 btn-submit"
                                                value="Pay" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Flutterwave payment --}}
                    <div class="card hide flutter_card">
                        <div class="card-header">
                            <h4>{{ __('Settlement with flutterwave') }}</h4>
                        </div>
                        <div class="card-body">
                            <form class="myform" action="{{ url('admin/driver_fluterPayment') }}" method="post">
                                @csrf
                                <input type="hidden" name="driver" value="{{ $driver->id }}">
                                <input type="hidden" name="amount" value="{{ $amount }}">
                                <input type="hidden" name="duration" value="{{ $duration }}">
                                <input type="submit" value="Flutterwave" class="btn btn-primary">
                            </form>
                        </div>
                    </div>

                    {{-- COD --}}
                    <div class="card cod_card">
                        <div class="card-header">
                            <h4>{{ __('settlement with cod') }}</h4>
                        </div>
                        <div class="card-body">
                            <input type="button" onclick="driver_settlement()" value="{{ __('Pay offline') }}"
                                class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
