<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    @php
        $title = App\Models\GeneralSetting::find(1)->business_name;
        $favicon = App\Models\GeneralSetting::find(1)->favicon;
    @endphp

    <title>{{ $title }} | @yield('title','Vendor register')</title>

    <link rel="icon" href="{{ url('images/upload/'.$favicon) }}" type="image/png">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" href="{{ url('css/components.css')}}">

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
        input[type=checkbox] + label {
            display: block;
            margin: 0.2em;
            cursor: pointer;
            padding: 0.2em;
        }

        input[type=checkbox]
        {
            display: none;
        }

        input[type=checkbox] + label:before
        {
            border: 0.1em solid #000;
            border-radius: 0.2em;
            display: inline-block;
            width: 1.5em;
            height: 1.5em;
            padding-left: 0.2em;
            padding-bottom: 0.3em;
            margin-right: 0.5em;
            vertical-align: bottom;
            color: transparent;
            transition: .2s;
            content: "\2714";
        }

        input[type=checkbox] + label:active:before
        {
            transform: scale(0);
        }

        input[type=checkbox]:checked + label:before
        {
            background-color:var(--site_color);
            border-color:var(--site_color);
            color: #fff;
        }

        input[type=checkbox]:disabled + label:before
        {
            transform: scale(1);
            border-color: #aaa;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }
    </style>
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
                        <h4 class="text-dark mb-5 font-weight-normal">{{__('Welcome to ')}}<span class="font-weight-bold">{{__('Restaurant')}}</span>
                        </h4>
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
                        <form method="POST" action="{{ url('vendor/register') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="frist_name">{{__('Name')}}</label>
                                    <input id="frist_name" type="text" placeholder="{{__('Name')}}" value="{{ old('name') }}" required class="form-control" name="name" autofocus="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">{{__('Email')}}</label>
                                <input id="email" type="email" placeholder="{{__('Email Address')}}" value="{{ old('email_id') }}" required class="form-control" name="email_id">
                            </div>

                            <div class="form-group">
                                <label>{{__('Contact')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend" style="width: 100px;">
                                        <select name="phone_code" class="form-control select2">
                                            @foreach ($phone_codes as $phone_code)
                                                <option value="+{{ $phone_code->phonecode }}">+{{ $phone_code->phonecode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="number" min=1 name="phone" placeholder="{{__('Contact Number')}}" value="{{ old('phone') }}" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="password" class="d-block">{{__('Password')}}</label>
                                    <input id="password" type="password" class="form-control" placeholder="{{__('* * * * * *')}}" required data-indicator="pwindicator" name="password">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <input type="checkbox" id="chkbox" name="vendor_own_driver">
                                    <label for="chkbox">{{__('Vendor Has Own Driver??')}}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{__('Register')}}
                                </button>
                            </div>
                        </form>
                        <div class="text-muted text-center">
                            {{__("Already have an account?")}} <a href="{{ url('vendor/login') }}">{{__('Login')}}</a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

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
