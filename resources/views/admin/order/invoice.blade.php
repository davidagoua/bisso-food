@extends('layouts.app',['activePage' => 'order'])

@section('title','Invoice')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>{{__('Invoice')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Invoice')}}</div>
        </div>
    </div>
    @php
        $discount = intval(00);
        $cust = "";
        $totalCust= 0;
        $itemPrice = 0;
    @endphp
    @if($order->promo_code_price != null)
        @php
            $discount += intval($order->promo_code_price);
        @endphp
    @elseif($order->vendor_discount_price != null)
        @php
            $discount += intval($order->vendor_discount_price);
        @endphp
    @endif

    @php
        $delivery_charge = 0;
    @endphp
    @if($order->delivery_charge != null)
        @php
            $delivery_charge = $order->delivery_charge
        @endphp
    @endif

    @php
        $tax = 0;
    @endphp
    @if($order->tax != null)
        @php
            $taxs = $order->tax;
            foreach (json_decode($taxs) as $t)
            {
                $tax = $tax + $t->tax;
            }
        @endphp
    @endif

    <div class="section-body">
        <div class="invoice">
            <div class="invoice-print">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="invoice-title">
                            <h2>{{__('Invoice')}}</h2>
                            <div class="invoice-number">{{__('Order')}} {{$order->order_id}}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <address>
                                    <strong>{{__('Billed To:')}}</strong><br>
                                    {{ $general_setting->business_name }}<br>
                                    {{ $general_setting->business_address }}
                                </address>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <address>
                                    <strong>{{__('user')}}</strong><br>
                                    {{ $order['user']->name }}<br>
                                    {{$order['user']->email}}<br>
                                    {{$order['user']->phone}}<br>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-md-6 col-md-6 text-md-right">
                                <address>
                                    <strong>{{__('Order Date:')}}</strong><br>
                                    {{ $order->date }}&nbsp;{{ $order->time }}<br><br>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="section-title">{{__('Order Summary')}}</div>
                        <p class="section-lead">{{__('All items here cannot be deleted.')}}</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <tbody>
                                    <tr>
                                        <th data-width="40" style="width: 40px;">#</th>
                                        <th>{{__('Item')}}</th>
                                        <th class="text-center">{{__('Price')}}</th>
                                        <th class="text-center">{{__('Quantity')}}</th>
                                        <th class="text-center">{{__('Custimization')}}</th>
                                        <th class="text-center">{{__('Custimization price')}}</th>
                                        <th class="text-right">{{__('Totals')}}</th>
                                    </tr>
                                    @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $item['itemName'] }}</td>
                                        <td class="text-center">
                                            {{ $general_setting->currency_symbol }}{{ $item->price }}
                                        </td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        @if ($item->custimization != null)
                                            @foreach ($item->custimization as $custimize)
                                                @php
                                                    $cust = $cust ." ". $custimize->data->name.",";
                                                    $totalCust += $custimize->data->price;
                                                @endphp
                                            @endforeach
                                            <td class="text-center">{{$cust}}</td>
                                            <td class="text-center">{{$totalCust}}</td>
                                        @else
                                            @php
                                                $totalCust= 0;
                                            @endphp

                                            <td class="text-center">{{__('Not included')}}</td>
                                            <td class="text-center">{{__('Not included')}}</td>
                                        @endif
                                        <td class="text-right">{{ $general_setting->currency_symbol }}{{ $item->price + $totalCust }}</td>
                                        @php
                                            $itemPrice = $item->price + $totalCust;
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="section-title mt-5">{{__('Tax')}}</div>
                            <p class="section-lead">{{__('Tax Description.')}}</p>
                            <table class="table table-striped table-hover table-md text-center">
                                <tbody>
                                    <tr>
                                        <th data-width="40" style="width: 40px;">#</th>
                                        <th>{{__('Tax Name')}}</th>
                                        <th>{{__('Tax value')}}</th>
                                    </tr>
                                    @foreach (json_decode($order->tax) as $ts)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $ts->name }}</td>
                                        <td>
                                            {{ $general_setting->currency_symbol }}{{ $ts->tax }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="offset-lg-8 col-lg-4 text-right">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">{{__('Tax')}}</div>
                                    <div class="invoice-detail-value">
                                        {{ $general_setting->currency_symbol }}{{ $tax }}</div>
                                </div>
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">{{__('Subtotal')}}</div>
                                    <div class="invoice-detail-value">
                                        {{ $general_setting->currency_symbol }}{{ $itemPrice }}</div>
                                </div>
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">{{__('Shipping')}}</div>
                                    <div class="invoice-detail-value">
                                        {{ $general_setting->currency_symbol }}{{ $delivery_charge }}
                                    </div>
                                </div>

                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">{{__('Discount')}}</div>
                                    <div class="invoice-detail-value">
                                        {{ $general_setting->currency_symbol }}{{ $discount }}
                                    </div>
                                </div>
                                <hr class="mt-2 mb-2">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">{{__('Total')}}</div>
                                    <div class="invoice-detail-value invoice-detail-value-lg">
                                        {{ $general_setting->currency_symbol }}{{ $order->amount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-md-left">
                <a href="{{ url('admin/order/invoice_print/'.$order->id) }}" target="_blank" class="btn btn-primary btn-icon icon-left"><i class="fas fa-print"></i> {{__('Print')}}</a>
            </div>
        </div>
    </div>
</section>

@endsection
