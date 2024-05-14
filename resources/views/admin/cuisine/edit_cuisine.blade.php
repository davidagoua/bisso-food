@extends('layouts.app', ['activePage' => 'cuisine'])

@section('title', 'Edit Cuisine')

@section('content')
    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif
        <div class="section-header">
            <h1>{{ __('cuisines') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ url('admin/cuisine') }}">{{ __('update cuisine') }}</a></div>
                <div class="breadcrumb-item">{{ __('Cuisine') }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{ __('Cuisine menu') }}</h2>
            <p class="section-lead">{{ __('Add, Edit, Manage Cuisine. Admin can add cuisines such as Thai, Continental, Chinese, Italian, etc... And Restaurant owners can select one or more cuisines in their profile') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/cuisine/' . $cuisine->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card p-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label for="Promo code name">{{ __('cuisine') }}</label>
                                <div class="logoContainer">
                                    <img id="image" src="{{ $cuisine->image }}" width="180" height="150">
                                </div>
                                <div class="fileContainer sprite">
                                    <span>{{ __('Image') }}</span>
                                    <input type="file" name="image" value="Choose File" id="previewImage"
                                        data-id="edit" accept=".png, .jpg, .jpeg, .svg">
                                </div>
                                @error('image')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">{{ __('Name of Cuisine') }}</label>
                                <input type="text" name="name" placeholder="{{ __('Enter Cuisine Name') }}"
                                    class="form-control @error('name') is_invalide @enderror" value="{{ $cuisine->name }}"
                                    required="true">
                                @error('name')
                                    <span class="custom_error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('update Cuisine') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
