@extends('layouts.app',['activePage' => 'user'])

@section('title','user')

@section('content')

<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('users')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('User')}}</div>
            </div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('User management panel')}}</h2>
        <p class="section-lead">{{__('Add, Edit, Manage Users.')}}</p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    <a href="{{ url('admin/user/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                </div>
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
                            <th>{{__('user name')}}</th>
                            <th>{{__('Role')}}</th>
                            <th>{{__('phone')}}</th>
                            <th>{{__('email')}}</th>
                            <th>{{__('enable')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <input name="id[]" value="{{$user->id}}" id="{{$user->id}}" data-id="{{ $user->id }}" class="sub_chk" type="checkbox" />
                                    <label for="{{$user->id}}"></label>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @foreach ($user['roles'] as $item)
                                        <span class="badge badge-success">{{ $item->title }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $user->phone }}</td>
                                <td class="text_transform_none">{{ $user->email_id }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status" onclick="change_status('admin/user',{{ $user->id }})" {{($user->status == 1) ? 'checked' : ''}}>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                @if (Gate::check('user_edit') && Gate::check('user_access') && Gate::check('user_delete'))
                                <td class="d-flex">
                                    @can('user_edit')
                                        <a href="{{ url('admin/user/'.$user->id.'/edit') }}" class="btn btn-primary btn-action mr-1 {{ $user->id == 1 ? 'disabled' : '' }}" data-toggle="tooltip" title="" data-original-title="{{__('Edit user')}}"><i class="fas fa-pencil-alt"></i></a>
                                    @endcan
                                    @can('user_access')
                                        <a href="{{ url('admin/user/'.$user->id) }}" data-toggle="tooltip" title="" data-original-title="{{__('show user profile')}}" class="btn btn-primary btn-action mr-1 {{ $user->id == 1 ? 'disabled' : '' }}"><i class="fas fa-eye"></i></a>
                                    @endcan
                                    @can('user_delete')
                                        <a href="javascript:void(0);" class="table-action btn btn-danger btn-action {{ $user->id == 1 ? 'disabled' : '' }}" onclick="deleteData('admin/user',{{ $user->id }},'User')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endcan
                                    <a href="{{ url('admin/user/user_wallet/'.$user->id) }}" data-toggle="tooltip" title="" data-original-title="{{__('User Wallet')}}" class="btn btn-info btn-action ml-1 {{ $user->id == 1 ? 'disabled' : '' }}"><i class="fas fa-wallet"></i></a>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('user_multi_delete','user')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>
@endsection
