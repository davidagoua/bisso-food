@extends('layouts.app', ['activePage' => 'delivery_zone'])

@section('title', 'show delivery person')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Show delivery zone') }}</h1>
            <div class="section-header-breadcrumb">
                @if (Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item"><a href="{{ url('admin/delivery_zone') }}">{{ __('Delivery zone') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Show Delivery zone') }}</div>
                @endif
                @if (Auth::user()->load('roles')->roles->contains('title', 'vendor'))
                    <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item"><a href="{{ url('vendor/deliveryZone') }}">{{ __('Delivery zone') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Show Delivery zone') }}</div>
                @endif
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Delivery zone area management') }}</h2>
            <p class="section-lead">{{ __('Delivery zone Area.') }}</p>

            <input type="hidden" name="delivery_zone_id" id="delivery_zone_id" value="{{ $delivery_zone->id }}">
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="card w-75">
                                <div class="card-body">
                                    <h5 class="text-dark">{{ $delivery_zone->name }}</h5>
                                    <p>{{ __('Zone admin name : ') }} <span
                                            class="font-weight-bold">{{ $delivery_zone->admin_name }}</span></p>
                                    <p>{{ __('number of delivery person  : ') }} <span
                                            class="font-weight-bold">{{ count($delivery_persons) }}</span></p>
                                    <a href="javascript:void(0);" class="btn btn-primary float-right" data-toggle="modal"
                                        data-target="#show_delivery_person">{{ __('View delivery person') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card w-75">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-12 text-right">
                                            <button type="button" class="btn btn-primary p-1" data-toggle="modal"
                                                data-target="#staticBackdrop">{{ __('Add Area') }}</button>
                                        </div>
                                    </div>

                                    @foreach ($areas as $area)
                                        @if ($loop->iteration == 1)
                                            @php
                                                $first = $area;
                                            @endphp
                                        @break
                                    @endif
                                @endforeach
                                <ul class="nav nav-pills nav-pills-rose nav-pills-icons flex-column" role="tablist">
                                    @foreach ($areas as $area)
                                        <li class="nav-item mt-2">
                                            <div class="nav-link w-100 h-100 {{ $loop->iteration == 1 ? 'active show' : '' }}"
                                                onclick="delivery_zone_area_map({{ $area->id }})" data-toggle="tab"
                                                href="#link110" role="tablist">
                                                {{ $area->name }}
                                                <span>
                                                    <a class="float-right text-light" data-toggle="modal"
                                                        data-target="#edit_delivery_zone"
                                                        onclick="edit_delivery_person({{ $area->id }})">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="ml-2 float-right text-light"
                                                        onclick="deleteData('admin/delivery_zone_area',{{ $area->id }},'Delivery Zone Area')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="card w-100 p-1">
                            <div class="card-header">
                                <h4 class="text-primary">{{ __('MAP') }}</h4>
                            </div>
                            <div class="card-body" style="height: 750px">
                                @if (isset($first))
                                    <div class="tab-content">
                                        <div class="tab-pane active show" id="link110">
                                            <div class="form-group">
                                                <div id="abcd" style="border: 1px solid black; height:650px;">
                                                </div>
                                                <input type="hidden" id="show_lat" value="{{ $first->lat }}">
                                                <input type="hidden" id="show_lang" value="{{ $first->lang }}">
                                                <input type="hidden" id="show_name" value="{{ $first->name }}">
                                                <input type="hidden" id="show_radius" value="{{ $first->radius }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="show_delivery_person" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __('Delivery person') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0"
                    width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Delivery person profile') }}</th>
                            <th>{{ __('Delivery person name') }}</th>
                            <th>{{ __('status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($delivery_persons as $delivery_person)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $delivery_person->image }}" width="50" height="50"
                                        alt="">
                                </td>
                                <td>{{ $delivery_person->first_name }}&nbsp;{{ $delivery_person->last_name }}</td>
                                <td>{{ $delivery_person->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __('Add area') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-dark alert-dismissible fade show hide show_alert" role="alert">
                    <strong class="display"></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">{{ __('Name of area') }}</label>
                        <input type="text" name="area_name" id="name" class="form-control">
                    </div>
                </div>
                @if (Auth::user()->load('roles')->roles->contains('title', 'admin'))
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <label for="">{{ __('Vendor') }}</label>
                            <select name="vendor_id[]" class="select2" multiple>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="row pt-3">
                    <div class="col-md-6">
                        <label for="">{{ __('Radius of area') }}</label>
                        <input type="number" min=1 name="radius" id="radius" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <div class="pac-card col-md-12 mb-3" id="pac-card">
                            <label for="pac-input">{{ __('Location based on latitude/lontitude') }}</label>
                            <div id="pac-container">
                                <input id="pac-input" type="text" name="map_address" class="form-control"
                                    placeholder="Enter a location" />
                                <input type="hidden" name="lat" value="{{ 22.3039 }}" id="lat">
                                <input type="hidden" name="lang" value="{{ 70.8022 }}" id="lang">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col w-100 text-right">
                        <button class="btn btn-primary"
                            onclick="remove_coordinates()">{{ __('clear area') }}</button>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-md-12">
                        <div id="map" style="height: 650px;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" onclick="add_area()">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="edit_delivery_zone" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Area') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" name="delivery_zone_area_id">
            <form class="myform" id="update_zone_area_form" method="post">
                <div class="modal-body">
                    <div class="alert alert-dark alert-dismissible fade show hide show_alert" role="alert">
                        <strong class="display"></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">{{ __('Name of area') }}</label>
                            <input type="text" name="name" id="edit_name" class="form-control">
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <label for="">{{ __('Radius of area') }}</label>
                            <input type="number" min=1 name="radius" id="edit_radius" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <div class="edit-pac-card col-md-12 mb-3" id="edit-pac-card">
                                <label for="edit-pac-input">{{ __('Location based on latitude/lontitude') }}</label>
                                <div id="pac-container">
                                    <input id="edit_pac-input" type="text" name="map_address"
                                        class="form-control" placeholder="Enter a location" />
                                    <input type="hidden" name="lat" value="" id="edit_lat">
                                    <input type="hidden" name="lang" value="" id="edit_lang">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-12">
                            <label for="">{{ __('Vendor') }}</label>
                            <select name="vendor_id[]" id="edit_vendor_id" class="form-control select2" multiple>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div id="edit_map" style="height: 650px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" onclick="update_area()"
                        class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('js/delivery_person_map.js') }}"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ App\Models\GeneralSetting::first()->map_key }}&callback=initMap&libraries=places&v=weekly"
    defer></script>
@endsection
