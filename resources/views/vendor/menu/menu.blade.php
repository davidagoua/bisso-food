@extends('layouts.app', ['activePage' => 'vendor_menu'])

@section('title', 'Menu Category')

@section('content')

    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif

        @if (old('old_value') == 'add_menu')
            <script type="text/javascript">
                $(function() {
                    $('#insert_modal').modal();
                    $('#insert_modal').addClass('show');
                });
            </script>
        @endif

        @if (old('old_value') == 'update_menu')
            <script type="text/javascript">
                window.onload = () => {
                    document.querySelector('[data-target="#edit_modal"]').click();
                }
            </script>
        @endif

        <div class="section-header">
            <h1>{{ __('Menu Category') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Menu') }}</div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Menu category management') }}</h2>
            <p class="section-lead">
                {{ __('Add, and categorize the menu adding sub-menus. (Add,Edit & Manage Menu Categories )') }}</p>
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        @can('vendor_menu_access')
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal"
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
                                <th>{{ __('Menu Category ID') }}</th>
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
                                    <td>{{ $menu->id }}</td>
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
                                    <td>
                                        <button type="button" onclick="update_menu({{ $menu->id }})"
                                            class="btn btn-primary" data-toggle="modal" data-target="#edit_modal"><i
                                                class="fas fa-pencil-alt"></i></button>
                                        @can('vendor_submenu_access')
                                            <a href="{{ url('vendor/vendor_menu/' . $menu->id) }}"
                                                class="btn btn-primary btn-action mr-1" data-toggle="tooltip"
                                                data-original-title="{{ __('Add item') }}">
                                                <i class="fas fa-utensils"></i>
                                            </a>
                                        @endcan
                                        <a href="javascript:void(0);" class="table-action btn btn-danger btn-action"
                                            onclick="deleteData('admin/menu',{{ $menu->id }},'Menu')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <input type="button" value="Delete selected" onclick="deleteAll('menu_multi_delete')"
                        class="btn btn-primary">
                </div>
            </div>
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
                        <h5 class="text-primary">{{ __('Add menu') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
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
                        <h5 class="text-primary">{{ __('Update menu') }}</h5>
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
                                placeholder="{{ __('Menu Name') }}" id="update_menu" required
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
