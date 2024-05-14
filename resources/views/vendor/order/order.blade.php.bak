@extends('layouts.app',['activePage' => 'order'])

@section('title','Order')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>{{__('Orders')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('order')}}</div>
        </div>
    </div>
    <div class="section-body">
        <input type="hidden" name="currency" value="{{ $currency }}">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card p-3">
                    <form action="{{ url('vendor/Orders') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-12">
                                <input type="text" name="date_range" class="form-control">
                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
                                <input type="button" value="{{__('apply')}}" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills orderStatusUl" id="myTab3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" id="all-tab3" data-toggle="tab" href="#all3" role="tab"aria-controls="home" aria-selected="true">{{__('All')}}({{count($orders)}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pending-tab3" data-toggle="tab" href="#pending3" role="tab" aria-controls="profile" aria-selected="false">{{__('Pending')}}({{count($pendingOrders)}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="approve-tab3" data-toggle="tab" href="#approve3" role="tab" aria-controls="approve" aria-selected="false">{{__('Approve')}}({{count($approveOrders)}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="delivered-tab3" data-toggle="tab" href="#delivered3" role="tab" aria-controls="delivered" aria-selected="false">{{__('Delivered')}}({{count($deliveredOrders)}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pickup-tab3" data-toggle="tab" href="#pickup3" role="tab" aria-controls="pickup" aria-selected="false">{{__('PickUp')}}({{count($pickUpOrders)}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cancel-tab3" data-toggle="tab" href="#cancel3" role="tab" aria-controls="contact" aria-selected="false">{{__('cancel')}}({{count($cancelOrders)}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="complete-tab3" data-toggle="tab" href="#complete3" role="tab" aria-controls="complete" aria-selected="false">{{__('Complete')}}({{count($completeOrders)}})</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>{{__('Order')}}</h4>
                <div class="w-100">
                    @can('vendor_order_add')
                        <a href="{{ url('vendor/order/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent2">
                    <div class="tab-pane fade active show" id="all3" role="tabpanel" aria-labelledby="all-tab3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered orderTable" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <input name="select_all" value="1" id="master" type="checkbox" />
                                            <label for="master"></label>
                                        </th>
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
                                        <th>{{__('Delivery Person Name')}}</th>
                                        @if (Session::get('vendor_driver') == 0)
                                            <th>{{__('Received Amount From Delivery Person')}}</th>
                                            <th>{{__('Received Amount?')}}</th>
                                        @endif
                                        <th>{{__('View')}}</th>
                                        <th>{{__('Delete')}}</th>
                                        <th>{{__('Print')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                <input name="id[]" value="{{$order->id}}" id="{{$order->id}}" data-id="{{ $order->id }}" class="sub_chk" type="checkbox" />
                                                <label for="{{$order->id}}"></label>
                                            </td>
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
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

                                                @if ($order->order_status == 'ACCEPT')
                                                    <span class="badge badge-pill accept">{{__('ACCEPT')}}</span>
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
                                                                <option value="PENDING" disabled {{ $order->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
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
                                                                <option value="PENDING" disabled {{ $order->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
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
                                                    <select name="assign_driver" onchange="driver_assign({{$order->id}})" id="driver_id{{ $order->id }}" {{ $order->delivery_person_id != null ? 'disabled' : '' }} {{ $order->delivery_type == 'SHOP' ? 'disabled' : '' }} class="form-control w-auto">
                                                        <option value="">{{__('select Drivers')}}</option>
                                                        @foreach ($delivery_persons as $delivery_person)
                                                            <option value="{{ $delivery_person->id }}" {{ $delivery_person->id == $order->delivery_person_id ? 'selected' : "" }}>{{ $delivery_person->first_name.' '.$delivery_person->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endif
                                            <td>{{ $order->deliver_person_name }}</td>
                                            @if (Session::get('vendor_driver') == 0)
                                                @if ($order->payment_type == 'COD' && $order->vendor_pending_amount == 0 && $order->order_status == 'COMPLETE')
                                                    <td>{{ $currency }}{{ $order->amount }}</td>
                                                    <td>
                                                        <a href="{{ url('vendor/deliveryPerson/pending_amount/'.$order->id) }}" class="text-danger">{{__('Pending Amount')}}</a>
                                                    </td>
                                                @else
                                                    <td>{{ $currency }}{{00}}</td>
                                                    <td>
                                                        <span class="text-primary">{{__('Recieved Amount')}}</span>
                                                    </td>
                                                @endif
                                            @endif
                                            <td>
                                                <a href="{{ url('vendor/order/'.$order->id) }}" onclick="show_order({{ $order->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="table-action btn btn-danger btn-action" onclick="deleteData('vendor/order',{{ $order->id }},'Order')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ url('vendor/print_thermal/'.$order->id) }}">
                                                    {{__('Print Bill')}}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        @if (Session::get('vendor_driver') == 1)
                                            <th></th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pending3" role="tabpanel" aria-labelledby="pending-tab3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered orderTable" cellspacing="0" width="100%">
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
                                        <th>{{__('View')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingOrders as $pendingOrder)
                                        <tr>
                                            <input type="hidden" name="order_id" value="{{ $pendingOrder->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$pendingOrder->order_id}}</td>
                                            <td>{{ $pendingOrder['vendor']->name }}</td>
                                            <td>{{ $pendingOrder['user']->name }}</td>
                                            <td>{{ $pendingOrder->date }}</td>
                                            <td>{{ $pendingOrder->time }}</td>
                                            <td class="orderStatusTd{{ $pendingOrder->id }}">
                                                @if ($pendingOrder->order_status == 'PENDING')
                                                    <span class="badge badge-pill pending">{{__('PENDING')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'APPROVE')
                                                    <span class="badge badge-pill approve">{{__('APPROVE')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'REJECT')
                                                    <span class="badge badge-pill reject">{{__('REJECT')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'CANCEL')
                                                    <span class="badge badge-pill cancel">{{__('CANCEL')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'PICKUP')
                                                    <span class="badge badge-pill pickup">{{__('PICKUP')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'DELIVERED')
                                                    <span class="badge badge-pill delivered">{{__('DELIVERED')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'COMPLETE')
                                                    <span class="badge badge-pill complete">{{__('COMPLETE')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'PREPARE_FOR_ORDER')
                                                    <span class="badge badge-pill preparre-food">{{__('PREPARE FOR ORDER')}}</span>
                                                @endif

                                                @if ($pendingOrder->order_status == 'READY_FOR_ORDER')
                                                    <span class="badge badge-pill ready_for_food">{{__('READY FOR ORDER')}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $currency }}{{ $pendingOrder->amount }}</td>
                                            <td>
                                                @if ($pendingOrder->payment_status == 1)
                                                    <div class="span">{{__('payment complete')}}</div>
                                                @endif

                                                @if ($pendingOrder->payment_status == 0)
                                                    <div class="span">{{__('payment not complete')}}</div>
                                                @endif
                                            </td>
                                            <td>{{ $pendingOrder->payment_type }}</td>
                                            <td>
                                                @if ($pendingOrder->delivery_type == 'SHOP')
                                                    @if ($pendingOrder->order_status == 'COMPLETE' || $pendingOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$pendingOrder->id}}">
                                                            <option value="COMPLETE" {{ $pendingOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$pendingOrder->id}})" id="status{{$pendingOrder->id}}">
                                                            <option value="PENDING" {{ $pendingOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $pendingOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="PREPARE_FOR_ORDER" {{ $pendingOrder->order_status == 'PREPARE_FOR_ORDER' ? 'selected' : '' }}>{{__('Prepare for order')}}</option>
                                                            <option value="READY_FOR_ORDER" {{ $pendingOrder->order_status == 'READY_FOR_ORDER' ? 'selected' : '' }}>{{__('Ready for order')}}</option>
                                                            <option value="REJECT" {{ $pendingOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="COMPLETE" {{ $pendingOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    @if ($pendingOrder->order_status == 'COMPLETE' || $pendingOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$pendingOrder->id}}">
                                                            <option value="COMPLETE" {{ $pendingOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                            <option value="CANCEL" {{ $pendingOrder->order_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$pendingOrder->id}})" name="order_status_change" id="status{{$pendingOrder->id}}">
                                                            <option value="PENDING" {{ $pendingOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $pendingOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="REJECT" {{ $pendingOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="PICKUP" {{ $pendingOrder->order_status == 'PICKUP' ? 'selected' : '' }}>{{__('pickup')}}</option>
                                                            <option value="DELIVERED" {{ $pendingOrder->order_status == 'DELIVERED' ? 'selected' : '' }}>{{__('Delivered')}}</option>
                                                            <option value="COMPLETE" {{ $pendingOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('vendor/order/'.$pendingOrder->id) }}" onclick="show_order({{ $pendingOrder->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="approve3" role="tabpanel" aria-labelledby="approve-tab3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered orderTable" cellspacing="0" width="100%">
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
                                    @foreach ($approveOrders as $approveOrder)
                                        <tr>
                                            <input type="hidden" name="order_id" value="{{ $approveOrder->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$approveOrder->order_id}}</td>
                                            <td>{{ $approveOrder['vendor']->name }}</td>
                                            <td>{{ $approveOrder['user']->name }}</td>
                                            <td>{{ $approveOrder->date }}</td>
                                            <td>{{ $approveOrder->time }}</td>
                                            <td class="orderStatusTd{{ $approveOrder->id }}">
                                                @if ($approveOrder->order_status == 'PENDING')
                                                    <span class="badge badge-pill pending">{{__('PENDING')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'APPROVE')
                                                    <span class="badge badge-pill approve">{{__('APPROVE')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'REJECT')
                                                    <span class="badge badge-pill reject">{{__('REJECT')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'CANCEL')
                                                    <span class="badge badge-pill cancel">{{__('CANCEL')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'PICKUP')
                                                    <span class="badge badge-pill pickup">{{__('PICKUP')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'DELIVERED')
                                                    <span class="badge badge-pill delivered">{{__('DELIVERED')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'COMPLETE')
                                                    <span class="badge badge-pill complete">{{__('COMPLETE')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'PREPARE_FOR_ORDER')
                                                    <span class="badge badge-pill preparre-food">{{__('PREPARE FOR ORDER')}}</span>
                                                @endif

                                                @if ($approveOrder->order_status == 'READY_FOR_ORDER')
                                                    <span class="badge badge-pill ready_for_food">{{__('READY FOR ORDER')}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $currency }}{{ $approveOrder->amount }}</td>
                                            <td>
                                                @if ($approveOrder->payment_status == 1)
                                                    <div class="span">{{__('payment complete')}}</div>
                                                @endif

                                                @if ($approveOrder->payment_status == 0)
                                                    <div class="span">{{__('payment not complete')}}</div>
                                                @endif
                                            </td>
                                            <td>{{ $approveOrder->payment_type }}</td>
                                            <td>
                                                @if ($approveOrder->delivery_type == 'SHOP')
                                                    @if ($approveOrder->order_status == 'COMPLETE' || $approveOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$approveOrder->id}}">
                                                            <option value="COMPLETE" {{ $approveOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$approveOrder->id}})" id="status{{$approveOrder->id}}">
                                                            <option value="PENDING" {{ $approveOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $approveOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="PREPARE_FOR_ORDER" {{ $approveOrder->order_status == 'PREPARE_FOR_ORDER' ? 'selected' : '' }}>{{__('Prepare for order')}}</option>
                                                            <option value="READY_FOR_ORDER" {{ $approveOrder->order_status == 'READY_FOR_ORDER' ? 'selected' : '' }}>{{__('Ready for order')}}</option>
                                                            <option value="REJECT" {{ $approveOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="COMPLETE" {{ $approveOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    @if ($approveOrder->order_status == 'COMPLETE' || $approveOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$approveOrder->id}}">
                                                            <option value="COMPLETE" {{ $approveOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                            <option value="CANCEL" {{ $approveOrder->order_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$approveOrder->id}})" name="order_status_change" id="status{{$approveOrder->id}}">
                                                            <option value="PENDING" {{ $approveOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $approveOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="REJECT" {{ $approveOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="PICKUP" {{ $approveOrder->order_status == 'PICKUP' ? 'selected' : '' }}>{{__('pickup')}}</option>
                                                            <option value="DELIVERED" {{ $approveOrder->order_status == 'DELIVERED' ? 'selected' : '' }}>{{__('Delivered')}}</option>
                                                            <option value="COMPLETE" {{ $approveOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            @if (Session::get('vendor_driver') == 1)
                                                <td>
                                                    <select name="assign_driver w-auto" onchange="driver_assign({{$approveOrder->id}})" id="driver_id{{ $approveOrder->id }}" {{ $approveOrder->delivery_person_id != null ? 'disabled' : '' }} class="form-control">
                                                        <option value="">{{__('select Drivers')}}</option>
                                                        @foreach ($delivery_persons as $delivery_person)
                                                            <option value="{{ $delivery_person->id }}" {{ $delivery_person->id == $approveOrder->delivery_person_id ? 'selected' : "" }}>{{ $delivery_person->first_name.' '.$delivery_person->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{ url('vendor/order/'.$approveOrder->id) }}" onclick="show_order({{ $approveOrder->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="delivered3" role="tabpanel" aria-labelledby="delivered-tab3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered orderTable" cellspacing="0" width="100%">
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
                                        <th>{{__('View')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deliveredOrders as $deliveryOrder)
                                        <tr>
                                            <input type="hidden" name="order_id" value="{{ $deliveryOrder->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$deliveryOrder->order_id}}</td>
                                            <td>{{ $deliveryOrder['vendor']->name }}</td>
                                            <td>{{ $deliveryOrder['user']->name }}</td>
                                            <td>{{ $deliveryOrder->date }}</td>
                                            <td>{{ $deliveryOrder->time }}</td>
                                            <td class="orderStatusTd{{ $deliveryOrder->id }}">
                                                @if ($deliveryOrder->order_status == 'PENDING')
                                                    <span class="badge badge-pill pending">{{__('PENDING')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'APPROVE')
                                                    <span class="badge badge-pill approve">{{__('APPROVE')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'REJECT')
                                                    <span class="badge badge-pill reject">{{__('REJECT')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'CANCEL')
                                                    <span class="badge badge-pill cancel">{{__('CANCEL')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'PICKUP')
                                                    <span class="badge badge-pill pickup">{{__('PICKUP')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'DELIVERED')
                                                    <span class="badge badge-pill delivered">{{__('DELIVERED')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'COMPLETE')
                                                    <span class="badge badge-pill complete">{{__('COMPLETE')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'PREPARE_FOR_ORDER')
                                                    <span class="badge badge-pill preparre-food">{{__('PREPARE FOR ORDER')}}</span>
                                                @endif

                                                @if ($deliveryOrder->order_status == 'READY_FOR_ORDER')
                                                    <span class="badge badge-pill ready_for_food">{{__('READY FOR ORDER')}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $currency }}{{ $deliveryOrder->amount }}</td>
                                            <td>
                                                @if ($deliveryOrder->payment_status == 1)
                                                    <div class="span">{{__('payment complete')}}</div>
                                                @endif

                                                @if ($deliveryOrder->payment_status == 0)
                                                    <div class="span">{{__('payment not complete')}}</div>
                                                @endif
                                            </td>
                                            <td>{{ $deliveryOrder->payment_type }}</td>
                                            <td>
                                                @if ($deliveryOrder->delivery_type == 'SHOP')
                                                    @if ($deliveryOrder->order_status == 'COMPLETE' || $deliveryOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$deliveryOrder->id}}">
                                                            <option value="COMPLETE" {{ $deliveryOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$deliveryOrder->id}})" id="status{{$deliveryOrder->id}}">
                                                            <option value="PENDING" {{ $deliveryOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $deliveryOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="PREPARE_FOR_ORDER" {{ $deliveryOrder->order_status == 'PREPARE_FOR_ORDER' ? 'selected' : '' }}>{{__('Prepare for order')}}</option>
                                                            <option value="READY_FOR_ORDER" {{ $deliveryOrder->order_status == 'READY_FOR_ORDER' ? 'selected' : '' }}>{{__('Ready for order')}}</option>
                                                            <option value="REJECT" {{ $deliveryOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="COMPLETE" {{ $deliveryOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    @if ($deliveryOrder->order_status == 'COMPLETE' || $deliveryOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$deliveryOrder->id}}">
                                                            <option value="COMPLETE" {{ $deliveryOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                            <option value="CANCEL" {{ $deliveryOrder->order_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$deliveryOrder->id}})" name="order_status_change" id="status{{$deliveryOrder->id}}">
                                                            <option value="PENDING" {{ $deliveryOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $deliveryOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="REJECT" {{ $deliveryOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="PICKUP" {{ $deliveryOrder->order_status == 'PICKUP' ? 'selected' : '' }}>{{__('pickup')}}</option>
                                                            <option value="DELIVERED" {{ $deliveryOrder->order_status == 'DELIVERED' ? 'selected' : '' }}>{{__('Delivered')}}</option>
                                                            <option value="COMPLETE" {{ $deliveryOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('vendor/order/'.$deliveryOrder->id) }}" onclick="show_order({{ $deliveryOrder->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pickup3" role="tabpanel" aria-labelledby="pickup-tab3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered orderTable" cellspacing="0" width="100%">
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
                                        <th>{{__('View')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pickUpOrders as $pickUpOrder)
                                        <tr>
                                            <input type="hidden" name="order_id" value="{{ $pickUpOrder->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$pickUpOrder->order_id}}</td>
                                            <td>{{ $pickUpOrder['vendor']->name }}</td>
                                            <td>{{ $pickUpOrder['user']->name }}</td>
                                            <td>{{ $pickUpOrder->date }}</td>
                                            <td>{{ $pickUpOrder->time }}</td>
                                            <td class="orderStatusTd{{ $pickUpOrder->id }}">
                                                @if ($pickUpOrder->order_status == 'PENDING')
                                                    <span class="badge badge-pill pending">{{__('PENDING')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'APPROVE')
                                                    <span class="badge badge-pill approve">{{__('APPROVE')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'REJECT')
                                                    <span class="badge badge-pill reject">{{__('REJECT')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'CANCEL')
                                                    <span class="badge badge-pill cancel">{{__('CANCEL')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'PICKUP')
                                                    <span class="badge badge-pill pickup">{{__('PICKUP')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'DELIVERED')
                                                    <span class="badge badge-pill delivered">{{__('DELIVERED')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'COMPLETE')
                                                    <span class="badge badge-pill complete">{{__('COMPLETE')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'PREPARE_FOR_ORDER')
                                                    <span class="badge badge-pill preparre-food">{{__('PREPARE FOR ORDER')}}</span>
                                                @endif

                                                @if ($pickUpOrder->order_status == 'READY_FOR_ORDER')
                                                    <span class="badge badge-pill ready_for_food">{{__('READY FOR ORDER')}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $currency }}{{ $pickUpOrder->amount }}</td>
                                            <td>
                                                @if ($pickUpOrder->payment_status == 1)
                                                    <div class="span">{{__('payment complete')}}</div>
                                                @endif

                                                @if ($pickUpOrder->payment_status == 0)
                                                    <div class="span">{{__('payment not complete')}}</div>
                                                @endif
                                            </td>
                                            <td>{{ $pickUpOrder->payment_type }}</td>
                                            <td>
                                                @if ($pickUpOrder->delivery_type == 'SHOP')
                                                    @if ($pickUpOrder->order_status == 'COMPLETE' || $pickUpOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$pickUpOrder->id}}">
                                                            <option value="COMPLETE" {{ $pickUpOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$pickUpOrder->id}})" id="status{{$pickUpOrder->id}}">
                                                            <option value="PENDING" {{ $pickUpOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $pickUpOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="PREPARE_FOR_ORDER" {{ $pickUpOrder->order_status == 'PREPARE_FOR_ORDER' ? 'selected' : '' }}>{{__('Prepare for order')}}</option>
                                                            <option value="READY_FOR_ORDER" {{ $pickUpOrder->order_status == 'READY_FOR_ORDER' ? 'selected' : '' }}>{{__('Ready for order')}}</option>
                                                            <option value="REJECT" {{ $pickUpOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="COMPLETE" {{ $pickUpOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    @if ($pickUpOrder->order_status == 'COMPLETE' || $pickUpOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$pickUpOrder->id}}">
                                                            <option value="COMPLETE" {{ $pickUpOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                            <option value="CANCEL" {{ $pickUpOrder->order_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$pickUpOrder->id}})" name="order_status_change" id="status{{$pickUpOrder->id}}">
                                                            <option value="PENDING" {{ $pickUpOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $pickUpOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="REJECT" {{ $pickUpOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="PICKUP" {{ $pickUpOrder->order_status == 'PICKUP' ? 'selected' : '' }}>{{__('pickup')}}</option>
                                                            <option value="DELIVERED" {{ $pickUpOrder->order_status == 'DELIVERED' ? 'selected' : '' }}>{{__('Delivered')}}</option>
                                                            <option value="COMPLETE" {{ $pickUpOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('vendor/order/'.$pickUpOrder->id) }}" onclick="show_order({{ $pickUpOrder->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cancel3" role="tabpanel" aria-labelledby="cancel-tab3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered orderTable" cellspacing="0" width="100%">
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
                                        <th>{{__('Cancel by')}}</th>
                                        <th>{{__('Cancel reason')}}</th>
                                        <th>{{__('Payment status')}}</th>
                                        <th>{{__('Payment type')}}</th>
                                        <th>{{__('Order Accept')}}</th>
                                        <th>{{__('View')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cancelOrders as $cancelOrder)
                                        <tr>
                                            <input type="hidden" name="order_id" value="{{ $cancelOrder->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$cancelOrder->order_id}}</td>
                                            <td>{{ $cancelOrder['vendor']->name }}</td>
                                            <td>{{ $cancelOrder['user']->name }}</td>
                                            <td>{{ $cancelOrder->date }}</td>
                                            <td>{{ $cancelOrder->time }}</td>
                                            <td class="orderStatusTd{{ $cancelOrder->id }}">
                                                @if ($cancelOrder->order_status == 'PENDING')
                                                    <span class="badge badge-pill pending">{{__('PENDING')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'APPROVE')
                                                    <span class="badge badge-pill approve">{{__('APPROVE')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'REJECT')
                                                    <span class="badge badge-pill reject">{{__('REJECT')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'CANCEL')
                                                    <span class="badge badge-pill cancel">{{__('CANCEL')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'PICKUP')
                                                    <span class="badge badge-pill pickup">{{__('PICKUP')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'DELIVERED')
                                                    <span class="badge badge-pill delivered">{{__('DELIVERED')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'COMPLETE')
                                                    <span class="badge badge-pill complete">{{__('COMPLETE')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'PREPARE_FOR_ORDER')
                                                    <span class="badge badge-pill preparre-food">{{__('PREPARE FOR ORDER')}}</span>
                                                @endif

                                                @if ($cancelOrder->order_status == 'READY_FOR_ORDER')
                                                    <span class="badge badge-pill ready_for_food">{{__('READY FOR ORDER')}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $currency }}{{ $cancelOrder->amount }}</td>
                                            <td>{{ $cancelOrder->cancel_by }}</td>
                                            <td>{{ $cancelOrder->cancel_reason }}</td>
                                            <td>
                                                @if ($cancelOrder->payment_status == 1)
                                                    <div class="span">{{__('payment complete')}}</div>
                                                @endif

                                                @if ($cancelOrder->payment_status == 0)
                                                    <div class="span">{{__('payment not complete')}}</div>
                                                @endif
                                            </td>
                                            <td>{{ $cancelOrder->payment_type }}</td>
                                            <td>
                                                @if ($cancelOrder->delivery_type == 'SHOP')
                                                    @if ($cancelOrder->order_status == 'COMPLETE' || $cancelOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$cancelOrder->id}}">
                                                            <option value="COMPLETE" {{ $cancelOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$cancelOrder->id}})" id="status{{$cancelOrder->id}}">
                                                            <option value="PENDING" {{ $cancelOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $cancelOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="PREPARE_FOR_ORDER" {{ $cancelOrder->order_status == 'PREPARE_FOR_ORDER' ? 'selected' : '' }}>{{__('Prepare for order')}}</option>
                                                            <option value="READY_FOR_ORDER" {{ $cancelOrder->order_status == 'READY_FOR_ORDER' ? 'selected' : '' }}>{{__('Ready for order')}}</option>
                                                            <option value="REJECT" {{ $cancelOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="COMPLETE" {{ $cancelOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    @if ($cancelOrder->order_status == 'COMPLETE' || $cancelOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$cancelOrder->id}}">
                                                            <option value="COMPLETE" {{ $cancelOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                            <option value="CANCEL" {{ $cancelOrder->order_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$cancelOrder->id}})" name="order_status_change" id="status{{$cancelOrder->id}}">
                                                            <option value="PENDING" {{ $cancelOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $cancelOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="REJECT" {{ $cancelOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="PICKUP" {{ $cancelOrder->order_status == 'PICKUP' ? 'selected' : '' }}>{{__('pickup')}}</option>
                                                            <option value="DELIVERED" {{ $cancelOrder->order_status == 'DELIVERED' ? 'selected' : '' }}>{{__('Delivered')}}</option>
                                                            <option value="COMPLETE" {{ $cancelOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('vendor/order/'.$cancelOrder->id) }}" onclick="show_order({{ $cancelOrder->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="complete3" role="tabpanel" aria-labelledby="complete-tab3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered orderTable" cellspacing="0" width="100%">
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
                                        <th>{{__('View')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($completeOrders as $completeOrder)
                                        <tr>
                                            <input type="hidden" name="order_id" value="{{ $completeOrder->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$completeOrder->order_id}}</td>
                                            <td>{{ $completeOrder['vendor']->name }}</td>
                                            <td>{{ $completeOrder['user']->name }}</td>
                                            <td>{{ $completeOrder->date }}</td>
                                            <td>{{ $completeOrder->time }}</td>
                                            <td class="orderStatusTd{{ $completeOrder->id }}">
                                                @if ($completeOrder->order_status == 'PENDING')
                                                    <span class="badge badge-pill pending">{{__('PENDING')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'APPROVE')
                                                    <span class="badge badge-pill approve">{{__('APPROVE')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'REJECT')
                                                    <span class="badge badge-pill reject">{{__('REJECT')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'CANCEL')
                                                    <span class="badge badge-pill cancel">{{__('CANCEL')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'PICKUP')
                                                    <span class="badge badge-pill pickup">{{__('PICKUP')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'DELIVERED')
                                                    <span class="badge badge-pill delivered">{{__('DELIVERED')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'COMPLETE')
                                                    <span class="badge badge-pill complete">{{__('COMPLETE')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'PREPARE_FOR_ORDER')
                                                    <span class="badge badge-pill preparre-food">{{__('PREPARE FOR ORDER')}}</span>
                                                @endif

                                                @if ($completeOrder->order_status == 'READY_FOR_ORDER')
                                                    <span class="badge badge-pill ready_for_food">{{__('READY FOR ORDER')}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $currency }}{{ $completeOrder->amount }}</td>
                                            <td>
                                                @if ($completeOrder->payment_status == 1)
                                                    <div class="span">{{__('payment complete')}}</div>
                                                @endif

                                                @if ($completeOrder->payment_status == 0)
                                                    <div class="span">{{__('payment not complete')}}</div>
                                                @endif
                                            </td>
                                            <td>{{ $completeOrder->payment_type }}</td>
                                            <td>
                                                @if ($completeOrder->delivery_type == 'SHOP')
                                                    @if ($completeOrder->order_status == 'COMPLETE' || $completeOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$completeOrder->id}}">
                                                            <option value="COMPLETE" {{ $completeOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$completeOrder->id}})" id="status{{$completeOrder->id}}">
                                                            <option value="PENDING" {{ $completeOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $completeOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="PREPARE_FOR_ORDER" {{ $completeOrder->order_status == 'PREPARE_FOR_ORDER' ? 'selected' : '' }}>{{__('Prepare for order')}}</option>
                                                            <option value="READY_FOR_ORDER" {{ $completeOrder->order_status == 'READY_FOR_ORDER' ? 'selected' : '' }}>{{__('Ready for order')}}</option>
                                                            <option value="REJECT" {{ $completeOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="COMPLETE" {{ $completeOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    @if ($completeOrder->order_status == 'COMPLETE' || $completeOrder->order_status == 'CANCEL')
                                                        <select class="form-control w-auto" disabled name="order_status_change" id="{{$completeOrder->id}}">
                                                            <option value="COMPLETE" {{ $completeOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                            <option value="CANCEL" {{ $completeOrder->order_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                                        </select>
                                                    @else
                                                        <select class="form-control w-auto" onchange="order_status({{$completeOrder->id}})" name="order_status_change" id="status{{$completeOrder->id}}">
                                                            <option value="PENDING" {{ $completeOrder->order_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                                            <option value="APPROVE" {{ $completeOrder->order_status == 'APPROVE' ? 'selected' : '' }}>{{__('Approve')}}</option>
                                                            <option value="REJECT" {{ $completeOrder->order_status == 'REJECT' ? 'selected' : '' }}>{{__('Reject')}}</option>
                                                            <option value="PICKUP" {{ $completeOrder->order_status == 'PICKUP' ? 'selected' : '' }}>{{__('pickup')}}</option>
                                                            <option value="DELIVERED" {{ $completeOrder->order_status == 'DELIVERED' ? 'selected' : '' }}>{{__('Delivered')}}</option>
                                                            <option value="COMPLETE" {{ $completeOrder->order_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                                        </select>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('vendor/order/'.$completeOrder->id) }}" onclick="show_order({{ $completeOrder->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="tfoot-light">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('order_multi_delete','Order')" class="btn btn-primary">
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


