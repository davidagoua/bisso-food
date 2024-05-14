@extends('layouts.app', ['activePage' => 'delivery_person'])

@section('title', 'Delivery Person')

@section('content')

    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif

        <div class="section-header">
            <h1>{{ __('Delivery person') }}</h1>
            <div class="section-header-breadcrumb">
                @if (Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item active"><a
                            href="{{ url('admin/delivery_person/' . $delivery_person->id) }}">{{ $delivery_person->first_name . ' - ' . $delivery_person->last_name }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Delivery person order history') }}</div>
                @endif
                @if (Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ url('vendor/deliveryPerson') }}">{{ $delivery_person->first_name . ' - ' . $delivery_person->last_name }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Delivery person order history') }}</div>
                @endif
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('This is Delivery Person') }}</h2>
            <p class="section-lead">{{ __('Delivery person page.') }}</p>
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        @if (Auth::user()->load('roles')->roles->contains('title', 'admin'))
                            @if ($delivery_person->vendor_id == null)
                                <a href="{{ url('admin/delivery_person_finance_details/' . $delivery_person->id) }}"
                                    class="btn btn-primary float-right">{{ __('View finance details') }}</a>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Order Id') }}</th>
                                    <th>{{ __('Vendor name') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    @if ($delivery_person->vendor_id == null)
                                        <th>{{ __('Delivery Charge') }}</th>
                                    @endif
                                    <th>{{ __('Order status') }}</th>
                                    @if (Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                                        <th>{{ __('Due Amount') }}</th>
                                        <th>{{ __('Recieved Amount') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $final_amount = 0;
                                @endphp
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->vendor['name'] }}</td>
                                        <td>{{ $order->date }}</td>
                                        <td>{{ $order->time }}</td>
                                        @if ($delivery_person->vendor_id == null)
                                            <td>{{ $order->delivery_charge }}</td>
                                        @endif
                                        <td>{{ $order->order_status }}</td>
                                        @if (Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                                            @if ($order->payment_type == 'COD' && $order->vendor_pending_amount == 0 && $order->order_status == 'COMPLETE')
                                                @php
                                                    $final_amount += $order->amount;
                                                @endphp
                                                <td>{{ $currency }}{{ $order->amount }}</td>
                                                <td>
                                                    <a href="{{ url('vendor/deliveryPerson/pending_amount/' . $order->id) }}"
                                                        class="text-danger">{{ __('Pending Amount') }}</a>
                                                </td>
                                            @else
                                                <td>{{ $currency }}{{ 00 }}</td>
                                                <td>
                                                    <span class="text-primary">{{ __('Recieved Amount') }}</span>
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th>{{ $currency }}{{ $final_amount }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
