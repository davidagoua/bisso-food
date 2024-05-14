<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    @php
        $title = App\Models\GeneralSetting::find(1)->business_name;
    @endphp

    <title>{{ $title }} | {{ 'Invoice' }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- General CSS Files -->
    <input type="hidden" id="mainurl" value="{{url('/')}}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>

    <!-- CSS Libraries -->

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-flash-1.6.2/b-html5-1.6.2/b-print-1.6.2/datatables.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.15/dist/summernote.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.css">

    @php
        $favicon = App\Models\GeneralSetting::find(1)->company_favicon;
        $color = App\Models\GeneralSetting::find(1)->site_color;
    @endphp
    <style>
        :root {
            --site_color: <?php echo $color; ?>;
            --hover_color: <?php echo $color.'c7'; ?>;
        }
    </style>

    <!-- Template CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.css">

    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('js/iziToast.min.js') }}"></script>

    <script>
        window.print();
    </script>
</head>

<body>
    <div class="page">
        <div id="app">
            <div class="main-wrapper main-wrapper-1">
                <div class="main-content">
                    @php
                        $discount = intval(00);
                        $cust = "";
                        $totalCust= 0;
                        $itemPrice = 0;
                    @endphp
                    @if($order->promo_code_price != null)
                        @php
                            $discount += intval($order->promo_code_price);
                        @endphp
                    @elseif($order->vendor_discount_price != null)
                        @php
                            $discount += intval($order->vendor_discount_price);
                        @endphp
                    @endif

                    @php
                        $delivery_charge = 0;
                    @endphp
                    @if($order->delivery_charge != null)
                        @php
                            $delivery_charge = $order->delivery_charge
                        @endphp
                    @endif

                    @php
                        $tax = 0;
                    @endphp
                    @if($order->tax != null)
                        @php
                            $taxs = $order->tax;
                            foreach (json_decode($taxs) as $t)
                            {
                                $tax = $tax + $t->tax;
                            }
                        @endphp
                    @endif

                    <div class="section-body">
                        <div class="invoice">
                            <div class="invoice-print">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="invoice-title">
                                            <h2>{{__('Invoice')}}</h2>
                                            <div class="invoice-number">{{__('Order')}} {{$order->order_id}}</div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <address>
                                                    <strong>{{__('Billed To:')}}</strong><br>
                                                    {{ $general_setting->business_name }}<br>
                                                    {{ $general_setting->business_address }}
                                                </address>
                                            </div>
                                            <div class="col-md-6 text-md-right">
                                                <address>
                                                    <strong>{{__('user')}}</strong><br>
                                                    {{ $order['user']->name }}<br>
                                                    {{$order['user']->email}}<br>
                                                    {{$order['user']->phone}}<br>
                                                </address>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="offset-md-6 col-md-6 text-md-right">
                                                <address>
                                                    <strong>{{__('Order Date:')}}</strong><br>
                                                    {{ $order->date }}&nbsp;{{ $order->time }}<br><br>
                                                </address>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="section-title">{{__('Order Summary')}}</div>
                                        <p class="section-lead">{{__('All items here cannot be deleted.')}}</p>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-md">
                                                <tbody>
                                                    <tr>
                                                        <th data-width="40" style="width: 40px;">#</th>
                                                        <th>{{__('Item')}}</th>
                                                        <th class="text-center">{{__('Price')}}</th>
                                                        <th class="text-center">{{__('Quantity')}}</th>
                                                        <th class="text-center">{{__('Custimization')}}</th>
                                                        <th class="text-center">{{__('Custimization price')}}</th>
                                                        <th class="text-right">{{__('Totals')}}</th>
                                                    </tr>
                                                    @foreach ($order->orderItems as $item)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{ $item['itemName'] }}</td>
                                                        <td class="text-center">
                                                            {{ $general_setting->currency_symbol }}{{ $item->price }}
                                                        </td>
                                                        <td class="text-center">{{ $item->qty }}</td>
                                                        @if ($item->custimization != null)
                                                            @foreach ($item->custimization as $custimize)
                                                                @php
                                                                    $cust = $cust ." ". $custimize->data->name.",";
                                                                    $totalCust += $custimize->data->price;
                                                                @endphp
                                                            @endforeach
                                                            <td class="text-center">{{$cust}}</td>
                                                            <td class="text-center">{{$totalCust}}</td>
                                                        @else
                                                            @php
                                                                $totalCust= 0;
                                                            @endphp

                                                            <td class="text-center">{{__('Not included')}}</td>
                                                            <td class="text-center">{{__('Not included')}}</td>
                                                        @endif
                                                        <td class="text-right">{{ $general_setting->currency_symbol }}{{ $item->price + $totalCust }}</td>
                                                        @php
                                                            $itemPrice = $item->price + $totalCust;
                                                        @endphp
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <div class="section-title mt-5">{{__('Tax')}}</div>
                                            <p class="section-lead">{{__('Tax Description.')}}</p>
                                            <table class="table table-striped table-hover table-md text-center">
                                                <tbody>
                                                    <tr>
                                                        <th data-width="40" style="width: 40px;">#</th>
                                                        <th>{{__('Tax Name')}}</th>
                                                        <th>{{__('Tax value')}}</th>
                                                    </tr>
                                                    @foreach (json_decode($order->tax) as $ts)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{ $ts->name }}</td>
                                                        <td>
                                                            {{ $general_setting->currency_symbol }}{{ $ts->tax }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="offset-lg-8 col-lg-4 text-right">
                                                <div class="invoice-detail-item">
                                                    <div class="invoice-detail-name">{{__('Tax')}}</div>
                                                    <div class="invoice-detail-value">
                                                        {{ $general_setting->currency_symbol }}{{ $tax }}</div>
                                                </div>
                                                <div class="invoice-detail-item">
                                                    <div class="invoice-detail-name">{{__('Subtotal')}}</div>
                                                    <div class="invoice-detail-value">
                                                        {{ $general_setting->currency_symbol }}{{ $itemPrice }}</div>
                                                </div>
                                                <div class="invoice-detail-item">
                                                    <div class="invoice-detail-name">{{__('Shipping')}}</div>
                                                    <div class="invoice-detail-value">
                                                        {{ $general_setting->currency_symbol }}{{ $delivery_charge }}
                                                    </div>
                                                </div>

                                                <div class="invoice-detail-item">
                                                    <div class="invoice-detail-name">{{__('Discount')}}</div>
                                                    <div class="invoice-detail-value">
                                                        {{ $general_setting->currency_symbol }}{{ $discount }}
                                                    </div>
                                                </div>
                                                <hr class="mt-2 mb-2">
                                                <div class="invoice-detail-item">
                                                    <div class="invoice-detail-name">{{__('Total')}}</div>
                                                    <div class="invoice-detail-value invoice-detail-value-lg">
                                                        {{ $general_setting->currency_symbol }}{{ $order->amount }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
