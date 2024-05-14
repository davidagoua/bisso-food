<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    @php
        $title = App\Models\GeneralSetting::find(1)->business_name;
        $favicon = App\Models\GeneralSetting::find(1)->favicon;
    @endphp

    <title>{{ $title }} | @yield('title','Admin login')</title>

    <link rel="icon" href="{{ url('images/upload/'.$favicon) }}" type="image/png">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

        @php
            $favicon = App\Models\GeneralSetting::find(1)->company_favicon;
            $color = App\Models\GeneralSetting::find(1)->site_color;
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
<body onload="document.forms['member_signup'].submit()">
    <form method="POST" name="member_signup" action="https://checkout.flutterwave.com/v3/hosted/pay">
        <input type="hidden" name="public_key" value="{{ App\Models\PaymentSetting::first()->public_key }}" />
        <input type="hidden" name="customer[email]" value="{{ $order->user['email_id'] }}" />
        <input type="hidden" name="customer[phone_number]" value="{{ $order->user['phone'] }}" />
        <input type="hidden" name="customer[name]" value="{{ $order->user['name'] }}" />
        <input type="hidden" name="tx_ref" value="bitethtx-019203" />
        <input type="hidden" name="amount" value="{{ $order->amount }}" />
        <input type="hidden" name="currency" value="{{ App\Models\GeneralSetting::first()->currency }}" />
        <input type="hidden" name="meta[token]" value="20" />
        <input type="hidden" name="redirect_url" value="{{ url('transction_verify/'.$order->id) }}" />
    </form>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ url('js/stisla.js') }}"></script>

    <script src="{{url('js/scripts.js')}}"></script>
    <script src="{{url('js/custom.js')}}"></script>
</body>

</html>
