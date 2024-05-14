@extends('layouts.app', ['activePage' => 'setting'])

@section('title', 'static pages')

@section('content')


    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Static pages') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ url('admin/setting') }}">{{ __('Setting') }}</a></div>
                <div class="breadcrumb-item">{{ __('Static pages') }}</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills nav-pills-rose nav-pills-icons flex-column" role="tablist">
                                <li class="nav-item active">
                                    <a class="nav-link mt-1 w-100 h-100 show active" data-toggle="tab" href="#link110"
                                        role="tablist" aria-expanded="true">
                                        {{ __('Privacy Policy') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link111" role="tablist"
                                        aria-expanded="false">
                                        {{ __('Terms and condition') }}
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link113" role="tablist"
                                        aria-expanded="false">
                                        {{ __('Help') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link112" role="tablist"
                                        aria-expanded="false">
                                        {{ __('About US') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mt-1 w-100 h-100" data-toggle="tab" href="#link114" role="tablist"
                                        aria-expanded="false">
                                        {{ __('Company Details') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="link110">
                            <form class="myform" action="{{ url('admin/update_privacy') }}" method="post">
                                @csrf
                                <div class="card" id="settings-card">
                                    <div class="card-header">
                                        <h4>{{ __('Privacy policy') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row align-items-center">
                                            <div class="form-group col-12">
                                                <label>{{ __('Privacy policy') }}</label>
                                                <textarea class="form-control privacy_policy" name="privacy_policy">{{ $setting->privacy_policy }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-whitesmoke text-md-right">
                                        <button class="btn btn-primary" id="save-btn">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="link111">
                            <form class="myform" action="{{ url('admin/update_terms') }}" method="post">
                                @csrf
                                <div class="card" id="settings-card">
                                    <div class="card-header">
                                        <h4>{{ __('Terms and condition') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row align-items-center">
                                            <div class="form-group col-12">
                                                <label>{{ __('Terms and condition') }}</label>
                                                <textarea class="form-control textarea_editor_term" name="terms_and_condition">{{ $setting->terms_and_condition }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-whitesmoke text-md-right">
                                        <button class="btn btn-primary" id="save-btn">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="link113">
                            <form class="myform" action="{{ url('admin/update_help') }}" method="post">
                                @csrf
                                <div class="card" id="settings-card">
                                    <div class="card-header">
                                        <h4>{{ __('Help') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row align-items-center">
                                            <div class="form-group col-12">
                                                <label>{{ __('Help') }}</label>
                                                <textarea class="form-control textarea_editor" name="help">{{ $setting->help }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-whitesmoke text-md-right">
                                        <button class="btn btn-primary" id="save-btn">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="link112">
                            <form class="myform" action="{{ url('admin/update_about') }}" method="post">
                                @csrf
                                <div class="card" id="settings-card">
                                    <div class="card-header">
                                        <h4>{{ __('About Us') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row align-items-center">
                                            <div class="form-group col-12">
                                                <label>{{ __('About Us') }}</label>
                                                <textarea class="form-control textarea_editor" name="about_us">{{ $setting->about_us }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-whitesmoke text-md-right">
                                        <button class="btn btn-primary" id="save-btn">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="link114">
                            <form class="myform" action="{{ url('admin/update_company_details') }}" method="post">
                                @csrf
                                <div class="card" id="settings-card">
                                    <div class="card-header">
                                        <h4>{{ __('About Us') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row align-items-center">
                                            <div class="form-group col-12">
                                                <label>{{ __('About Us') }}</label>
                                                <textarea class="form-control textarea_editor" name="company_details">{{ $setting->company_details }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-whitesmoke text-md-right">
                                        <button class="btn btn-primary" id="save-btn">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
