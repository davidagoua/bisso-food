@extends('layouts.app', ['activePage' => 'role'])

@section('title', 'role')

@section('content')

    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif

        <div class="section-header">
            <h1>{{ __('Roles') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('role') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Role and Permission Management System') }}</h2>
            <p class="section-lead">{{ __('Add, Edit, Update Or Delete User Roles.') }}</p>
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        <a href="{{ url('admin/roles/create') }}"
                            class="btn btn-primary float-right">{{ __('Add New') }}</a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Role name') }}</th>
                                <th>{{ __('permissions') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->title }}</td>
                                    <td>
                                        @forelse ($role->permissions as $permission)
                                            <span class="badge badge-lg badge-primary m-1">{{ $permission->title }}</span>
                                        @empty
                                            <span class="badge  badge-lg badge-warning m-1">{{ __('No Data') }}</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/roles/' . $role->id . '/edit') }}"
                                            class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title=""
                                            data-original-title="{{ __('Edit roles') }}"><i
                                                class="fas fa-pencil-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection
