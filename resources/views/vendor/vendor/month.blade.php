@extends('layouts.app', ['activePage' => 'finance_details'])

@section('title', 'Vendor Finance Details')

@section('content')

    <section class="section">

        <div class="section-header">
            <h1>{{ date('F', mktime(0, 0, 0, $month, 1)) }}&nbsp;{{ 'Finance details' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('vendor/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Finance details') }}</div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('monthly finance details') }}</h2>
            <p class="section-lead">{{ __('Finace details') }}</p>
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <h4>{{ date('F', mktime(0, 0, 0, $month, 1)) }}{{ __(' earning') }}</h4>
                            </div>
                            <div class="col text-right">
                                <form class="myform" action="{{ url('vendor/month') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col">
                                            <select name="month" class="form-control">
                                                @for ($m = 1; $m <= 12; $m++)
                                                    <option value="{{ $m }}"
                                                        {{ $m == $month ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="col">
                                            @php
                                                $years = range(\Carbon\Carbon::now()->year, 2019);
                                            @endphp
                                            <select name="year" class="form-control">
                                                @for ($i = 0; $i < count($years); $i++)
                                                    <option value="{{ $years[$i] }}">{{ $years[$i] }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="col text-left">
                                            <button class="btn btn-primary">{{ __('Apply') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="monthFinance" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Total Amount') }}</th>
                                <th>{{ __('Admin Commission') }}</th>
                                <th>{{ __('your earning') }}</th>
                            </tr>
                        </thead>
                        <tbody class="month_finance">
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order['date'] }}</td>
                                    <td>{{ $currency }}{{ $order['amount'] }}</td>
                                    <td>{{ $currency }}{{ $order['admin_commission'] }}</td>
                                    <td>{{ $currency }}{{ $order['vendor_amount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection
