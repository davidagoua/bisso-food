@extends('layouts.app', ['activePage' => 'finance_details'])

@section('title', 'Delivery Person Detail Finance Details')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ $driver->first_name }}&nbsp;{{ 'Finance details' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ url('delivery person detail/delivery person detail_home') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Finance details') }}</div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ $driver->first_name . '  ' }}{{ $driver->last_name }}{{ __("'s finance details") }}
            </h2>
            <p class="section-lead">{{ __('Finace details') }}</p>

            <div class="card">
                <div class="card-header text-right">
                    <h4>{{ __('Settlements') }}</h4>
                    <span class="badge badge-danger">{{ __('rs delivery person gives to admin') }}</span>&nbsp;
                    <span class="badge badge-success">{{ __('rs admin gives to delivery person') }}</span>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('duration') }}</th>
                                <th>{{ __('Order count') }}</th>
                                <th>{{ __('Total amount') }}</th>
                                <th>{{ __('Admin Earning') }}</th>
                                <th>{{ __('delivery person earning') }}</th>
                                <th>{{ __('Settles amount') }}</th>
                                <th>{{ __('Settles') }}</th>
                                <th>{{ __('View') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($settels as $settel)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td id="duration{{ $loop->iteration }}">{{ $settel['duration'] }}</td>
                                    <td>{{ $settel['d_total_task'] }}</td>
                                    <td>{{ $currency }}{{ $settel['d_total_amount'] }}</td>
                                    <td>{{ $currency }}{{ $settel['admin_earning'] }}</td>
                                    <td>{{ $currency }}{{ $settel['driver_earning'] }}</td>
                                    <td>
                                        @if ($settel['d_balance'] > 0)
                                            {{-- admins will take --}}
                                            <span
                                                class="badge badge-success">{{ $currency }}{{ abs($settel['d_balance']) }}</span>
                                        @else
                                            {{-- driver will take --}}
                                            <span
                                                class="badge badge-danger">{{ $currency }}{{ abs($settel['d_balance']) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (abs($settel['d_balance']) > 0)
                                            <form class="myform" action="{{ url('admin/driver_make_payment') }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="duration" value="{{ $settel['duration'] }}">
                                                <input type="hidden" name="driver" value="{{ $driver->id }}">
                                                <button type="submit" class="btn btn-primary">{{ __('Settle') }}</button>
                                            </form>
                                        @else
                                            <button type="submit"
                                                class="btn btn-primary disabled">{{ __('Settle') }}</button>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary"
                                            onclick="show_driver_settle_details({{ $loop->iteration }},{{ $driver->id }})"
                                            data-toggle="modal" data-target="#exampleModal">
                                            {{ __('Show settlement details') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Show settlement details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body details_body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
