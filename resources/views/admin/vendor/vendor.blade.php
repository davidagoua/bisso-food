@extends('layouts.app',['activePage' => 'vendor'])

@section('title','Vendor')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>{{__('Vendor')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Vendor')}}</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Vendor Management System')}}</h2>
        <p class="section-lead">{{__('Add, Edit, Manage Vendors.')}}</p>
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {{ session('error') }}
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                @can('admin_vendor_add')
                    <div class="w-100">
                        <a href="{{ url('admin/vendor/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                    </div>
                @endcan
            </div>
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th>{{__('vendor profile')}}</th>
                            <th>{{__('vendor name')}}</th>
                            <th>{{__('Location')}}</th>
                            <th>{{__('Email')}}</th>
                            <th>{{__('Enable')}}</th>
                            @if(Gate::check('admin_vendor_edit'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $vendor)
                        <tr>
                            <td>
                                <input name="id[]" value="{{$vendor->id}}" id="{{$vendor->id}}" data-id="{{ $vendor->id }}" class="sub_chk" type="checkbox" />
                                <label for="{{$vendor->id}}"></label>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $vendor->image }}" width="50" height="50" class="rounded" alt="">
                            </td>
                            <th>{{$vendor->name}}</th>
                            <td>{{$vendor->address}}</td>
                            <td class="text_transform_none">{{$vendor->email_id}}</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" name="status" onclick="change_status('admin/vendor',{{ $vendor->id }})" {{($vendor->status == 1) ? 'checked' : ''}}>
                                    <div class="slider"></div>
                                </label>
                            </td>

                            @if(Gate::check('admin_vendor_edit'))
                                <td class="d-flex justify-content-center">
                                    <a href="{{ url('admin/vendor/'.$vendor->id) }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('show vendor')}}"><i class="fas fa-eye"></i></a>
                                    @can('admin_vendor_edit')
                                        <a href="{{ url('admin/vendor/'.$vendor->id.'/edit') }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                    @endcan

                                    @can('admin_vendor_delete')
                                        <a href="javascript:void(0);" class="table-action btn btn-danger btn-action mr-1" onclick="deleteData('admin/vendor',{{ $vendor->id }},'Vendor')" data-toggle="tooltip" title="" data-original-title="{{__('Delete')}}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endcan

                                    <a href="{{ url('admin/login-to-vendor/'. $vendor->id) }}" class="btn btn-primary btn-action" data-toggle="tooltip" data-original-title="{{__('Login')}}">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('vendor_multi_delete','Vendor')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection
