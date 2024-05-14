@extends('layouts.app',['activePage' => 'refund'])

@section('title','Refund')

@section('content')
<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('Refund')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Refund')}}</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Refund')}}</h2>
        <p class="section-lead">{{__('Refund Management')}}</p>
        <div class="card">
            <div class="card-header">
                <h4>{{__('Refund')}}</h4>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('order id')}}</th>
                            <th>{{__('user name')}}</th>
                            <th>{{__('user bank details')}}</th>
                            <th>{{__('Refund reason')}}</th>
                            <th>{{__('Refund Amount')}}</th>
                            <th>{{__('Refund status')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refunds as $refund)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $refund->order['order_id'] }}</td>
                            <td>{{ $refund->user['name'] }}</td>
                            <td>
                                <a href="" onclick="user_bank_details({{$refund->user_id}})" data-toggle="modal" data-target="#view_order">{{__('User bank Details')}}</a>
                            </td>
                            <td>{{ $refund->refund_reason }}</td>
                            <td>{{ $currency }}{{ $refund->order['amount'] }}</td>
                            <td>
                                <select name="refundStatus" onchange="refundStatus({{$refund->id}})" id={{$refund->id}} class="form-control" {{ $refund->refund_status != 'PENDING' ? 'disabled' : '' }}>
                                    <option value="{{'PENDING'}}" {{ $refund->refund_status == 'PENDING' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                    <option value="{{'ACCEPT'}}" {{ $refund->refund_status == 'ACCEPT' ? 'selected' : '' }}>{{__('Accept')}}</option>
                                    <option value="{{'CANCEL'}}" {{ $refund->refund_status == 'CANCEL' ? 'selected' : '' }}>{{__('Cancel')}}</option>
                                    <option value="{{'COMPLETE'}}" {{ $refund->refund_status == 'COMPLETE' ? 'selected' : '' }}>{{__('Complete')}}</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal right fade" id="view_order" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="staticBackdropLabel">{{__('User bank Details')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th>{{__('user name')}}</th>
                        <td class="user_name"></td>
                    </tr>
                    <tr>
                        <th>{{__('Ifsc Code')}}</th>
                        <td class="ifsc_code"></td>
                    </tr>

                    <tr>
                        <th>{{__('Account name')}}</th>
                        <td class="account_name"></td>
                    </tr>

                    <tr>
                        <th>{{__('Account number')}}</th>
                        <td class="account_number"></td>
                    </tr>

                    <tr>
                        <th>{{__('MICR code')}}</th>
                        <td class="micr_code"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection
