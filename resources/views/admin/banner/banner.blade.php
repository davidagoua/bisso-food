@extends('layouts.app',['activePage' => 'banner'])

@section('title','Banner')

@section('content')

<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('Banner')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('Banner')}}</div>
            </div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Banner Management System')}}</h2>
        <p class="section-lead">{{__('Manage Your Banners Within App.')}}</p>
        <div class="card">
            <div class="card-header">
                @can('banner_add')
                    <div class="w-100">
                        <a href="{{ url('admin/banner/create') }}" class="btn btn-primary float-right">{{__('Add banner')}}</a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th>{{__('banner image')}}</th>
                            <th>{{__('banner name')}}</th>
                            <th>{{__('Enable')}}</th>
                            @if(Gate::check('banner_edit') && Gate::check('banner_delete'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $banner)
                            <tr>
                                <td>
                                    <input name="id[]" value="{{$banner->id}}" id="{{$banner->id}}" data-id="{{ $banner->id }}" class="sub_chk" type="checkbox" />
                                    <label for="{{$banner->id}}"></label>
                                </td>
                                <th>{{ $loop->iteration }}</th>
                                <td>
                                    <img src="{{ $banner->image }}" width="50" height="50" class="rounded" alt="">
                                </td>
                                <td>{{$banner->name}}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status" onclick="change_status('admin/banner',{{ $banner->id }})" {{($banner->status == 1) ? 'checked' : ''}}>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                @if(Gate::check('banner_edit') && Gate::check('banner_delete'))
                                    <td>
                                        @can('banner_edit')
                                            <a href="{{ url('admin/banner/'.$banner->id.'/edit') }}" class="btn btn-primary" data-toggle="tooltip" title="" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                        @endcan
                                        @can('banner_delete')
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/banner',{{ $banner->id }},'Banner')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('banner_multi_delete','Banner')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection

