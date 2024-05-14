@extends('layouts.app', ['activePage' => 'vendor_discount'])

@section('title', 'vendor discount')

@section('content')
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif

    <section class="section">
        <div class="section-header">
            <h1>{{ __('vendor discount') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Vendor Discount') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Vendor Discount Management') }}</h2>
            <p class="section-lead">{{ __('Add & Manage Discounts') }}</p>
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        @can('vendor_discount_add')
                            <a href="{{ url('vendor/vendor_discount/create/') }}"
                                class="btn btn-primary float-right">{{ __('Add New') }}</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center" id="datatable">
                            <thead>
                                <tr>
                                    <th>
                                        <input name="select_all" value="1" id="master" type="checkbox" />
                                        <label for="master"></label>
                                    </th>
                                    <th>#</th>
                                    <th>{{ __('Discount image') }}</th>
                                    <th>{{ __('Discount type') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Start to end date') }}</th>
                                    @if (Gate::check('vendor_discount_edit') || Gate::check('vendor_discount_delete'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discounts as $discount)
                                    <tr>
                                        <td>
                                            <input name="id[]" value="{{ $discount->id }}" id="{{ $discount->id }}"
                                                data-id="{{ $discount->id }}" class="sub_chk" type="checkbox" />
                                            <label for="{{ $discount->id }}"></label>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ url('images/upload/' . $discount->image) }}" width="50"
                                                height="50" class="rounded" alt="">
                                        </td>
                                        <td>{{ $discount->type }}</td>
                                        <td>
                                            @if ($discount->type == 'amount')
                                                {{ $currency }}{{ $discount->discount }}
                                            @endif
                                            @if ($discount->type == 'percentage')
                                                {{ $discount->discount }}%
                                            @endif
                                        </td>
                                        <td>{{ $discount->start_end_date }}</td>
                                        @if (Gate::check('vendor_discount_edit') || Gate::check('vendor_discount_delete'))
                                            <td>
                                                @can('vendor_discount_edit')
                                                    <a href="{{ url('vendor/vendor_discount/' . $discount->id . '/edit') }}"
                                                        class="btn btn-primary btn-action mr-1" data-toggle="tooltip"
                                                        title="" data-original-title="Edit"><i
                                                            class="fas fa-pencil-alt"></i></a>
                                                @endcan

                                                @can('vendor_discount_delete')
                                                    <a href="javascript:void(0);" class="table-action btn btn-danger btn-action"
                                                        onclick="deleteData('vendor/vendor_discount',{{ $discount->id }},'Vendor Discount')">
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
                <div class="card-footer">
                    <input type="button" value="Delete selected"
                        onclick="deleteAll('vendor_discount_multi_delete','Vendor Discount')" class="btn btn-primary">
                </div>
            </div>

        </div>
    </section>

@endsection
