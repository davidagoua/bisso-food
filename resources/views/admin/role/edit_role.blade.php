@extends('layouts.app', ['activePage' => 'role'])

@section('title', 'Edit Role')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('edit role') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ url('admin/roles') }}">{{ __('role') }}</a></div>
                <div class="breadcrumb-item">{{ __('edit a role') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Role and Permission Management System') }}</h2>
            <p class="section-lead">{{ __('edit role') }}</p>
            <div class="card">
                <div class="card-body">
                    <form class="container-fuild myform" action="{{ url('admin/roles/' . $role->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="role">{{ __('role title') }}</label>
                                <input type="text" name="title" value="{{ $role->title }}" readonly
                                    class="form-control @error('title') is_invalide @enderror"
                                    placeholder="{{ __('role name') }}" value="{{ old('title') }}" required="">
                                @error('title')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="role">{{ __('Permissions') }}</label>
                                <select name="permissions[]" class="select2 form-control"
                                    {{ $role->title == 'admin' ? 'disabled' : '' }}
                                    {{ $role->title == 'vendor' ? 'disabled' : '' }} multiple>
                                    @foreach ($permissions as $per)
                                        <option value="{{ $per->id }}"
                                            {{ in_array($per->id, old('permissions', [])) || (isset($role) && $role->permissions->contains($per->id)) ? 'selected' : '' }}>
                                            {{ $per['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit">{{ __('Update role') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
