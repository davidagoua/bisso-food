@extends('layouts.app',['activePage' => 'promo_code'])

@section('title','promo code')

@section('content')

<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('Promo code')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Promo Code')}}</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">{{__('Promo code Management system')}}</h2>
        <p class="section-lead">{{__('Assign the best deals and promo codes for the Users.')}}</p>
        <div class="card">
            <div class="card-header">
                <div class="w-100">
                    @can('promo_code_add')
                        <a href="{{ url('admin/promo_code/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        <input name="select_all" value="1" id="master" type="checkbox" />
                                        <label for="master"></label>
                                    </th>
                                    <th>#</th>
                                    <th>{{__('Promo code image')}}</th>
                                    <th>{{__('Promo code name')}}</th>
                                    <th>{{__('Promo code')}}</th>
                                    <th>{{__('Start end period  (MM_DD_YYYY)')}}</th>
                                    <th>{{__('Enable')}}</th>
                                    @if(Gate::check('promo_code_edit') && Gate::check('promo_code_delete'))
                                        <th>{{__('Action')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($PromoCodes as $PromoCode)
                                <tr>
                                    <td>
                                        <input name="id[]" value="{{$PromoCode->id}}" id="{{$PromoCode->id}}" data-id="{{ $PromoCode->id }}" class="sub_chk" type="checkbox" />
                                        <label for="{{$PromoCode->id}}"></label>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ $PromoCode->image }}" width="50" height="50" class="rounded" alt="">
                                    </td>
                                    <td>{{$PromoCode->name}}</td>
                                    <td>{{$PromoCode->promo_code}}</td>
                                    <td>{{$PromoCode->start_end_date}}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" name="status" onclick="change_status('admin/promo_code',{{ $PromoCode->id }})" {{($PromoCode->status == 1) ? 'checked' : ''}}>
                                            <div class="slider"></div>
                                        </label>
                                    </td>
                                    @if(Gate::check('promo_code_edit') && Gate::check('promo_code_delete'))
                                        <td>
                                            @can('promo_code_edit')
                                                <a href="{{ url('admin/promo_code/'.$PromoCode->id.'/edit') }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                            @endcan
                                            @can('promo_code_delete')
                                                <a href="javascript:void(0);" class="table-action ml-2 btn btn-danger btn-action" onclick="deleteData('admin/promo_code',{{ $PromoCode->id }},'Promo Code')">
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
                </div>
            </div>
            <div class="card-footer">
                <input type="button" value="Delete selected" onclick="deleteAll('promo_code_multi_delete','Promo Code')" class="btn btn-primary">
            </div>
        </div>
    </div>
</section>

@endsection
