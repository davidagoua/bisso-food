@extends('layouts.app',['activePage' => 'tax'])

@section('title','Tax')

@section('content')

<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('Tax')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('Tax')}}</div>
            </div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Tax Management System')}}</h2>
        <p class="section-lead">{{__('Define All Tax.')}}</p>
        <div class="card">
            <div class="card-header">
                @can('tax_add')
                    <div class="w-100">
                        <a href="{{ url('admin/tax/create') }}" class="btn btn-primary float-right">{{__('Add tax')}}</a>
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
                            <th>{{__('tax name')}}</th>
                            <th>{{__('tax type')}}</th>
                            <th>{{__('Tax')}}</th>
                            <th>{{__('Enable')}}</th>
                            @if(Gate::check('tax_edit') && Gate::check('tax_delete'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxs as $tax)
                            <tr>
                                <td>
                                    <input name="id[]" value="{{$tax->id}}" id="{{$tax->id}}" data-id="{{ $tax->id }}" class="sub_chk" type="checkbox" />
                                    <label for="{{$tax->id}}"></label>
                                </td>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{$tax->name}}</td>
                                <td>{{ $tax->type }}</td>
                                @if($tax->type == 'amount')
                                    <td>{{ $currency }}{{ $tax->tax }}</td>
                                @endif
                                @if($tax->type == 'percentage')
                                    <td>{{ $tax->tax }}%</td>
                                @endif
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="status" onclick="change_status('admin/tax',{{ $tax->id }})" {{($tax->status == 1) ? 'checked' : ''}}>
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                @if(Gate::check('tax_edit') && Gate::check('tax_delete'))
                                    <td>
                                        @can('tax_edit')
                                            <a href="{{ url('admin/tax/'.$tax->id.'/edit') }}" class="btn btn-primary" data-toggle="tooltip" title="" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                        @endcan
                                        @can('tax_delete')
                                            <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/tax',{{ $tax->id }},'Tax')">
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
                <input type="button" value="Delete selected" onclick="deleteAll('tax_multi_delete','Tax')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection
