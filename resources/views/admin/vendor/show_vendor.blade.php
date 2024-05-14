@extends('layouts.app', ['activePage' => 'vendor'])

@section('title', 'Show Vendor')

@section('content')
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif

    <section class="section">
        <div class="section-header">
            <h1>{{ $vendor->name }}</h1>
            <div class="section-header-breadcrumb">
                <div class="dropdown d-inline mr-2">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton3"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('More') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-start"
                        style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                        @can('admin_vendor_edit')
                            <a class="dropdown-item"
                                href="{{ url('admin/vendor/' . $vendor->id . '/edit') }}">{{ __('Edit Vendor') }}</a>
                        @endcan
                        @can('admin_vendor_discount_access')
                            <a class="dropdown-item"
                                href="{{ url('admin/vendor_discount/' . $vendor->id) }}">{{ __('Discount') }}</a>
                        @endcan
                        @can('admin_vendor_financeDetails')
                            <a class="dropdown-item"
                                href="{{ url('admin/finance_details/' . $vendor->id) }}">{{ __('finance details') }}</a>
                        @endcan
                        @can('admin_vendor_deliveryTimeslots')
                            <a class="dropdown-item"
                                href="{{ url('admin/edit_delivery_time/' . $vendor->id) }}">{{ __('Edit delivery time') }}</a>
                        @endcan
                        @can('admin_vendor_pickupTimeslots')
                            <a class="dropdown-item"
                                href="{{ url('admin/edit_pick_up_time/' . $vendor->id) }}">{{ __('pick up time') }}</a>
                        @endcan
                        @can('admin_vendor_sellingTimeslots')
                            <a class="dropdown-item"
                                href="{{ url('admin/edit_selling_timeslot/' . $vendor->id) }}">{{ __('selling timeslots') }}</a>
                        @endcan
                        @can('admin_vendor_reviews')
                            <a class="dropdown-item"
                                href="{{ url('admin/rattings/' . $vendor->id) }}">{{ __('review and rattings') }}</a>
                        @endcan
                        @can('admin_vendor_bankDetails')
                            <a class="dropdown-item"
                                href="{{ url('admin/vendor_bank_details/' . $vendor->id) }}">{{ __('add bank details') }}</a>
                        @endcan
                        @can('admin_vendor_password')
                            <a class="dropdown-item"
                                href="{{ url('admin/vendor_change_password/' . $vendor->id) }}">{{ __('change password') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Vendor Management System') }}</h2>

            <p class="section-lead">{{ __('Information about vendor') }}</p>
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="{{ $vendor->image }}" class="rounded-circle profile-widget-picture"
                                width="200px" height="100px">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">{{ __('total order') }}</div>
                                    <div class="profile-widget-item-value">
                                        {{ App\Models\Order::where('vendor_id', $vendor->id)->count() }}</div>
                                </div>
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">{{ __('Pending orders') }}</div>
                                    <div class="profile-widget-item-value">
                                        {{ App\Models\Order::where([['vendor_id', $vendor->id], ['order_status', 'PENDING']])->count() }}
                                    </div>
                                </div>
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">{{ __('Total review') }}</div>
                                    <div class="profile-widget-item-value">{{ $vendor->review }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="profile-widget-name text_transform_none"> {{ $vendor->email_id }} <div
                                    class="text-muted d-inline font-weight-normal">
                                </div>
                            </div>
                            {{ $vendor->map_address }}
                        </div>
                    </div>
                </div>
            </div>
            @can('admin_menu_access')
                <div class="card">
                    <div class="card-header">
                        <div class="w-100 text-right">
                            @can('admin_menu_add')
                                <button type="button" class="btn btn-primary rounded" data-toggle="modal"
                                    data-target="#insert_modal">{{ __('Add menu') }}
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        <input name="select_all" value="1" id="master" type="checkbox" />
                                        <label for="master"></label>
                                    </th>
                                    <th>#</th>
                                    <th>{{ __('Menu Image') }}</th>
                                    <th>{{ __('Menu name') }}</th>
                                    <th>{{ __('Enable') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vendor->menu as $menu)
                                    <tr>
                                        <td>
                                            <input name="id[]" value="{{ $menu->id }}" id="{{ $menu->id }}"
                                                data-id="{{ $menu->id }}" class="sub_chk" type="checkbox" />
                                            <label for="{{ $menu->id }}"></label>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img src="{{ $menu->image }}" class="rounded" width="50" height="50"
                                                alt=""></td>
                                        <td>{{ $menu->name }}</td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" name="status"
                                                    onclick="change_status('admin/menu',{{ $menu->id }})"
                                                    {{ $menu->status == 1 ? 'checked' : '' }}>
                                                <div class="slider"></div>
                                            </label>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <button type="button" onclick="update_menu({{ $menu->id }})"
                                                class="btn btn-primary mr-2" data-toggle="modal" data-target="#edit_modal"><i
                                                    class="fas fa-pencil-alt"></i>
                                            </button>

                                            @can('admin_submenu_access')
                                                <a href="{{ url('admin/menu/' . $menu->id) }}"
                                                    class="btn btn-primary btn-action mr-2" data-toggle="tooltip" title=""
                                                    data-original-title="{{ __('show menu') }}"><i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('admin_menu_delete')
                                                <a href="javascript:void(0);" class="table-action btn btn-danger btn-action"
                                                    onclick="deleteData('admin/menu',{{ $menu->id }},'Menu')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <input type="button" value="Delete selected" onclick="deleteAll('menu_multi_delete','Menu')"
                            class="btn btn-primary">
                    </div>
                </div>
            @endcan
        </div>
    </section>

    <div class="modal right fade" id="insert_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="container-fuild myform" action="{{ url('admin/menu') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_value" value="add_menu">
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                    <div class="modal-header">
                        <h3 class="text-primary">{{ __('Add menu') }}</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image"
                                    accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en">
                                <label class="custom-file-label" for="customFileLang">{{ __('Select file') }}</label>
                            </div>
                            @error('image')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('menu name') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                placeholder="{{ __('Menu Name') }}" required value="{{ old('name') }}">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="{{ __('status') }}">{{ __('Status') }}</label><br>
                            <label class="switch">
                                <input type="checkbox" name="status">
                                <div class="slider"></div>
                            </label>
                        </div>
                        <hr class="my-3">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ __('Close') }}</button>
                            <input type="submit" value="{{ __('Save') }}" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="container-fuild myform" id="update_menu_form" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="old_value" value="update_menu">
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                    <div class="modal-header">
                        <h3>{{ __('Update menu') }}</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="" id="update_image" width="200" height="200"
                                class="rounded-lg p-2" />
                        </div>
                        <div class="form-group mt-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image"
                                    accept=".png, .jpg, .jpeg, .svg" id="customFileLang1" lang="en">
                                <label class="custom-file-label" for="customFileLang1">{{ __('Select file') }}</label>
                            </div>
                            @error('image')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('menu name') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                placeholder="{{ __('menu name') }}" id="update_menu" required
                                value="{{ old('name') }}">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ __('Close') }}</button>
                            <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
