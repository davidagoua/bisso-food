@extends('layouts.app', ['activePage' => 'bank_details'])

@section('title', 'Vendor Printer Setting')

@section('content')
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Printer Details') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('vendor/vendor_home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Printer Details') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Printer Setting') }}</h2>
            <p class="section-lead">{{ __('Printer Setting') }}</p>
            <div class="card">
                <div class="card-header w-100 justify-content-end">
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal"
                        data-target="#exampleModal">
                        Help
                    </button>
                </div>
                <form class="myform" action="{{ url('vendor/update_printer_setting') }}" method="post">
                    @csrf
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="connector_type">{{ __('Connector Type') }}</label>
                                <select name="connector_type"
                                    class="form-control @error('connector_type') is_invalide @enderror">
                                    <option value="windows" {{ $vendor->connector_type == 'windows' ? 'selected' : '' }}>
                                        {{ __('windows') }}</option>
                                    <option value="cups" {{ $vendor->connector_type == 'cups' ? 'selected' : '' }}>
                                        {{ __('cups') }}</option>
                                    <option value="network" {{ $vendor->connector_type == 'network' ? 'selected' : '' }}>
                                        {{ __('network') }}</option>
                                </select>

                                @error('connector_type')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="connector_descriptor">{{ __('Connector Descriptor') }}</label>
                                <input type="text" name="connector_descriptor" required
                                    class="form-control @error('connector_descriptor') is_invalide @enderror"
                                    placeholder="{{ __('Connector Descriptor') }}"
                                    value="{{ $vendor->connector_descriptor }}">

                                @error('connector_descriptor')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="connector_port">{{ __('Connector Port') }}</label>
                                <input type="text" name="connector_port"
                                    class="form-control @error('connector_port') is_invalide @enderror"
                                    placeholder="{{ __('Connector Port') }}" value="{{ $vendor->connector_port }}">

                                @error('connector_port')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="{{ __('Update Printer Setting') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Printer Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="w-100 text-center">
                        <b class="w-100 text-center">
                            {{ 'Connector Type' }}
                        </b>
                    </div>
                    <table class="table">
                        <tr>
                            <td>
                                <b>windows</b>
                            </td>
                            <td>if you are using Windows as your web server.</td>
                        </tr>
                        <tr>
                            <td>
                                <b>cups </b>
                            </td>
                            <td> if you are using Linux or Mac as your web server.</td>
                        </tr>
                        <tr>
                            <td>
                                <b>network </b>
                            </td>
                            <td>if you are using a network printer.</td>
                        </tr>
                    </table>
                    <hr>
                    <div class="w-100 text-center">
                        <b class="w-100 text-center">
                            {{ 'Connector Descriptor' }}
                        </b>
                    </div>
                    <div class="form-group mt-3">
                        the printer name if your connector_type is either windows or cups
                    </div>
                    <div class="form-group">
                        the IP address or Samba URI,
                        <b>e.g: smb://192.168.0.5/PrinterName</b>
                        if your connector_type is network Set connector_port to the open port for the printer, only if your
                        connector_type is network
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
