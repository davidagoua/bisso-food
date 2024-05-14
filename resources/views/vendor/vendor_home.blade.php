@extends('layouts.app',['activePage' => 'home'])

@section('title','Vendor Dashboard')

@section('content')

<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('Vendor dashboard')}}</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-sort"></i>
                        </div>
                        <h4>{{ count($today_orders) }}</h4>
                        <div class="card-description">{{__("Today’s order")}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4>{{ count($total_orders) }}</h4>
                        <div class="card-description">{{__('Total orders')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h4>{{ $currency }}{{ $today_earnings }}</h4>
                        <div class="card-description">{{__("Today's earning")}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h4>{{ $currency }}{{ $total_earnings }}</h4>
                        <div class="card-description">{{__('Total earnings')}}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{__("Today's pending order")}}</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('Order Id')}}</th>
                                    <th>{{__('Vendor name')}}</th>
                                    <th>{{__('User Name')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Time')}}</th>
                                    <th>{{__('Order Status')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Payment status')}}</th>
                                    <th>{{__('Payment type')}}</th>
                                    <th>{{__('Order Accept')}}</th>
                                    @if (Session::get('vendor_driver') == 1)
                                        <th>{{__('Assign Driver')}}</th>
                                    @endif
                                    <th>{{__('View')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pending_today_orders as $order)
                                <tr>
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$order->order_id}}</td>
                                    <td>{{ $order['vendor']->name }}</td>
                                    <td>{{ $order['user']->name }}</td>
                                    <td>{{ $order->date }}</td>
                                    <td>{{ $order->time }}</td>
                                    <td class="orderStatusTd{{ $order->id }}">
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

                                        @if ($order->order_status == 'ACCEPT')
                                            <span class="badge badge-pill accept">{{__('ACCEPT')}}</span>
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
                                    <td>{{ $currency }}{{ $order->amount }}</td>
                                    <td>
                                        @if ($order->payment_status == 1)
                                            <div class="span">{{__('payment complete')}}</div>
                                        @endif

                                        @if ($order->payment_status == 0)
                                            <div class="span">{{__('payment not complete')}}</div>
                                        @endif
                                    </td>
                                    <td>{{ $order->payment_type }}</td>
                                    <td>
                                        @if ($order->delivery_type == 'SHOP')
                                            @if ($order->order_status == 'COMPLETE' || $order->order_status == 'CANCEL' || $order->order_status == 'REJECT')
                                                <select class="form-control w-auto" disabled name="order_status_change" id="{{$order->id}}">
                                                    <option value="COMPLETE" {{ $order->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                </select>
                                            @else
                                                @if ($order->order_status == 'PENDING')
                                                    <select class="form-control w-auto" onchange="order_status({{$order->id}})" id="status{{$order->id}}">
                                                        <option value="PENDING" disabled {{ $order->order_status == 'PENDING' ? 'selected' : '' }}>{{__('PENDING')}}</option>
                                                        <option value="APPROVE" {{ $order->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                        <option value="REJECT" {{ $order->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                    </select>
                                                @endif
                                                @if ($order->order_status == 'APPROVE' || $order->order_status == 'PREPARE_FOR_ORDER' || $order->order_status == 'READY_FOR_ORDER' || $order->order_status == 'COMPLETE')
                                                    <select class="form-control w-auto" onchange="order_status({{$order->id}})" id="status{{$order->id}}">
                                                        <option value="APPROVE" {{ $order->order_status == 'APPROVE' ? 'selected' : '' }} disabled>{{__('Approve')}}</option>
                                                        <option value="PREPARE_FOR_ORDER" {{ $order->order_status == 'PREPARE_FOR_ORDER' ? 'selected' : '' }} >{{__('Prepare for order')}}</option>
                                                        <option value="READY_FOR_ORDER" {{ $order->order_status == 'READY_FOR_ORDER' ? 'selected' : '' }}>{{__('Ready for order')}}</option>
                                                        <option value="COMPLETE" {{ $order->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                    </select>
                                                @endif
                                            @endif
                                        @else
                                            @if ($order->order_status == 'COMPLETE' || $order->order_status == 'CANCEL')
                                                <select class="form-control w-auto" disabled name="order_status_change" id="{{$order->id}}">
                                                    <option value="COMPLETE" {{ $order->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                    <option value="CANCEL" {{ $order->order_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                                </select>
                                            @else
                                                @if ($order->order_status == 'PENDING')
                                                    <select class="form-control w-auto" onchange="order_status({{$order->id}})" name="order_status_change" id="status{{$order->id}}">
                                                        <option value="PENDING" disabled {{ $order->order_status == 'PENDING' ? 'selected' : '' }}>{{__('PENDING')}}</option>
                                                        <option value="APPROVE" {{ $order->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                        <option value="REJECT" {{ $order->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                    </select>
                                                @endif

                                                @if ($order->order_status == 'APPROVE' || $order->order_status == 'PICKUP' || $order->order_status == 'DELIVERED' || $order->order_status == 'COMPLETE')
                                                    <select class="form-control w-auto" onchange="order_status({{$order->id}})" name="order_status_change" id="status{{$order->id}}">
                                                        <option value="APPROVE" disabled {{ $order->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                        <option value="PICKUP" {{ $order->order_status == 'PICKUP' ? 'selected' : '' }}>{{__('pickup')}}</option>
                                                        <option value="DELIVERED" {{ $order->order_status == 'DELIVERED' ? 'selected' : '' }}>{{__('Delivered')}}</option>
                                                        <option value="COMPLETE" {{ $order->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                    </select>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    @if (Session::get('vendor_driver') == 1)
                                        <td>
                                            <select name="assign_driver" onchange="driver_assign({{$order->id}})" id="driver_id{{ $order->id }}" {{ $order->delivery_person_id != null ? 'disabled' : '' }} {{ $order->order_status == 'CANCEL' ? 'disabled' : '' }} {{ $order->delivery_type == 'SHOP' ? 'disabled' : '' }} class="form-control">
                                                <option value="">{{__('select Drivers')}}</option>
                                                @foreach ($delivery_persons as $delivery_person)
                                                    <option value="{{ $delivery_person->id }}" {{ $delivery_person->id == $order->delivery_person_id ? 'selected' : "" }}>{{ $delivery_person->first_name.' '.$delivery_person->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{ url('vendor/order/'.$order->id) }}" onclick="show_order({{ $order->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{__('Orders chart')}}</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="userChart" class="chartjs-render-monitor"
                            style="display: block; width: 580px; height: 250px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{__('Revenue chart')}}</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" class="chartjs-render-monitor"
                            style="display: block; width: 580px; height: 250px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{__('Top selling Items')}}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('Items name')}}</th>
                                    <th>{{__('Price')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topItems as $topItem)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <th>
                                        <a href="{{ url('admin/menu/'.$topItem->menu_id) }}" class="nav-link active">
                                            {{ $topItem->itemName }}
                                        </a>
                                    </th>
                                    <th>{{ $currency }}{{ $topItem->price }}</th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{__('Top Selling Items')}}</h4>
                    </div>
                    <div class="card-body">
                        @foreach ($topItems as $topItem)
                            <div class="tickets-list">
                                <a class="ticket-item">
                                    <div class="ticket-title text-muted">
                                        <h4>{{ $topItem->itemName }}</h4>
                                    </div>
                                    <div class="ticket-info">
                                        <div>{{ $topItem->total }}{{' time served'}}</div>
                                        <div class="w-100">
                                            @if ($topItem->type == 'veg')
                                                <img src="{{ url('images/veg.png') }}" class="float-right" alt="">
                                            @else
                                                <img src="{{ url('images/non-veg.png') }}" class="float-right" alt="">
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal right fade" id="view_order" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="staticBackdropLabel">{{__('View order')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th>{{__('Order Id')}}</th>
                        <td class="show_order_id"></td>
                    </tr>
                    <tr>
                        <th>{{__('User name')}}</th>
                        <td class="show_user_name"></td>
                    </tr>
                    <tr>
                        <th>{{__('date')}}</th>
                        <td class="show_date"></td>
                    </tr>
                    <tr>
                        <th>{{__('time')}}</th>
                        <td class="show_time"></td>
                    </tr>
                    <tr>
                        <th>{{__('Delivery At')}}</th>
                        <td class="show_delivery_at"></td>
                    </tr>
                    <tr>
                        <th>{{__('Discount')}}</th>
                        <td class="show_discount"></td>
                    </tr>
                    <tr>
                        <th>{{__('Total Amount')}}</th>
                        <td class="show_total_amount"></td>
                    </tr>
                    <tr>
                        <th>{{__('Admin Commission')}}</th>
                        <td class="show_admin_commission"></td>
                    </tr>
                    <tr>
                        <th>{{__('Vendor Commission')}}</th>
                        <td class="show_vendor_amount"></td>
                    </tr>
                </table>
                <h6>{{__('tax')}}</h6>
                <table class="table TaxTable">
                </table>
                <h6>{{__('Items')}}</h6>
                <table class="table show_order_table">
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>

@endsection



