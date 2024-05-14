@extends('layouts.app', ['activePage' => 'faq'])

@section('title', 'Edit Faq')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>{{ __('Edit faq') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ url('admin/faq') }}">{{ __('faq') }}</a></div>
                <div class="breadcrumb-item">{{ __('Edit a faq') }}</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">{{ __('FAQ') }}</h2>
            <p class="section-lead">{{ __('Edit FAQs') }}</p>
            <form class="container-fuild myform" action="{{ url('admin/faq/' . $faq->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="mT-30">
                            <div class="row">
                                <div class="col-md-12 mb-5">
                                    <label for="type">{{ __('Faq for..') }}<span
                                            class="text-danger">&nbsp;*</span></label>
                                    <select class="form-control" name="type">
                                        <option value="customer" {{ $faq->type == 'customer' ? 'selected' : '' }}>
                                            {{ __('customer') }}</option>
                                        <option value="vendor" {{ $faq->type == 'vendor' ? 'selected' : '' }}>
                                            {{ __('vendor') }}</option>
                                        <option value="driver" {{ $faq->type == 'driver' ? 'selected' : '' }}>
                                            {{ __('driver') }}</option>
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
                                    <textarea name="question" placeholder="{{ __('question') }}" class="form-control" cols="30" rows="10">{{ $faq->question }}</textarea>
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
                                    <textarea name="answer" placeholder="{{ __('answer') }}" class="form-control">{{ $faq->answer }}</textarea>
                                    @error('answer')
                                        <span class="custom_error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"
                                    type="submit">{{ __('update Faq') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>


@endsection
