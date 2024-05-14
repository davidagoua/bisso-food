@extends('layouts.app',['activePage' => 'order_report'])

@section('title','Order Report')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('order report')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('order report')}}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card p-3">
                    <form action="{{ url('admin/order_report') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-12">
                                <input type="text" name="date_range" class="form-control">
                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
                                <input type="submit" value="{{__('apply')}}" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger"><i class="far fa-user"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('Total orders')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ count($orders) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success"><i class="fas fa-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('This month earning')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $currency }}{{ $month_earning }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning"><i class="fas fa-circle"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('This year earning')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $currency }}{{ $year_earning }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        <h4>{{__('Order report')}}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered report" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('Order Id')}}</th>
                                    <th>{{__('Vendor name')}}</th>
                                    <th>{{__('User name')}}</th>
                                    <th>{{__('Order date')}}</th>
                                    <th>{{__('Order time')}}</th>
                                    <th>{{__('amount')}}</th>
                                    <th>{{__('Tax')}}</th>
                                    <th>{{__('promo code price')}}</th>
                                    <th>{{__('vendor discount price')}}</th>
                                    <th>{{__('delivery charge')}}</th>
                                    <th>{{__('payment type')}}</th>
                                    <th>{{__('Order status')}}</th>
                                    <th>{{__('Payment Status')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->vendor['name'] }}</td>
                                        <td>{{ $order->user['name'] }}</td>
                                        <td>{{ $order->date }}</td>
                                        <td>{{ $order->time }}</td>
                                        <td>{{ $currency }}{{ $order->amount }}</td>
                                        <td>
                                            @if ($order->tax == null)
                                                {{ $currency }}00
                                            @else
                                                @php
                                                    $t = 0;
                                                    $taxs = json_decode($order->tax);
                                                    if(is_array($taxs))
                                                    {
                                                        foreach ($taxs as $tax)
                                                        {
                                                            $t += intval($tax->tax);
                                                        }
                                                    }
                                                @endphp
                                            @endif
                                            {{ $currency }}{{ $t }}
                                        </td>
                                        <td>
                                            @if ($order->promocode_price == null)
                                                {{ $currency }}{{00}}
                                            @else
                                                {{ $currency }}{{$order->promocode_price}}
                                            @endif
                                        </td>
                                        <td>{{ $currency }}{{ $order->vendor_discount_price }}</td>
                                            @if ($order->delivery_charge == null)
                                                <td>{{ $currency }}00</td>
                                            @else
                                                <td>{{ $currency }}{{ $order->delivery_charge }}</td>
                                            @endif
                                            <td>{{ $order->payment_type }}</td>
                                            <td>
                                                @if ($order->order_status == 'PENDING')
                                                    <span class="badge badge-pill pending">{{__('PENDING')}}</span>
                                                @endif

                                                @if ($order->order_status == 'APPROVE')
                                                    <span class="badge badge-pill approve">{{__('APPROVE')}}</span>
                                                @endif

                                                @if ($order->order_status == 'REJECT')
                                                    <span class="badge badge-pill reject">{{__('REJECT')}}</span>
                                                @endif

                                                @if ($order->order_status == 'CANCEL')
                                                    <span class="badge badge-pill cancel">{{__('CANCEL')}}</span>
                                                @endif

                                                @if ($order->order_status == 'PICKUP')
                                                    <span class="badge badge-pill pickup">{{__('PICKUP')}}</span>
                                                @endif

                                                @if ($order->order_status == 'DELIVERED')
                                                    <span class="badge badge-pill delivered">{{__('DELIVERED')}}</span>
                                                @endif

                                                @if ($order->order_status == 'COMPLETE')
                                                    <span class="badge badge-pill complete">{{__('COMPLETE')}}</span>
                                                @endif

                                                @if ($order->order_status == 'PREPARE_FOR_ORDER')
                                                    <span class="badge badge-pill preparre-food">{{__('PREPARE FOR ORDER')}}</span>
                                                @endif

                                                @if ($order->order_status == 'READY_FOR_ORDER')
                                                    <span class="badge badge-pill ready_for_food">{{__('READY FOR ORDER')}}</span>
                                                @endif
                                            </td>
                                        <td>
                                            @if ($order->payment_status == 1)
                                                <div class="badge badge-success">{{__('Complete')}}</div>
                                            @else
                                                <div class="badge badge-danger">{{__('Not complete')}}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

