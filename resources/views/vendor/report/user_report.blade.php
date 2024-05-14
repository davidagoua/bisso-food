@extends('layouts.app',['activePage' => 'user_report'])

@section('title','User Report')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('user report')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('user report')}}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card p-3">
                    <form action="{{ url('vendor/user_report') }}" method="post">
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
                    <div class="card-icon bg-warning"><i class="fas fa-user-friends"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('Total user')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ count($users) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('Total active users')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $active_user }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger"><i class="fas fa-user-lock"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{__('Total block users')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $block_user }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('user report')}}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered report" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('user profile')}}</th>
                                    <th>{{__('email')}}</th>
                                    <th>{{__('contact number')}}</th>
                                    <th>{{__('Total order')}}</th>
                                    <th>{{__('Remaining payment')}}</th>
                                    <th>{{__('Active / Deactive')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a class="nav-link active" href="{{ url('admin/user/'.$user->id) }}">{{ $user->name }}</a></td>
                                        <td>
                                            <img src="{{ $user->image }}" width="50" height="50" class="rounded-circle" alt="">
                                        </td>
                                        <td class="text_transform_none">{{ $user->email_id }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ count($user->total_order) }}</td>
                                        <td>{{ $currency }}{{ $user->remain_amount }}</td>
                                        <td>
                                            @if ($user->status == 1)
                                                <div class="badge badge-success">{{__('Active')}}</div>
                                            @else
                                                <div class="badge badge-danger">{{__('Deactive')}}</div>
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

