@extends('layouts.app',['activePage' => 'vendor_report'])

@section('title','Vendor Report')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('vendor report')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('vendor report')}}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card p-3">
                    <form action="{{ url('admin/vendor_report') }}" method="post">
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
                    <h4>{{__('vendor report')}}</h4>
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
                                @foreach ($vendors as $vendor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $vendor->name }}</td>
                                        <td class="text_transform_none">{{ $vendor->email_id }}</td>
                                        <td>{{ $vendor->total_order }}</td>
                                        <td>{{ $currency }}{{ $vendor->vendor_earning }}</td>
                                        <td>{{ $currency }}{{ $vendor->compelte_settle }}</td>
                                        <td>{{ $currency }}{{ $vendor->remain_settle }}</td>
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

