@extends('layouts.app',['activePage' => 'order'])

@section('title','Order')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>{{__('Orders')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('order')}}</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Order page')}}</h2>
        <p class="section-lead">{{__('Order')}}</p>
        <div class="card">
            <div class="card-header">
                <h4>{{__('Order')}}</h4>
            </div>
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th>{{__('Order Id')}}</th>
                            <th>{{__('Vendor name')}}</th>
                            <th>{{__('User Name')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Time')}}</th>
                            <th>{{__('Order Status')}}</th>
                            <th>{{__('Payment status')}}</th>
                            <th>{{__('Payment type')}}</th>
                            <th>{{__('View')}}</th>
                            <th>{{__('Invoice')}}</th>
                            <th>{{__('Delete')}}</th>
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
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$order->order_id}}</td>
                                <td>{{ $order['vendor']->name }}</td>
                                <td>{{ $order['user']->name }}</td>
                                <td>{{ $order->date }}</td>
                                <td>{{ $order->time }}</td>
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

                                    @if ($order->order_status == 'ACCEPT')
                                        <span class="badge badge-pill accept">{{__('ACCEPT')}}</span>
                                    @endif
                                </td>
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
                                    <a href="{{ url('vendor/order/'.$order->id) }}" onclick="show_order({{ $order->id }})" data-toggle="modal" data-target="#view_order">{{__('View Order')}}</a>
                                </td>
                                <th>
                                    <a href="{{ url('admin/order/invoice/'.$order->id) }}" data-toggle="tooltip" title="" data-original-title="{{__('order invoice')}}"><i class="fas fa-file-invoice-dollar"></i></a>
                                </th>
                                <th>
                                    <a href="javascript:void(0);" class="table-action btn btn-danger btn-action" onclick="deleteData('admin/order',{{ $order->id }},'Order')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
