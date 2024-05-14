@extends('layouts.app', ['activePage' => 'home'])

@section('title', 'Admin Dashboard')

@section('content')
    <script type="text/javascript">
        window.onload = () => {
            $(".main-sidebar").niceScroll();
        }
    </script>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Admin dashboard') }}</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5>{{ __('Daily Order Management') }}</h5>
                        </div>
                        <div class="card-body">
                            <h3>{{ $today_orders }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5>{{ __("Today's earning") }}</h5>
                        </div>
                        <div class="card-body">
                            <h3>{{ $currency }}{{ $today_earnings }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5>{{ __('Total orders') }}</h5>
                        </div>
                        <div class="card-body">
                            <h3>{{ $total_orders }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-primary rounded-lg">
                        <div class="card-header">
                            <h5>{{ __('Total earning') }}</h5>
                        </div>
                        <div class="card-body">
                            <h3>{{ $currency }}{{ $total_earnings }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-dark w-50">{{ __('Growth & progress') }}</h4>
                            <div class="w-50 text-right">
                                <input type="text" name="filter_date_range" class="form-control">
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" id="home-tab3" data-toggle="tab" href="#home3"
                                        role="tab" aria-controls="home" aria-selected="false">{{ __('orders') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                        aria-controls="profile" aria-selected="true">{{ __('earnings') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent2">

                                <div class="tab-pane fade active show" id="home3" role="tabpanel"
                                    aria-labelledby="home-tab3">
                                    <canvas id="orderChart" class="chartjs-render-monitor"
                                        style="display: block; width: 580px; height: 250px"></canvas>
                                </div>
                                <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                                    <canvas id="earningChart" class="chartjs-render-monitor"
                                        style="display: block; width: 580px; height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Avarage Time of restaurant') }}</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="avarageTime" class="chartjs-render-monitor"
                                style="display: block; width: 580px; height: 250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Top selling Items') }}</h4>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Items name') }}</th>
                                        <th>{{ __('vendor name') }}</th>
                                        <th>{{ __('Price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topItems as $topItem)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <th>
                                                <a href="{{ url('admin/menu/' . $topItem->menu_id) }}"
                                                    class="nav-link active">
                                                    {{ $topItem->itemName }}
                                                </a>
                                            </th>
                                            <th>{{ $topItem->vendor }}</th>
                                            <th>{{ $currency }}{{ $topItem->price }}</th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Top Selling Items') }}</h4>
                        </div>
                        <div class="card-body">
                            @foreach ($topItems as $topItem)
                                <div class="tickets-list">
                                    <a class="ticket-item">
                                        <div class="ticket-title text-muted">
                                            <h4>{{ $topItem->itemName }}</h4>
                                        </div>
                                        <div class="ticket-info">
                                            <div>{{ $topItem->total }}{{ ' time served' }}</div>
                                            <div class="w-100">
                                                @if ($topItem->type == 'veg')
                                                    <img src="{{ url('images/veg.png') }}" class="float-right"
                                                        alt="">
                                                @else
                                                    <img src="{{ url('images/non-veg.png') }}" class="float-right"
                                                        alt="">
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
