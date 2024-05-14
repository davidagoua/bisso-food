@extends('layouts.app', ['activePage' => 'vendor'])

@section('title', 'Cutimization submenu')

@section('content')
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif

    <section class="section">
        <div class="section-header">
            <h1>{{ $submenu->name }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a
                        href="{{ url('admin/vendor/' . $submenu->vendor_id) }}">{{ App\Models\Vendor::find($submenu->vendor_id)->name }}</a>
                </div>
                <div class="breadcrumb-item active"><a
                        href="{{ url('admin/menu/' . $submenu->menu_id) }}">{{ App\Models\Menu::find($submenu->menu_id)->name }}</a>
                </div>
                <div class="breadcrumb-item">{{ __('Custimization') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Custimization submenu') }}</h2>
            <p class="section-lead">{{ __('Custimization add, edit') }}</p>
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Customise') }}</h4>
                    <div class="w-100">
                        @can('admin_custimization_type_add')
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                                data-target="#staticBackdrop">{{ __('Add customization type') }}
                            </button>
                        @endcan
                    </div>
                </div>

                @can('admin_custimization_type_access')
                    <div class="card-body">
                        <div id="accordion">
                            @foreach ($custimization_types as $custimization_type)
                                <div class="accordion">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="accordion-header collapsed" role="button" data-toggle="collapse"
                                                aria-expanded="false" href="#panel-body-1{{ $custimization_type['id'] }}">
                                                <h4>{{ $custimization_type->name }}
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            @can('admin_custimization_type_edit')
                                                <span>
                                                    <button type="button" data-toggle="modal" data-target="#edit_modal"
                                                        onclick="update_submenucustimization({{ $custimization_type->id }})"
                                                        class="btn btn-primary">{{ __('Edit') }}</button>
                                                </span>
                                            @endcan
                                            @can('admin_custimization_type_delete')
                                                <span>
                                                    <a href="javascript:void(0);" class="table-action btn btn-primary btn-action"
                                                        onclick="deleteData('admin/customization_type',{{ $custimization_type->id }},'Custimization')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </span>
                                            @endcan
                                        </div>
                                    </div>

                                    <div class="accordion-body collapse" id="panel-body-1{{ $custimization_type['id'] }}"
                                        data-parent="#accordion" style="">
                                        <div class="table-responsive">
                                            <form class="myform" action="{{ url('admin/customization_type/updateItem') }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="custimization_type_id"
                                                    value="{{ $custimization_type->id }}">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td>{{ __('Item name') }}<span class="text-danger">&nbsp;*</span>
                                                            </td>
                                                            <td>{{ __('Price') }}({{ $currency_symbol }})<span
                                                                    class="text-danger">&nbsp;*</span></td>
                                                            <td>{{ __('Default') }}<span class="text-danger">&nbsp;*</span>
                                                            </td>
                                                            <td>{{ __('Active') }}<span class="text-danger">&nbsp;*</span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <table class="table table_custimization text-center">
                                                            <tbody id="{{ 'custom' . $custimization_type->id }}">
                                                                @php
                                                                    $items = json_decode($custimization_type->custimazation_item);
                                                                @endphp
                                                                @if ($items)
                                                                    @foreach ($items as $item)
                                                                        <tr>
                                                                            <td>
                                                                                <input type="text"
                                                                                    name="{{ str_replace(' ', '_', strtolower('name' . $custimization_type->name . '[]')) }}"
                                                                                    placeholder="{{ __('Item name') }}"
                                                                                    value="{{ $item->name }}"
                                                                                    class="form-control">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="price[]"
                                                                                    value="{{ $item->price }}"
                                                                                    placeholder="{{ __('Price') }}"
                                                                                    class="form-control floatTextBox">
                                                                            </td>
                                                                            <td>
                                                                                <input type="radio"
                                                                                    id="{{ 'radio' . $custimization_type->id . '_' . $loop->index }}"
                                                                                    value="{{ $loop->index }}"
                                                                                    name="{{ str_replace(' ', '_', strtolower('isDefault_' . $custimization_type->name)) }}"
                                                                                    {{ $item->isDefault == 1 ? 'checked' : '' }}>
                                                                                <label
                                                                                    for="{{ 'radio' . $custimization_type->id . '_' . $loop->index }}">{{ __('Default') }}</label>
                                                                            </td>
                                                                            <td>
                                                                                <input type="checkbox"
                                                                                    id="{{ 'status' . $custimization_type->id . '_' . $loop->index }}"
                                                                                    name="status{{ $loop->index }}"
                                                                                    {{ $item->status == 1 ? 'checked' : '' }}>
                                                                                <label
                                                                                    for="{{ 'status' . $custimization_type->id . '_' . $loop->index }}">{{ __('Status') }}</label>
                                                                            </td>
                                                                            @if ($loop->iteration == 1)
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-primary update_custimization"
                                                                                        onclick="update_custimization({{ $custimization_type->id }},'{{ str_replace(' ', '_', strtolower($custimization_type->name)) }}')">+</button>
                                                                                </td>
                                                                            @else
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-primary removebtn"><i
                                                                                            class="fas fa-times"></i></button>
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr id="{{ 'custom' . $custimization_type->id }}">
                                                                        <td>
                                                                            <input type="text" required
                                                                                name="{{ str_replace(' ', '_', strtolower('name' . $custimization_type->name . '[]')) }}"
                                                                                placeholder="{{ __('Item name') }}"
                                                                                class="form-control">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" required name="price[]"
                                                                                placeholder="{{ __('Price') }}"
                                                                                class="form-control floatTextBox">
                                                                        </td>
                                                                        <td>
                                                                            <input type="radio" id="1" value="0"
                                                                                name="{{ str_replace(' ', '_', strtolower('isDefault_' . $custimization_type->name)) }}"
                                                                                checked>
                                                                            <label for="1">{{ __('Default') }}</label>
                                                                        </td>
                                                                        <td>
                                                                            <input type="checkbox" id="chkbox"
                                                                                name="{{ 'status0' }}" checked>
                                                                            <label for="chkbox">{{ __('Status') }}</label>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-primary"
                                                                                onclick="update_custimization({{ $custimization_type->id }},'{{ str_replace(' ', '_', strtolower($custimization_type->name)) }}')">+</button>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                        <div class="w-100 text-center">
                                                            <button type="submit"
                                                                class="btn btn-primary">{{ __('Add') }}</button>
                                                        </div>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </section>

    <div class="modal right fade" id="staticBackdrop" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form class="myform" action="{{ url('admin/customization_type') }}" method="post">
                    @csrf
                    <input type="hidden" name="vendor_id" value="{{ $submenu->vendor_id }}">
                    <input type="hidden" name="menu_id" value="{{ $submenu->menu_id }}">
                    <input type="hidden" name="submenu_id" value="{{ $submenu->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="staticBackdropLabel">{{ __('Add customization type') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <label for="">{{ __('Name of Custimization type') }}</label>
                                <input type="text" name="name" required class="form-control" required="true"
                                    style="text-transform : none;">
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <label for="">{{ __('Minimum Item selection') }}</label>
                                <select name="min_item_selection" class="form-control">
                                    @for ($i = 1; $i < 21; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <label for="">{{ __('Maximum Item selection') }}</label>
                                <select name="max_item_selection" class="form-control">
                                    @for ($i = 1; $i < 21; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <div class="control-label">{{ __('Type') }}</div>
                            <div class="custom-switches-stacked mt-2">
                                <label class="custom-switch">
                                    <input type="radio" name="type" value="veg" class="custom-switch-input"
                                        checked="">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('veg') }}</span>
                                </label>
                                <label class="custom-switch">
                                    <input type="radio" name="type" value="non_veg" class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('Non veg') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal right fade" id="edit_modal" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form class="myform" id="update_cutimization_form" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="staticBackdropLabel">{{ __('Update Custimization') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <label for="">{{ __('Name of Custimization type') }}</label>
                                <input type="text" name="name" id="update_name" required class="form-control"
                                    required="true" style="text-transform : none;">
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <label for="">{{ __('Minimum Item selection') }}</label>
                                <select name="min_item_selection" id="update_min_item_selection" class="form-control">
                                    @for ($i = 0; $i < 21; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <label for="">{{ __('Maximum Item selection') }}</label>
                                <select name="max_item_selection" id="update_max_item_selection" class="form-control">
                                    @for ($i = 0; $i < 21; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <div class="control-label">{{ __('Type') }}</div>
                            <div class="custom-switches-stacked mt-2">
                                <label class="custom-switch">
                                    <input type="radio" name="type" id="veg" value="veg"
                                        class="custom-switch-input" checked="">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('veg') }}</span>
                                </label>
                                <label class="custom-switch">
                                    <input type="radio" name="type" id="non_veg" value="non_veg"
                                        class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('Non veg') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
