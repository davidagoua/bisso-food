@extends('layouts.app', ['activePage' => 'user'])

@section('title', __('User Wallet'))

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ $user->name }}{{ '  wallet transaction' }}</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('User') }}</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Total Balance') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $currency }}{{ $user->balance }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-money-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('Deposit') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $currency }}{{ $user->deposit }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-money-bill-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('WithDraw') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $currency }}{{ $user->withdraw }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card p-3">
                        <form class="myform" action="{{ url('admin/user/user_wallet/' . $user->id) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-12">
                                    <input type="text" name="date_range" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-6 col-12">
                                    <input type="submit" value="{{ __('apply') }}" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>{{ $user->name }}{{ ' total wallet balance' }}{{ ' ' }}{{ $currency }}{{ $user->balance }}
                    </h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        {{ __('Add New') }}
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center report" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Payment Type') }}</th>
                                    <th>{{ __('Payment Token') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $currency }}{{ $transaction->amount }}</td>
                                        <td>
                                            <span
                                                class="badge badge-pill {{ $transaction->type == 'withdraw' ? 'badge-danger' : 'badge-success' }}">
                                                {{ $transaction->type }}
                                            </span>
                                            @if ($transaction->type == 'deposit')
                                                <span>({{ 'added by' }}{{ ' ' }}{{ $transaction->payment_details['added_by'] }})</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaction->payment_details)
                                                {{ $transaction->payment_details['payment_type'] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaction->payment_details)
                                                {{ $transaction->payment_details['payment_token'] }}
                                            @endif
                                        </td>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="myform" action="{{ url('admin/user/add_wallet') }}" method="post">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Wallet Amount') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name">{{ __('Amount') }}<span class="text-danger">&nbsp;*</span></label>
                                <input type="text" name="amount"
                                    class="form-control @error('amount') is_invalide @enderror floatTextBox"
                                    placeholder="{{ __('Amount') }}" value="{{ old('Wallet Amount') }}" required="">
                                @error('amount')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
