@extends('layouts.app', ['activePage' => 'faq'])

@section('title', 'Create A Faq')

@section('content')


    <section class="section">
        <div class="section-header">
            <h1>{{ __('Create new faq') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ url('admin/faq') }}">{{ __('faq') }}</a></div>
                <div class="breadcrumb-item">{{ __('create a faq') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('This is faq') }}</h2>
            <p class="section-lead">{{ __('create faq') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/faq') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="mT-30">
                            <div class="row">
                                <div class="col-md-12 mb-5">
                                    <label for="type">{{ __('Faq for..') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <select class="form-control" name="type">
                                        <option value="customer">{{ __('customer') }}</option>
                                        <option value="vendor">{{ __('vendor') }}</option>
                                        <option value="driver">{{ __('driver') }}</option>
                                    </select>

                                    @error('type')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-5">
                                    <label for="question">{{ __('question') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <textarea name="question" required placeholder="{{ __('Question') }}" class="form-control" cols="30"
                                        rows="10"></textarea>
                                    @error('question')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-5">
                                    <label for="{{ __('answer') }}">{{ __('answer') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <textarea name="answer" required placeholder="{{ __('Answer') }}" class="form-control">{{ old('answer') }}</textarea>
                                    @error('answer')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" type="submit">{{ __('Add Faq') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
