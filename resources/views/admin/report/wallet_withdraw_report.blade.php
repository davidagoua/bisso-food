@extends('layouts.app',['activePage' => 'wallet_transaction_report'])

@section('title','Wallet WithDraw Report')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('Wallet WithDraw Report')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('Wallet WithDraw Report')}}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card p-3">
                    <form action="{{ url('admin/wallet_withdraw_report') }}" method="post">
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

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Wallet Transaction Report')}}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered report" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('User')}}</th>
                                    <th>{{__('Order Id')}}</th>
                                    <th>{{__('vendor name')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <th>
                                            <a href="{{ url('admin/user/user_wallet/'.$transaction->user['id']) }}">{{ $transaction->user['name'] }}</a>
                                        </th>
                                        <td>
                                            @if (isset($transaction->order['order_id']))
                                                {{ $transaction->order['order_id'] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($transaction->order['vendor']->name))
                                                {{ $transaction->order['vendor']->name }}
                                            @endif
                                        </td>
                                        <td>{{ $currency }}{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->date }}</td>
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
