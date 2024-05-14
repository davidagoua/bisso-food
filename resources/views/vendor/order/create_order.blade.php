@extends('layouts.app', ['activePage' => 'order'])

@section('title', 'Create Order')

@section('content')

    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif
        <div class="section-header">
            <h1>{{ __('Create new order') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('vendor/home') }}">{{ __('Dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="{{ url('vendor/order') }}">{{ __('order') }}</a></div>
                <div class="breadcrumb-item">{{ __('create a order') }}</div>
            </div>
        </div>
        <input type="hidden" name="submenu_id" value="">
        <input type="hidden" name="hidden_amount" value="0">
        <input type="hidden" name="hidden_all_tax" value="0">
        <input type="hidden" name="hidden_promocode_price" value="0">
        <input type="hidden" name="hidden_promocode_id" value="0">
        <div class="section-body">
            <h2 class="section-title">{{ __('Create order') }}</h2>
            <p class="section-lead">{{ __('Create Order') }}</p>
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card menuCard">
                        <div class="row">
                            <div class="col-md-12 heroSlider-fixed">
                                <div class="overlay">
                                </div>
                                <div class="Orderslider responsive">
                                    @php
                                        $submenus = [];
                                    @endphp
                                    @foreach ($menus as $menu)
                                        @if ($loop->iteration == 1)
                                            @php
                                                $submenus = App\Models\Submenu::where('menu_id', $menu->id)->get();
                                                $tax = App\Models\GeneralSetting::first()->isItemTax;
                                                foreach ($submenus as $submenu) {
                                                    if ($tax == 0) {
                                                        $price_tax = App\Models\GeneralSetting::first()->item_tax;
                                                        $disc = $submenu->price * $price_tax;
                                                        $discount = $disc / 100;
                                                        $submenu->price = strval($submenu->price + $discount);
                                                    } else {
                                                        $submenu->price = strval($submenu->price);
                                                    }
                                                }
                                            @endphp
                                        @endif
                                        <div
                                            class="{{ $loop->iteration == 1 ? 'menuActive' : '' }} Menu{{ $menu->id }}">
                                            <h6 onclick="change_submenu({{ $menu->id }})">{{ $menu->name }}</h6>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="slider-prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                </div>
                                <div class="slider-next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade active show" id="menu110" role="tabpanel" aria-labelledby="home-tab3">
                            <br>
                            <div class="row">
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="row orderMainRow">
                                        @if (count($submenus) > 0)
                                            @foreach ($submenus as $submenu)
                                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-6 orderMainCol">
                                                    <div class="orderCard">
                                                        <div class="orderCardBody">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 orderCol">
                                                                    <img src="{{ $submenu->image }}" width="100%"
                                                                        height="113" class="orderImage" alt="">
                                                                </div>
                                                                <div
                                                                    class="col-sm-12 col-md-6 col-lg-6 col-xl-8 orderColRight">
                                                                    <div class="orderContent">
                                                                        @if ($submenu->type == 'veg')
                                                                            <img src="{{ url('images/veg.png') }}"
                                                                                class="orderIcon" alt="">
                                                                        @else
                                                                            <img src="{{ url('images/non-veg.png') }}"
                                                                                class="orderIcon" alt="">
                                                                        @endif
                                                                        <h5>{{ $submenu->name }}</h5>
                                                                    </div>
                                                                    <span class="text-muted orderDesc"
                                                                        id="orderContent{{ $submenu->id }}">{{ $submenu->description }}</span>
                                                                    <br>
                                                                    <a onclick="DispCustimization({{ $submenu->id }})"
                                                                        class="cursor-pointer hide text-primary custimization{{ $submenu->id }}">{{ __('Custimization') }}</a>
                                                                    @if (Session::get('cart') != null)
                                                                        @foreach (Session::get('cart') as $cart)
                                                                            @if ($cart['id'] == $submenu->id)
                                                                                <a onclick="DispCustimization({{ $submenu->id }})"
                                                                                    class="cursor-pointer text-primary custimization{{ $submenu->id }}">{{ __('Custimization') }}</a>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                    <div class="orderPriceQty"
                                                                        id="orderQty{{ $submenu->id }}">
                                                                        <div class="orderAmount">
                                                                            {{ $currency }}{{ $submenu->price }}
                                                                        </div>
                                                                        @if (Session::get('cart') != null)
                                                                            @foreach (Session::get('cart') as $cart)
                                                                                @if ($cart['id'] == $submenu->id)
                                                                                    <span
                                                                                        class="orderQty">{{ $cart['qty'] }}</span>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="alert alert-primary alert-dismissible fade show hide show_alert"
                                                role="alert">
                                                <strong class="display"></strong>
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="card SubmenuCard display_menu">
                                                <div class="table-responsive p-3 qtyTable">
                                                    <table class="table">
                                                        <tbody>
                                                            @foreach ($sub_menus as $sub_menu)
                                                                <input type="hidden"
                                                                    id="original_price{{ $sub_menu->id }}"
                                                                    value="{{ $sub_menu->price }}">
                                                                <tr>
                                                                    <th>
                                                                        <span>{{ $sub_menu->name }}</span>
                                                                        <br>
                                                                        <span
                                                                            class="text-muted">{{ $currency }}{{ $sub_menu->price }}</span>
                                                                    </th>
                                                                    @if (Session::get('cart') == null)
                                                                        <td class="priceTd">{{ $currency }}
                                                                            <span
                                                                                class="itemPrice{{ $sub_menu->id }}">00</span>
                                                                        </td>
                                                                        <td class="d-flex pt-3">
                                                                            <p class="orderMinusButton"
                                                                                id="minus{{ $sub_menu->id }}"><i
                                                                                    class="fas fa-minus"
                                                                                    onclick="cart({{ $sub_menu->id }},'minus')"></i>
                                                                            </p>
                                                                            <p
                                                                                class="orderQtyDisplay qty{{ $sub_menu->id }}">
                                                                                0</p>
                                                                            <p class="orderPlusButton"><i
                                                                                    class="fas fa-plus"
                                                                                    onclick="cart({{ $sub_menu->id }},'plus')"></i>
                                                                            </p>
                                                                        </td>
                                                                    @else
                                                                        @if (in_array($sub_menu->id, array_column(Session::get('cart'), 'id')))
                                                                            @foreach (Session::get('cart') as $cart)
                                                                                @if ($cart['id'] == $sub_menu->id)
                                                                                    <td class="priceTd">
                                                                                        {{ $currency }}
                                                                                        <span
                                                                                            class="itemPrice{{ $sub_menu->id }}">{{ $cart['price'] }}</span>
                                                                                    </td>
                                                                                    <td class="d-flex pt-3">
                                                                                        <p class="orderMinusButton"><i
                                                                                                class="fas fa-minus"
                                                                                                onclick="cart({{ $sub_menu->id }},'minus')"></i>
                                                                                        </p>
                                                                                        <p
                                                                                            class="orderQtyDisplay qty{{ $sub_menu->id }}">
                                                                                            {{ $cart['qty'] }}</p>
                                                                                        <p class="orderPlusButton"><i
                                                                                                class="fas fa-plus"
                                                                                                onclick="cart({{ $sub_menu->id }},'plus')"></i>
                                                                                        </p>
                                                                                    </td>
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            <td class="priceTd">{{ $currency }}
                                                                                <span
                                                                                    class="itemPrice{{ $sub_menu->id }}">00</span>
                                                                            </td>
                                                                            <td class="d-flex pt-3">
                                                                                <p class="orderMinusButton"><i
                                                                                        class="fas fa-minus"
                                                                                        onclick="cart({{ $sub_menu->id }},'minus')"></i>
                                                                                </p>
                                                                                <p
                                                                                    class="orderQtyDisplay qty{{ $sub_menu->id }}">
                                                                                    0</p>
                                                                                <p class="orderPlusButton"><i
                                                                                        class="fas fa-plus"
                                                                                        onclick="cart({{ $sub_menu->id }},'plus')"></i>
                                                                                </p>
                                                                            </td>
                                                                        @endif
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="cardFooter d-flex">
                                                    <div class="totalItemDiv">
                                                        <p class="totalItem">{{ __('Total Items') }}: <span
                                                                class="displayTotalItem">{{ $total_item }}</span>
                                                            <br>{{ __('Amount') }}: {{ $currency }}<span
                                                                class="totalPrice">{{ $grand_total }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="PlaceOrder">
                                                        <input type="button" class="placeOrderButton"
                                                            onclick="Custimization()" value="{{ __('Place Order') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide repeat_order">
                                                <div class="table-responsive p-3 qtyTable">
                                                    <div class="row custimization">
                                                        <div class="col-md-12 pb-3 text-center">
                                                            <button class="add_order"
                                                                onclick="repeat_order()">{{ __('Repeat Order') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cardFooter d-flex">
                                                    <div class="totalItemDiv">
                                                        <p class="totalItem">{{ __('Total Items') }}: <span
                                                                class="displayTotalItem">{{ $total_item }}</span>
                                                            <br>{{ __('Amount') }}:{{ $currency }}<span
                                                                class="totalPrice">{{ $grand_total }}
                                                        </p>
                                                    </div>
                                                    <div class="PlaceOrder">
                                                        <input type="button" class="placeOrderButton"
                                                            onclick="showUser()" value="{{ __('Place Order') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide session_menu">
                                                <div class="table-responsive p-3 qtyTable">
                                                    <table class="table">
                                                        <tbody>
                                                            @foreach ($sub_menus as $sub_menu)
                                                                <input type="hidden"
                                                                    id="original_price{{ $sub_menu->id }}"
                                                                    value="{{ $sub_menu->price }}">
                                                                <tr>
                                                                    <th>
                                                                        <span>{{ $sub_menu->name }}</span>
                                                                        <br>
                                                                        <span
                                                                            class="text-muted">{{ $currency }}{{ $sub_menu->price }}</span>
                                                                    </th>
                                                                    @if (Session::get('cart') == null)
                                                                        <td class="priceTd">{{ $currency }}
                                                                            <span
                                                                                class="itemPrice{{ $sub_menu->id }}">00</span>
                                                                        </td>
                                                                        <td class="d-flex pt-3">
                                                                            <p class="orderMinusButton"
                                                                                id="minus{{ $sub_menu->id }}"><i
                                                                                    class="fas fa-minus"
                                                                                    onclick="cart({{ $sub_menu->id }},'minus')"></i>
                                                                            </p>
                                                                            <p
                                                                                class="orderQtyDisplay qty{{ $sub_menu->id }}">
                                                                                0</p>
                                                                            <p class="orderPlusButton"><i
                                                                                    class="fas fa-plus"
                                                                                    onclick="cart({{ $sub_menu->id }},'plus')"></i>
                                                                            </p>
                                                                        </td>
                                                                    @else
                                                                        @if (in_array($sub_menu->id, array_column(Session::get('cart'), 'id')))
                                                                            @foreach (Session::get('cart') as $cart)
                                                                                @if ($cart['id'] == $sub_menu->id)
                                                                                    <td class="priceTd">
                                                                                        {{ $currency }}
                                                                                        <span
                                                                                            class="itemPrice{{ $sub_menu->id }}">{{ $cart['price'] }}</span>
                                                                                    </td>
                                                                                    <td class="d-flex pt-3">
                                                                                        <p class="orderMinusButton"><i
                                                                                                class="fas fa-minus"
                                                                                                onclick="cart({{ $sub_menu->id }},'minus')"></i>
                                                                                        </p>
                                                                                        <p
                                                                                            class="orderQtyDisplay qty{{ $sub_menu->id }}">
                                                                                            {{ $cart['qty'] }}</p>
                                                                                        <p class="orderPlusButton"><i
                                                                                                class="fas fa-plus"
                                                                                                onclick="cart({{ $sub_menu->id }},'plus')"></i>
                                                                                        </p>
                                                                                    </td>
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            <td class="priceTd">{{ $currency }}
                                                                                <span
                                                                                    class="itemPrice{{ $sub_menu->id }}">00</span>
                                                                            </td>
                                                                            <td class="d-flex pt-3">
                                                                                <p class="orderMinusButton"><i
                                                                                        class="fas fa-minus"
                                                                                        onclick="cart({{ $sub_menu->id }},'minus')"></i>
                                                                                </p>
                                                                                <p
                                                                                    class="orderQtyDisplay qty{{ $sub_menu->id }}">
                                                                                    0</p>
                                                                                <p class="orderPlusButton"><i
                                                                                        class="fas fa-plus"
                                                                                        onclick="cart({{ $sub_menu->id }},'plus')"></i>
                                                                                </p>
                                                                            </td>
                                                                        @endif
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="cardFooter d-flex">
                                                    <div class="totalItemDiv">
                                                        <p class="totalItem">{{ __('Total Items') }}: <span
                                                                class="displayTotalItem">{{ $total_item }}</span>
                                                            <br>{{ __('Amount') }}: {{ $currency }}<span
                                                                class="totalPrice">{{ $grand_total }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="PlaceOrder">
                                                        <input type="button" class="placeOrderButton"
                                                            onclick="showUser()" value="{{ __('Place Order') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide show_user">
                                                <div class="card-header">
                                                    <input type="text" class="searchTextBox"
                                                        placeholder="{{ __('search user') }}" id="search"
                                                        onkeyup="data_search()">
                                                    <a class="AddUserText"
                                                        onclick="user()">{{ __('+ Add New User') }}</a>
                                                </div>
                                                <div class="table-responsive qtyTable show_userCard">
                                                    <ul class="list-unstyled displayUser user-details list-unstyled-border list-unstyled-noborder"
                                                        id="sort_location">
                                                        @foreach ($users as $user)
                                                            <li class="media p-3 single_record">
                                                                <img alt="image" class="mr-3 rounded-circle"
                                                                    width="50" src="{{ $user->image }}">
                                                                <div class="media-body">
                                                                    <div class="media-title">{{ $user->name }}</div>
                                                                    <div class="text-job text-muted">
                                                                        {{ $user->email_id }}<br>
                                                                        {{ $user->phone }}
                                                                    </div>
                                                                </div>
                                                                <div class="media-item">
                                                                    <div class="media-value">
                                                                        <input type="radio" value="{{ $user->id }}"
                                                                            id="chkbox{{ $loop->iteration }}"
                                                                            name="user">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="cardFooter d-flex">
                                                    <div class="totalItemDiv">
                                                        <p class="totalItem">{{ __('Total Items') }}:
                                                            <span class="displayTotalItem">{{ $total_item }}</span>
                                                            <br>
                                                            {{ __('Amount :') }}{{ $currency }}
                                                            <span class="totalPrice">
                                                                {{ $grand_total }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div class="PlaceOrder">
                                                        <input type="button" class="placeOrderButton"
                                                            onclick="displayBill()" value="{{ __('Place Order') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide add_user">
                                                <div class="card-header"><i
                                                        class="fas fa-chevron-left mr-3 cursor-pointer"
                                                        onclick="showUser()"></i>
                                                    <h4>{{ __('Add New User') }}</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive qtyTable">
                                                        <form class="myform"
                                                            action="{{ url('branch_manager/add_user') }} ">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="username">User Name</label>
                                                                    <input type="text" name="name"
                                                                        placeholder="User Name" id="user_name"
                                                                        required="required" class="form-control"
                                                                        style="text-transform: none;">
                                                                    <div class="custom_error">
                                                                        <span class="name"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="contact">Email Address</label>
                                                                    <input type="email" name="email_id"
                                                                        placeholder="Email Address" id="email"
                                                                        required="required" class="form-control"
                                                                        style="text-transform: none;">
                                                                    <div class="custom_error">
                                                                        <span class="email_id"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 mb-3">
                                                                    <label for="contact">Phone</label>
                                                                    <input type="number" name="phone"
                                                                        placeholder="Phone" id="phone"
                                                                        required="required" class="form-control"
                                                                        style="text-transform: none;">
                                                                    <div class="custom_error">
                                                                        <span class="phone"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="cardFooter">
                                                    <input type="button" value="Add User" class="UserBtn"
                                                        onclick="UserBtn()">
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide total_bill">
                                                <div class="table-responsive p-3 qtyTable">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr class="Border" id="taxCharge">
                                                                <td class="leftTd">{{ __('Total amount') }}</td>
                                                                <td class="rightTd">{{ $currency }}
                                                                    <span class="dispBillTotalAmount">00</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="Border">
                                                                <td class="leftTd">{{ __('Final total') }}</td>
                                                                <td class="rightTd">
                                                                    {{ $currency }}
                                                                    <span class="dispBillFinalTotal">00</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="NoPaddingMargin">
                                                                    <div class="coupenTextbox">
                                                                        {{ __('you have a coupen to use') }}
                                                                        <a
                                                                            onclick="applyCoupen()">{{ __('Apply It') }}</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="cardFooter d-flex">
                                                    <div class="totalItemDiv">
                                                        <p class="totalItem">{{ 'Total Items' }}:
                                                            <span class="displayTotalItem">{{ $total_item }}</span>
                                                            <br>
                                                            {{ 'Amount :' }}{{ $currency }}
                                                            <span class="totalPrice">
                                                                {{ $grand_total }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div class="PlaceOrder">
                                                        <input type="button" class="placeOrderButton"
                                                            onclick="confirm_order()" value="{{ __('Place Order') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide display_coupen">
                                                <div class="table-responsive p-3 qtyTable">
                                                    <table class="table DisplayCoupen">
                                                        @foreach ($promoCodes as $promoCode)
                                                            <tr class="Border p-4">
                                                                <td class="leftTd">
                                                                    <p class="couponCode">{{ $promoCode->promo_code }}
                                                                    </p>
                                                                    <p class="couponDiscri">{{ $promoCode->description }}
                                                                    </p>
                                                                    <p class="couponExpire">
                                                                        {{ __('valid up to ') }}{{ explode(' - ', $promoCode->start_end_date)[1] }}
                                                                    </p>
                                                                </td>
                                                                <td class="rightTd"><a class="applyBtn"
                                                                        onclick="displayBillWithCoupen({{ $promoCode->id }})">{{ __('APPLY') }}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide display_bill_with_coupen">
                                                <div class="table-responsive p-3 qtyTable">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr class="Border" id="taxChargeWithCoupen">
                                                                <td class="leftTd">{{ __('Total amount') }}</td>
                                                                <td class="rightTd">{{ $currency }}
                                                                    <span class="CoupenTotalAmount">00</span>
                                                                </td>
                                                                {{-- </tr>
                                                        <tr class="Border" id="taxChargeWithCoupen">
                                                            <td class="leftTd">{{__('Tax charges')}}</td>
                                                            <td class="rightTd">
                                                                {{$currency}}
                                                                <span class="CoupenTax">00</span>
                                                            </td>
                                                        </tr> --}}
                                                            <tr class="Border">
                                                                <td class="leftTd">{{ __('Final total') }}</td>
                                                                <td class="rightTd">
                                                                    {{ $currency }}
                                                                    <span class="CoupenFinalTotal">00</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="Border">
                                                                <td class="leftTd">{{ __('Applied Coupen') }}<br>
                                                                    <span class="coupenTotalDisplay">KHKDJH(30%)</span>
                                                                    <span class="remove_coupen"
                                                                        onclick="displayBill()">{{ __('Remove Coupen') }}</span>
                                                                </td>
                                                                <td class="rightTd text-danger">-{{ $currency }}
                                                                    <span class="CoupenDiscount">00</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="leftTd">{{ __('Grand Total') }}</td>
                                                                <td class="rightTd">{{ $currency }}
                                                                    <span class="CoupenGrandTotal">00</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="NoPaddingMargin"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="cardFooter d-flex">
                                                    <div class="totalItemDiv">
                                                        <p class="totalItem">{{ __('Total Items') }}: <span
                                                                class="displayTotalItem">{{ $total_item }}</span>
                                                            <br>{{ __('Amount') }}: {{ $currency }}<span
                                                                class="totalPrice">{{ $grand_total }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="PlaceOrder">
                                                        <input type="button" class="placeOrderButton"
                                                            onclick="confirm_order()" value="{{ __('Place Order') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card SubmenuCard hide display_custimization">
                                                <div class="table-responsive p-3 qtyTable displayCustimization">
                                                </div>
                                                <div class="cardFooter d-flex">
                                                    <div class="totalItemDiv">
                                                        <p class="totalItem">{{ __('Total Items') }}: <span
                                                                class="displayTotalItem">{{ $total_item }}</span>
                                                            <br>{{ __('Amount') }}: {{ $currency }}<span
                                                                class="totalPrice">{{ $grand_total }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="PlaceOrder">
                                                        <input type="button" class="placeOrderButton"
                                                            onclick="showUser()" value="{{ __('Place Order') }}">
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
    </section>

@endsection

@section('js')
    <script src="{{ url('js/order.js') }}"></script>
@endsection
