@extends('layouts.app', ['activePage' => 'setting'])

@section('title', 'Order Setting')

@section('content')

    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Order pages') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ url('admin/setting') }}">{{ __('Setting') }}</a></div>
                <div class="breadcrumb-item">{{ __('Order pages') }}</div>
            </div>
        </div>
        <div class="section-body">
            <form class="myform" action="{{ url('admin/update_order_setting') }}" method="post">
                @csrf

                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <h5>{{ __('auto cancel order') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="">{{ __('order cancel thresold by vendor(In minutes)') }}</label>
                                <input type="number" min=1 value="{{ $orderData->vendor_order_max_time }}" required
                                    name="vendor_order_max_time" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">{{ __('order cancel thresold by driver(In minutes)') }}</label>
                                <input type="number" min=1 required value="{{ $orderData->driver_order_max_time }}"
                                    name="driver_order_max_time" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary mt-3">
                    <div class="card-header">
                        {{ __('delivery charges') }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">{{ __('charges based on ?') }}</label>
                                <select class="form-control" name="delivery_charge_type">
                                    <option value="order_amount"
                                        {{ $orderData->delivery_charge_type == 'order_amount' ? 'selected' : '' }}>
                                        {{ __('order amount') }}</option>
                                    <option value="delivery_distance"
                                        {{ $orderData->delivery_charge_type == 'delivery_distance' ? 'selected' : '' }}>
                                        {{ __('Delivery distance (KM)') }}</option>
                                </select>
                            </div>
                        </div>
                        <?php
                        $charges = json_decode($orderData->charges);
                        ?>
                        <div>
                            <table class="table delivery_table">
                                <tr
                                    class="delivery_charge_table {{ $orderData->delivery_charge_type == 'order_amount' ? 'hide' : '' }}">
                                    <td>{{ __('Distance From') }}</td>
                                    <td>{{ __('Distance To') }}</td>
                                    <td>{{ __('Charges') }}({{ $currency_symbol }})</td>
                                    <td></td>
                                </tr>
                                <tr
                                    class="order_charge_table {{ $orderData->delivery_charge_type == 'delivery_distance' ? 'hide' : '' }}">
                                    <td>{{ __('Order From') }}</td>
                                    <td>{{ __('Order To') }}</td>
                                    <td>{{ __('Charges') }}({{ $currency_symbol }})</td>
                                    <td></td>
                                </tr>
                                @foreach ($charges as $charge)
                                    <tr>
                                        <td><input type="number" min=1 required value="{{ $charge->min_value }}"
                                                name="min_value[]" class="form-control"></td>
                                        <td><input type="number" min=1 required value="{{ $charge->max_value }}"
                                                name="max_value[]" class="form-control"></td>
                                        <td><input type="text" required value="{{ $charge->charges }}" name="charges[]"
                                                class="form-control floatTextBox"></td>
                                        <td><button type="button" class="btn btn-danger removebtn"><i
                                                    class="fas fa-times"></i></button></td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary"
                                    onclick="add_field()">{{ __('Add Field') }}</button>
                            </div>
                        </div>
                        <?php
                        $order_charges = json_decode($orderData->order_charges);
                        ?>
                    </div>
                    <div class="card-footer">
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
