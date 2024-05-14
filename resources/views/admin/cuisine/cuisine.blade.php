@extends('layouts.app',['activePage' => 'cuisine'])

@section('title','Cuisine')

@section('content')

<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('cuisines')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Cuisine')}}</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Cuisine menu')}}</h2>
        <p class="section-lead">{{__('Add, Edit, Manage Cuisine. Admin can add cuisines such as Thai, Continental, Chinese, Italian, etc... And Restaurant owners can select one or more cuisines in their profile')}}</p>
        <div class="card">
            <div class="card-header">
                @can('cuisine_add')
                    <div class="w-100">
                        <a href="{{ url('admin/cuisine/create') }}" class="btn btn-primary float-right">{{__('add new')}}</a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input name="select_all" value="1" id="master" type="checkbox" />
                                <label for="master"></label>
                            </th>
                            <th>#</th>
                            <th>{{__('Image')}}</th>
                            <th>{{__('Cuisine name')}}</th>
                            <th>{{__('Enable')}}</th>
                            @if(Gate::check('cuisine_edit'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cuisines as $cuisine)
                            <tr>
                                <td>
                                    <input name="id[]" value="{{$cuisine->id}}" id="{{$cuisine->id}}" data-id="{{ $cuisine->id }}" class="sub_chk" type="checkbox" />
                                    <label for="{{$cuisine->id}}"></label>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $cuisine->image }}" class="rounded" width="50" height="50" alt="">
                                </td>
                                <td>{{$cuisine->name}}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status" onclick="change_status('admin/cuisine',{{ $cuisine->id }})" {{($cuisine->status == 1) ? 'checked' : ''}}>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                @if(Gate::check('cuisine_edit'))
                                    <td>
                                        @can('cuisine_edit')
                                            <a href="{{ url('admin/cuisine/'.$cuisine->id.'/edit') }}" class="btn btn-primary btn-action mr-1"><i class="fas fa-pencil-alt"></i></a>
                                        @endcan
                                        @can('cuisine_delete')
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/cuisine',{{ $cuisine->id }},'Cuisine')">
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
                <input type="button" value="Delete selected" onclick="deleteAll('cuisine_multi_delete','Cuisine')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection
