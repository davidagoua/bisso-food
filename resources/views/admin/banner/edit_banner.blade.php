@extends('layouts.app', ['activePage' => 'banner'])

@section('title', 'Edit Banner')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Edit banner') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ url('admin/banner') }}">{{ __('Banner') }}</a></div>
                <div class="breadcrumb-item">{{ __('Edit a banner') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('Banner Management System') }}</h2>
            <p class="section-lead">{{ __('Edit your banner') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/banner/' . $banner->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card p-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="Promo code name">{{ __('Banner image') }}</label>
                                <div class="logoContainer">
                                    <img id="image" src="{{ $banner->image }}" width="180" height="150">
                                </div>
                                <div class="fileContainer sprite">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" name="image" data-id="edit" value="Choose File"
                                        id="previewImage" accept=".png, .jpg, .jpeg, .svg">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">{{ __('Name of Banner') }}<span
                                        class="text-danger">&nbsp;*</span></label>
                                <input type="text" id="update_name" placeholder="{{ __('Enter Banner Name') }}"
                                    name="name" value="{{ $banner->name }}" class="form-control" required="true">
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('Update banner') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
