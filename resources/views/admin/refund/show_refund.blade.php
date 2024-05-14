@extends('layouts.app', ['activePage' => 'refund'])

@section('title', 'Refund')

@section('content')
    <input type="hidden" name="refund_id" value="{{ $refund->id }}">
    <input type="hidden" name="currency" value="{{ $c }}">
    <input type="hidden" name="amount" value="{{ $refund->order['amount'] }}">
    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif
        <div class="section-header">
            <h1>{{ __('Refund') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Refund') }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('Refund') }}</h2>
            <p class="section-lead">{{ __('Refund Management') }}</p>
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Refund Amount ') }}{{ $currency }}{{ $refund->order['amount'] }}</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                @if ($paymentSetting->stripe == 1)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="stripe" checked name="paymentradio"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="stripe">{{ __('Stripe') }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-8 col-md-6">
                    <div class="card stripe_card">
                        <div class="card-header">
                            <h4>{{ __('Refund with stripe') }}</h4>
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
                                                        title="please input only number." pattern="[0-9]{3}" name="card-cvc"
                                                        placeholder="CVC" title="CVC" required />
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

                    <div class="card hide paypal_card">
                        <div class="card-header">
                            <h4>{{ __('Refund with paypal') }}</h4>
                        </div>
                        <div class="card-body paypal_card_body">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
