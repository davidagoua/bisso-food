@extends('layouts.app', ['activePage' => 'notification_template'])

@section('title', 'Notification Template')

@section('content')

    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif
        <div class="section-header">
            <h1>{{ __('Notification Template') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ __('Notification template') }}</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            if (session::has('locale')) {
                                $lang = session()->get('locale');
                                if ($lang == 'spanish') {
                                    $item = App\Models\NotificationTemplate::where('title', 'book order')->first();
                                    $item->mail_content = $item->spanish_mail_content;
                                    $item->notification_content = $item->spanish_notification_content;
                                } else {
                                    $item = App\Models\NotificationTemplate::where('title', 'book order')->first();
                                }
                            } else {
                                $item = App\Models\NotificationTemplate::where('title', 'book order')->first();
                            }
                        @endphp
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <ul class="nav nav-pills nav-pills-rose nav-pills-icons flex-column" role="tablist">
                                        @foreach ($data as $value)
                                            <li class="nav-item">
                                                <a class="nav-link mt-1 w-100 h-100 {{ $loop->iteration == 1 ? 'active show' : '' }}"
                                                    onclick="notificationTemplateEdit({{ $value->id }})"
                                                    data-toggle="tab" href="#link110" role="tablist">{{ $value->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-8">
                                    <div class="tab-content">
                                        <div class="tab-pane active show" id="link110">
                                            <form class="myform" method="post"
                                                action="{{ 'notification_template/' . $item->id }}"
                                                class="edit_notification_template_form">
                                                @csrf
                                                @method('PUT')
                                                <div class="row">
                                                    <div class="col">
                                                        <h4 id="heading">{{ $item->title }}</h4>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr">{{ __('Title') }}</label>
                                                            <input type="text"
                                                                class="form-control @error('title') is-invalid @enderror"
                                                                name="title" value="{{ $item->title }}" id="title"
                                                                style="width:100%; text-transform: none" readonly>

                                                            @error('title')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr">{{ __('Subject') }}</label>
                                                            <input type="text"
                                                                class="form-control @error('subject') is-invalid @enderror"
                                                                name="subject" value="{{ $item->subject }}" id="subject"
                                                                style="width:100%; text-transform: none">

                                                            @error('subject')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr">{{ __('Notification content') }}</label>
                                                            <textarea name="notification_content" id="notification_content" name="notification_content"
                                                                class="form-control text_transform_none" cols="30" rows="10">{{ $item->notification_content }}</textarea>

                                                            @error('notification_content')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="usr">{{ __('mail content') }}</label>
                                                            <textarea name="mail_content" class="form-control textarea_editor text_transform_none" cols="10" rows="10"
                                                                id="mail_content">{{ $item->mail_content }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <div class="text-center">
                                                                <input type="submit" value="{{ __('Save') }}"
                                                                    class="btn btn-primary">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
