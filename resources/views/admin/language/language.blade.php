@extends('layouts.app',['activePage' => 'language'])

@section('title','Language')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{__('Language')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Language')}}</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">{{__('Language management')}}</h2>
        <p class="section-lead">{{__('Language')}}</p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    @can('language_add')
                        <a href="{{ url('admin/language/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('language Image')}}</th>
                                <th>{{__('Language')}}</th>
                                <th>{{__('Direction')}}</th>
                                <th>{{__('status')}}</th>
                                @if(Gate::check('language_edit'))
                                <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($languages as $language)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $language->image }}" width="50" height="50" class="rounded-lg" alt="">
                                </td>
                                <td>{{ $language->name }}</td>
                                <td>{{$language->direction}}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status"
                                            onclick="change_status('admin/language',{{ $language->id }})"
                                            {{($language->status == 1) ? 'checked' : ''}}>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                @if(Gate::check('language_edit'))
                                <td>
                                    @can('language_edit')
                                        <a href="{{ url('admin/language/'.$language->id.'/edit') }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('Edit Language')}}"><i class="fas fa-pencil-alt"></i></a>
                                    @endcan
                                    <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/language',{{ $language->id }},'Language')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                @endif
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
