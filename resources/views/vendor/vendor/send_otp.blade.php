<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    @php
        $title = App\Models\GeneralSetting::find(1)->business_name;
        $favicon = App\Models\GeneralSetting::find(1)->favicon;
    @endphp

    <title>{{ $title }} | @yield('title','Vendor verified')</title>

    <link rel="icon" href="{{ url('images/upload/'.$favicon) }}" type="image/png">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" href="{{ url('css/components.css')}}">

    @php
        $favicon = App\Models\GeneralSetting::find(1)->company_favicon;
        $color = App\Models\GeneralSetting::find(1)->site_color;
        $icon = App\Models\GeneralSetting::find(1)->company_black_logo;

    @endphp
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style>
        :root
        {
            --site_color: <?php echo $color; ?>;
            --hover_color: <?php echo $color.'c7'; ?>;
        }
        .digit-group
        {
            text-align: center;
        }
        .digit-group input
        {
            width: 80px;
            height: 68px;
            background-color: transparent;
            line-height: 50px;
            text-align: center;
            font-weight: 200;
            margin: 0 2px;
            border-radius: 24%;
            transition: all 0.2s ease-in-out;
            /* border: none; */
            outline: none;
            border: solid 1px #ccc;
        }
        .digit-group input:focus
        {
            border-color: var(--site_color);
            box-shadow: 0 0 5px var(--hover_color) inset;
        }
        .digit-group input::selection
        {
            background: transparent;
        }
        .digit-group .splitter {
            padding: 0 5px;
            color: white;
            font-size: 24px;
        }
        .prompt {
            margin-bottom: 20px;
            font-size: 20px;
            color: white;
        }
    </style>

    <script>
        $(function()
        {
            $('.digit-group').find('input').each(function()
            {
                $(this).attr('maxlength', 1);
                $(this).on('keyup', function(e)
                {
                    var parent = $($(this).parent());
                    if(e.keyCode === 8 || e.keyCode === 37) {
                        var prev = parent.find('input#' + $(this).data('previous'));

                        if(prev.length) {
                            $(prev).select();
                        }
                    } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                        var next = parent.find('input#' + $(this).data('next'));

                        if(next.length) {
                            $(next).select();
                        } else {
                            if(parent.data('autosubmit')) {
                                parent.submit();
                            }
                        }
                    }
                });
            });
        });
    </script>
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
                        <h4 class="text-dark mb-5 font-weight-normal text-center">{{__('Welcome to ')}}<span class="font-weight-bold">{{__('Restaurant')}}</span>
                        </h4>
                        @if (session('status'))
                            <script>
                                var msg = "<?php echo Session::get('msg'); ?>"
                                    $(window).on('load', function()
                                    {
                                        iziToast.success({
                                            message: msg,
                                            position: 'topRight'
                                        });
                                        console.log(msg);
                                    });
                            </script>
                        @endif
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
                        <form method="post" action="{{ url('vendor/check_otp') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="digit-group" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                <input type="text" required id="digit-1" name="digit_1" data-next="digit-2" />
                                <input type="text" required id="digit-2" name="digit_2" data-next="digit-3" data-previous="digit-1" />
                                <input type="text" required id="digit-3" name="digit_3" data-next="digit-4" data-previous="digit-2" />
                                <input type="text" required id="digit-4" name="digit_4" data-next="digit-5" data-previous="digit-3" />
                            </div>
                            <div class="form-group mt-4 w-100 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{__('verify')}}
                                </button>
                            </div>
                        </form>
                        <div class="text-muted text-center">
                            {{__("Didn't receive a code?")}}<a href="{{ url('vendor/send_otp/'.$user->id) }}">{{__('Send again.')}}</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
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
