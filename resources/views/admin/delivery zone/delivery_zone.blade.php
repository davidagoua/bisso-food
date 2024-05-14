@extends('layouts.app',['activePage' => 'delivery_zone'])

@section('title','Delivery Zone')

@section('content')

<section class="section">
    @if (Session::has('msg'))
    <script>
        var msg = "<?php echo Session::get('msg'); ?>"
            $(window).on('load', function()
            {
                iziToast.success({
                    message: msg,
                    position: 'topRight'
                });
            });
    </script>
    @endif
    <div class="section-header">
        <h1>{{__('Delivery zone')}}</h1>
        <div class="section-header-breadcrumb">
            @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('Delivery zone')}}</div>
            @endif
            @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('Delivery zone')}}</div>
            @endif
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">{{__('Delivery Zone Management')}}</h2>
        <p class="section-lead">{{__('Add, Edit, Manage Delivery Zone.')}}</p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    @can('delivery_zone_add')
                        @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                            <a href="{{ url('admin/delivery_zone/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                        @endif
                        @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                            <a href="{{ url('vendor/deliveryZone/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                        @endif
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    <input name="select_all" value="1" id="master" type="checkbox" />
                                    <label for="master"></label>
                                </th>
                                <th>#</th>
                                <th>{{__('Delivery Zone name')}}</th>
                                <th>{{__('Contact')}}</th>
                                <th>{{__('Admin Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Enable')}}</th>
                                @if(Gate::check('delivery_zone_access'))
                                <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deliveryZones as $deliveryZone)
                            <tr>
                                <td>
                                    <input name="id[]" value="{{$deliveryZone->id}}" id="{{$deliveryZone->id}}" data-id="{{ $deliveryZone->id }}" class="sub_chk" type="checkbox" />
                                    <label for="{{$deliveryZone->id}}"></label>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$deliveryZone->name}}</td>
                                <td>{{$deliveryZone->contact}}</td>
                                <td>{{$deliveryZone->admin_name}}</td>
                                <td class="text_transform_none">{{$deliveryZone->email}}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status"
                                            onclick="change_status('admin/delivery_zone',{{ $deliveryZone->id }})"
                                            {{($deliveryZone->status == 1) ? 'checked' : ''}}>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                @if(Gate::check('delivery_zone_access'))
                                <td class="d-flex justify-content-center">
                                    @can('delivery_zone_access')
                                        @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                                            <a href="{{ url('admin/delivery_zone_area/'.$deliveryZone->id) }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('View Delivery zone')}}"><i class="fas fa-eye"></i></a>
                                        @endif
                                        @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                                            <a href="{{ url('vendor/deliveryZoneArea/'.$deliveryZone->id) }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('View Delivery zone')}}"><i class="fas fa-eye"></i></a>
                                        @endif
                                    @endcan

                                    @can('delivery_zone_edit')
                                        @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                                            <a href="{{ url('admin/delivery_zone/'.$deliveryZone->id.'/edit') }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                        @endif
                                        @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                                            <a href="{{ url('vendor/deliveryZone/'.$deliveryZone->id.'/edit') }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                        @endif
                                    @endcan
                                    @can('delivery_zone_delete')
                                        @if(Auth::user()->load('roles')->roles->contains('title', 'admin'))
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/delivery_zone',{{ $deliveryZone->id }},'Delivery Zone')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                        @if(Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('vendor/deliveryZone',{{ $deliveryZone->id }},'Delivery Zone')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                    @endcan
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('delivery_zone_multi_delete','Delivery Zone')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection
