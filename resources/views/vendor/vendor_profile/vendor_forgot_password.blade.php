<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    @php

    $title = App\Models\GeneralSetting::find(1)->business_name;

    @endphp
    <title>{{ $title }} | @yield('title','Vendor forgot password')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

        @php
            $favicon = App\Models\GeneralSetting::find(1)->company_favicon;
            $color = App\Models\GeneralSetting::find(1)->site_color;
            $icon = App\Models\GeneralSetting::find(1)->company_black_logo;

        @endphp
        <style>
            :root
            {
                --site_color: <?php echo $color; ?>;
                --hover_color: <?php echo $color.'c7'; ?>;
            }
        </style>

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" href="{{ url('css/components.css')}}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-8 col-12 col-md-6 order-lg-1 order-1 min-vh-100 background-walk-y overlay-gradient-bottom" data-background="{{ url('images/1.png') }}" style="background-color: #23110f">
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <h1 class="mb-2 display-4 font-weight-bold">{{__("welcome Vendor...!!")}}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12 pt-5 col-md-6 order-lg-2 min-vh-100 order-2 bg-white">
                    <div class="p-4 m-3">
                        <div class="w-100 text-center">
                            <img src="{{ url('images/upload/'.$icon) }}" alt="logo" width="80" class="shadow-light rounded-circle mb-5 mt-2">
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-primary alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>×</span>
                                </button>
                                @foreach ($errors->all() as $item)
                                {{ $item }}
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if (Session::has('msg'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>×</span>
                                </button>
                                {{ Session::get('msg') }}
                            </div>
                        </div>
                        @endif
                        @if (Session::has('errormsg'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>×</span>
                                    </button>
                                    {{ Session::get('errormsg') }}
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ url('admin/admin_forgot_password') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{__('Email')}}</label>
                                <input id="email" type="email" class="form-control" placeholder="{{__('Please Enter Your Email')}}" name="email" tabindex="1" required autofocus>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    {{__('Submit')}}
                                </button>
                            </div>

                            <div class="form-group text-center">
                                <a href="{{ url('vendor/login') }}">{{__('Login')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ url('js/stisla.js') }}"></script>

    <!-- JS Libraies -->

    <!-- Template JS File -->
    <script src="{{ url('js/scripts.js') }}"></script>
    <script src="{{ url('js/custom.js') }}"></script>

    <!-- Page Specific JS File -->
</body>
</html>
