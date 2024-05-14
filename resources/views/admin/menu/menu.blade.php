@extends('layouts.app', ['activePage' => 'vendor_menu'])

@section('title', 'Menu')

@section('content')

    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif
        <div class="section-header">
            <h1>{{ $menu->name }}</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item active"><a
                            href="{{ url('admin/vendor/' . $menu->vendor_id) }}">{{ App\Models\Vendor::find($menu->vendor_id)->name }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Menu') }}</div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('Menu') }}</h2>
            <p class="section-lead">{{ __('Menu details') }}</p>
            <div class="card">
                <div class="card-header">
                    <div>
                        <select name="submenu_filter" class="form-control">
                            <option value="all">{{ __('All') }}</option>
                            <option value="excel">{{ __('Data added from excel') }}</option>
                            <option value="panel">{{ __('Data added from panel') }}</option>
                            <option value="veg">{{ __('Veg menu') }}</option>
                            <option value="non_veg">{{ __('Non veg menu') }}</option>
                        </select>
                    </div>
                    <div class="dropdown d-inline mr-2 w-100">
                        <button class="btn btn-primary dropdown-toggle float-right" type="button" id="dropdownMenuButton3"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('More') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-start"
                            style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item"
                                href="{{ url('admin/download_pdf/sub_menu.xlsx') }}">{{ __('Download Sample file') }}</a>
                            <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal"
                                data-target="#exampleModal">{{ __('Import Excel File') }}</a>
                            @can('admin_submenu_add')
                                <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-toggle="modal"
                                    data-target="#insert_model">{{ __('Add submenu') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="display_submenu">
                        @include('vendor.submenu.display_submenu', $menu->submenu)
                    </div>
                </div>
                <div class="card-footer">
                    <input type="button" value="Delete selected" onclick="deleteAll('submenu_multi_delete')"
                        class="btn btn-primary">
                </div>
            </div>
        </div>
    </section>

    <div class="modal right fade" id="insert_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="container-fuild myform" action="{{ url('admin/submenu') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="old_value" value="add_submenu">
                    <input type="hidden" name="vendor_id" value="{{ $menu->vendor_id }}">
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    <div class="modal-header">
                        <h3>{{ __('Add Submenu') }}</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image"
                                    accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en"
                                    accept=".png, .jpg, .jpeg, .svg">
                                <label class="custom-file-label" for="customFileLang">{{ __('Select file') }}</label>
                            </div>
                            @error('image')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Item name') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                placeholder="{{ __('Item Name') }}" value="{{ old('name') }}" required>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Item price') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input class="form-control @error('price') is-invalid @enderror floatTextBox" name="price"
                                type="text" placeholder="{{ __('Item Price') }}" required
                                value="{{ old('price') }}">

                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Description') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <textarea name="description" required class="form-control"></textarea>

                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('type') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <select name="type" class="form-control">
                                <option value="none">{{ __('none') }}</option>
                                <option value="veg">{{ __('Veg') }}</option>
                                <option value="non_veg">{{ __('Non Veg') }}</option>
                            </select>

                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="control-label">{{ __('Quantity reset?') }}<span
                                    class="text-danger">&nbsp;*</span></div>
                            <div class="custom-switches-stacked mt-2">
                                <label class="custom-switch">
                                    <input type="radio" name="qty_reset" value="never" class="custom-switch-input"
                                        checked="">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('never') }}</span>
                                </label>
                                <label class="custom-switch">
                                    <input type="radio" name="qty_reset" value=daily class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('daily') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Total Item reset ?') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input class="form-control @error('item_reset_value') is-invalid @enderror"
                                name="item_reset_value" type="number" min=1 placeholder="{{ __('Item Reset Value') }}"
                                required value="0" disabled>
                            @error('item_reset_value')
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
                <form class="myform" id="update_submenu_form" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="old_value" value="update_submenu">
                    <div class="modal-header">
                        <h3>{{ __('update Submenu') }}</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="text-center">
                            <img src="" id="update_image" width="200" height="182"
                                class="rounded-lg p-2" />
                        </div>

                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image"
                                    accept=".png, .jpg, .jpeg, .svg" id="customFileLang" lang="en"
                                    accept=".png, .jpg, .jpeg, .svg">
                                <label class="custom-file-label" for="customFileLang">{{ __('Select file') }}</label>
                            </div>
                            @error('image')
                                <span class="custom_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Item name') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text"
                                placeholder="{{ __('Item Name') }}" value="{{ old('name') }}" id="update_name"
                                required>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Item price') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input
                                class="form-control floatTextBox text_transform_none @error('price') is-invalid @enderror"
                                name="price" type="text" placeholder="{{ __('Item Price') }}" id="update_price"
                                required value="{{ old('price') }}">

                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Description') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <textarea name="description" id="update_description" required class="form-control"></textarea>

                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('type') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <select name="type" id="type" class="form-control">
                                <option value="none">{{ __('none') }}</option>
                                <option value="veg">{{ __('Veg') }}</option>
                                <option value="non_veg">{{ __('Non Veg') }}</option>
                            </select>

                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="control-label">{{ __('Quantity reset?') }}<span
                                    class="text-danger">&nbsp;*</span></div>
                            <div class="custom-switches-stacked mt-2">
                                <label class="custom-switch">
                                    <input type="radio" name="qty_reset" id="never" value="never"
                                        class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('never') }}</span>
                                </label>
                                <label class="custom-switch">
                                    <input type="radio" name="qty_reset" id="daily" value="daily"
                                        class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">{{ __('daily') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">{{ __('Total Item reset ?') }}<span
                                    class="text-danger">&nbsp;*</span></label>
                            <input class="form-control @error('item_reset_value') is-invalid @enderror"
                                name="item_reset_value" type="number" min=1 placeholder="{{ __('Item Reset Value') }}"
                                required disabled>
                            @error('item_reset_value')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="myform" action="{{ url('admin/submenu_import/' . $menu->vendor_id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Import excel file') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="file" accept=".xlsx" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="Submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
