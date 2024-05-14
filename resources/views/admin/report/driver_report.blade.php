@extends('layouts.app',['activePage' => 'driver_report'])

@section('title','Delivery Person Report')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('delivery person report')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('driver report')}}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card p-3">
                    <form action="{{ url('admin/driver_report') }}" method="post">
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
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{__('Total delivery persons')}}</h4>
                  </div>
                  <div class="card-body">
                    {{ count($drivers) }}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-circle"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{__('Total online delivery persons')}}</h4>
                  </div>
                  <div class="card-body">
                    {{ $total_online_driver }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('delivery person report')}}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered report" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('name')}}</th>
                                    <th>{{__('email id')}}</th>
                                    <th>{{__('total order')}}</th>
                                    <th>{{__('earning')}}</th>
                                    <th>{{__('complete settlement amount')}}</th>
                                    <th>{{__('remaining settlement amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drivers as $driver)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $driver->first_name }}&nbsp;{{ $driver->last_name }}</td>
                                        <td class="text_transform_none">{{ $driver->email_id }}</td>
                                        <td>{{ $driver->total_order }}</td>
                                        <td>{{ $currency }}{{ $driver->driver_earning }}</td>
                                        <td>{{ $currency }}{{ $driver->compelte_settle }}</td>
                                        <td>{{ $currency }}{{ $driver->remain_settle }}</td>
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

