var base_url = $('#mainurl').val();

$(document).ready(function ()
{
    $('.responsive').slick({
        dots: true,
        prevArrow: $('.slider-prev'),
        nextArrow: $('.slider-next'),
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
})

function change_submenu(menu_id)
{
    $('.slick-slide').removeClass('menuActive');
    $('.Menu' + menu_id).addClass('menuActive');
    $.ajax(
        {
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: base_url + '/vendor/change_submenu',
            data:
            {
                menu_id: menu_id,
            },
            success: function (result)
            {
                $('.orderMainRow').html('');
                for (let i = 0; i < result.data.length; i++)
                {
                    var qty;
                    var cust;
                    var icon;
                    if(result.data[i].type == 'veg')
                    {
                        icon = '<img src="'+base_url+'/images/veg.png" alt="" class="orderIcon">';
                    }
                    else
                    {
                        icon = '<img src="'+base_url+'/images/non-veg.png" alt="" class="orderIcon">';
                    }

                    if (result.data[i].qty > 0)
                    {
                        qty = '<span class="orderQty">' + result.data[i].qty + '</span>';
                        cust = ''
                    }
                    else
                    {
                        qty = '';
                        cust = 'hide';
                    }
                    $('.orderMainRow').append('<div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 orderMainCol"><div class="orderCard"><div class="orderCardBody"><div class="row"><div class="col-sm-12 col-md-12 col-lg-12 col-xl-4 orderCol"><img src="' + result.data[i].image + '" width="100%" height="113" alt="" class="orderImage"></div><div class="col-sm-12 col-md-12 col-lg-12 col-xl-8 orderColRight"><div class="orderContent">'+icon+'<h5>' + result.data[i].name + '</h5></div><span class="text-muted orderDesc" id="orderContent' + result.data[i].id + '">' + result.data[i].description + '</span><br><a onclick="DispCustimization(' + result.data[i].id + ')" class="text-primary cursor-pointer '+cust+' custimization'+result.data[i].id + '">Custimization</a><div class="orderPriceQty" id=orderQty' + result.data[i].id + '><div class="orderAmount">'+result.currency + result.data[i].price + '</div>' + qty + '</div></div></div></div></div></div>');
                }
            },
            error: function (err) {
                console.log('err ', err)
            }
    });
}

function Custimization()
{
    if ($('.displayTotalItem').text() != 0)
    {
        $('.repeat_order').removeClass("hide");
        $('.repeat_order').addClass("cardBottom");
        $('.display_menu').addClass("hide");
        $('.display_menu').removeClass("cardBottom");
        // $('.repeat_order').remo();
        // $('.display_menu').hide();
    }
    else {
        $('.show_alert').show();
        $('.display').text("Cart cann't be empty..!!");
    }
}

function repeat_order() {
    $('.session_menu').removeClass("hide");
    $('.session_menu').addClass("cardBottom");
    $('.repeat_order').hide();
    $('.display_menu').addClass("hide");
    $('.display_menu').removeClass("cardBottom");
    // $('.repeat_order').addClass("hide");
    // $('.repeat_order').removeClass("cardBottom");
}

function showUser()
{
    $('.show_user').removeClass("hide");
    $('.show_user').addClass("cardBottom");
    $('.session_menu').addClass("hide");
    $('.session_menu').removeClass("cardBottom");
    $('.repeat_order').addClass("hide");
    $('.repeat_order').removeClass("cardBottom");
    $('.display_bill_with_coupen').addClass("hide");
    $('.add_user').addClass("hide");
    $('.add_user').removeClass("cardBottom");
    $('.display_custimization').addClass("hide");
    $('.display_custimization').removeClass("cardBottom");
    var table_length = $('.custimization_table').length;
    var custimization = [];

    for (let i = 0; i < table_length; i++)
    {
        if ($('input[type=checkbox][id=custchkbox' + i + ']:checked').val() != undefined) {
            var radio_length = $('.radios' + i).length
            for (let j = 0; j < radio_length; j++) {
                if ($('input[type=radio][id=radio' + i + '_' + j + ']:checked').val() != undefined) {
                    var temp = $('input[type=radio][id=radio' + i + '_' + j + ']:checked').val();
                    console.log('temp', temp);
                    name = temp.split('_')[0];
                    price = temp.split('_')[1];
                }
            }
            custimization.push(
            {
                'main_menu': $('input[type=checkbox][id=custchkbox' + i + ']:checked').val(),
                'data': {
                    'name': name,
                    'price': price,
                }
            });
        }
    }

    // $.ajax(
    // {
    //     headers:
    //     {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     type: "GET",
    //     url: base_url + '/vendor/showUser',
    //     success: function (result)
    //     {
    //         if (result.success == true)
    //         {
    //             result.data.forEach((element,index)=>
    //             {
    //                 var index = parseInt(index) + parseInt(1);
    //                 $('.displayUser').append('<tbody><tr><th>'+index+'</th><th><input type="radio" id="chkbox'+index+'" name="user"><label for="chkbox'+index+'"></label></th><th>'+element.name+'</th><th>'+element.email_id+'</th><th>'+element.phone+'</th></tr></tbody>');
    //             });
    //         }
        // if (custimization.length > 0)
        // {
            $.ajax(
                {
                    headers:
                    {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "post",
                    url: base_url + "/vendor/update_custimization",
                    data:
                    {
                        submenu_id: $('input[type=hidden][name=submenu_id]').val(),
                        custimization: custimization,
                    },
                    success: function (result) {
                        if (result.success == true) {
                            $('.totalPrice').text(result.data.grand_total);
                        }
                    }
            });
    //     }
    // },
    //     error: function (err) {
    //         console.log('err ', err)
    //     }
    // });
}

function user() {
    $('.add_user').removeClass("hide");
    $('.add_user').addClass("cardBottom");
    $('.show_user').addClass("hide");
    $('.show_user').removeClass("cardBottom");
}

function displayBill()
{
    var user = $('input[type=radio]:checked').val();
    if (user != undefined)
    {
        $('.session_menu').addClass("hide");
        $('.session_menu').removeClass("cardBottom");
        $('.display_coupen').addClass("hide");
        $('.display_coupen').removeClass("cardBottom");
        $('.show_user').addClass("hide");
        $('.show_user').removeClass("cardBottom");
        $('.display_bill_with_coupen ').addClass("hide");
        $('.display_bill_with_coupen ').removeClass("cardBottom");
        $('.total_bill').removeClass("hide");
        $('.total_bill').addClass("cardBottom");
        $.ajax(
            {
                headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: base_url + '/vendor/display_bill',
                success: function (result) {
                    if (result.success == true)
                    {
                        $('.dispBillTotalAmount').text(result.data.totalAmount);
                        // $('.dispBillTex').text(result.data.tax);
                        $('.dispBillFinalTotal').text(result.data.finalTotal);
                        $('input[name=hidden_amount]').val(result.data.totalAmount);
                        $('input[name=hidden_all_tax]').val(JSON.stringify(result.data.admin_tax));
                        $('input[name=hidden_promocode_price]').val(0);
                        $('input[name=hidden_promocode_id]').val(0);
                        $('.totalPrice').text(result.data.finalTotal);
                        $('.dispBill').html('');
                        result.data.admin_tax.forEach(element =>
                        {
                            $('#taxCharge').after('<tr class="Border dispBill"><td class="leftTd">'+element.name+'</td> <td class="rightTd">'+result.currency+'<span class="dispBillTex">'+element.tax+'</span></td></tr>');
                        });
                    }
                },
                error: function (err) {
                    console.log('err ', err)
                }
            });
    }
    else {
        $('.show_alert').show();
        $('.display').text('please first select user');
    }
}

function applyCoupen() {

    $('.display_coupen').removeClass("hide");
    $('.display_coupen').addClass("cardBottom");
    $('.total_bill').addClass("hide");
    $('.total_bill').removeClass("cardBottom");
    // $.ajax(
    // {
    //     headers:
    //     {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     type: "GET",
    //     url: base_url + '/branch_manager/applyCoupen',
    //     // data:
    //     // {
    //     //     menu_id: menu_id,
    //     // },
    //     success: function (result)
    //     {
    //         if (result.success == true)
    //         {
    //             $('.SubmenuCard').html('<div class="table-responsive p-3 qtyTable"><table class="table DisplayCoupen"></table></div>');
    //             result.data.forEach(element =>
    //             {
    //                 var expiryDate = element.start_end_date.split(" - ");
    //                 $('.DisplayCoupen').append('<tr class="Border p-4"><td class="leftTd"><p class="couponCode">'+element.promo_code+'</p><p class="couponDiscri">'+element.promo_code+'</p><p class="couponExpire">valid up to '+expiryDate+'</p></td><td class="rightTd"><a class="applyBtn" onclick="displayBillWithCoupen()">APPLY</a></td></tr>');
    //             });
    //         }
    //     }
    // });
}

function displayBillWithCoupen(id)
{
    var user_id = $('input[type=radio]:checked').val();
    var tax = $('input[name=hidden_all_tax]').val();
    $.ajax(
    {
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/vendor/displayBillWithCoupen',
        data:
        {
            promo_id: id,
            user_id: user_id,
            tax: tax,
            amount: $('input[name=hidden_amount]').val(),
        },
        success: function (result)
        {
            if (result.success == true) {
                $('.display_bill_with_coupen').removeClass("hide");
                $('.display_bill_with_coupen').addClass("cardBottom");
                $('.display_coupen').addClass("hide");
                $('.display_coupen').removeClass("cardBottom");
                $('.CoupenTotalAmount').text(result.data.totalAmount);
                $('.CoupenFinalTotal').text(result.data.finalTotal);
                $('.CoupenDiscount').text(result.data.discount);
                $('.CoupenGrandTotal').text(result.data.grandTotal);
                $('input[name=hidden_promocode_price]').val(result.data.discount);
                $('input[name=hidden_promocode_id]').val(result.data.procode_id);
                $('.totalPrice').text(result.data.grandTotal);
                $('.coupenTotalDisplay').text(result.promo.promo_code);
                if(!Array.isArray(result.data.tax))
                {
                    var t = JSON.parse(result.data.tax);
                    t.forEach(element =>
                    {
                        $('#taxChargeWithCoupen').after('<tr class="Border dispBill"><td class="leftTd">'+element.name+'</td> <td class="rightTd">'+result.currency+'<span class="dispBillTex">'+element.tax+'</span></td></tr>');
                    });
                }
            }
            else {
                Swal.fire({
                    text: result.data,
                });
                displayBill();
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function confirm_order()
{
    $.ajax(
    {
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data:
        {
            amount: $('input[name=hidden_amount]').val(),
            tax: $('input[name=hidden_all_tax]').val(),
            user_id: $('input[type=radio]:checked').val(),
            promocode_id: $('input[name=hidden_promocode_id]').val(),
            promocode_price: $('input[name=hidden_promocode_price]').val(),
        },
        url: base_url + '/vendor/order',
        success: function (result)
        {
            if (result.success == true)
            {
                location.replace(base_url + '/vendor/Orders');
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function cart(id, operation)
{
    var qty = parseFloat($('.qty' + id).text());
    var original_price = parseFloat($('#original_price' + id).val());
    if (operation == 'plus') {
        $('#minus' + id).prop('disabled', false);
    }
    else {
        if (qty == 0) {
            $('#minus' + id).prop('disabled', true);
        }
        else {
            $('#minus' + id).prop('disabled', false);
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/vendor/cart',
        data:
        {
            id: id,
            price: original_price,
            operation: operation,
        },  
        success: function (result)
        {
            if (result.success == true) {
                $('.displayTotalItem').text(result.data.total_item);
                $('.totalPrice').text(result.data.grand_total);
                $('.qty' + id).text(result.data.qty);
                $('.itemPrice' + id).text(result.data.itemPrice);
                $('#orderQty' + id).append('<span class="orderQty">' + result.data.qty + '</span>');
                console.log($('.custimization' + id).is(":visible"));
                if (result.data.qty >= 1) {
                    if ($('.custimization' + id).is(":visible") == false) {
                        $('.custimization' + id).show();
                    }
                    if (result.data.qty <= 0) {
                        $('.custimization' + id).hide();
                    }
                }
            }
            else {
                swal({
                    text: result.data,
                });
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function DispCustimization(submenu_id) {
    $.ajax(
    {
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: base_url + '/vendor/DispCustimization/' + submenu_id,
        success: function (result)
        {
            if (result.success == true)
            {
                $('.display_custimization').removeClass("hide");
                $('.display_custimization').addClass("cardBottom");
                $('.display_menu').addClass("hide");
                $('.display_menu').removeClass("cardBottom");
                $('.repeat_order').addClass("hide");
                $('.repeat_order').removeClass("cardBottom");
                $('.session_menu').addClass("hide");
                $('.session_menu').removeClass("cardBottom");
                $('.total_bill').addClass("hide");
                $('.total_bill').removeClass("cardBottom");
                $('.display_coupen').addClass("hide");
                $('.display_coupen').removeClass("cardBottom");
                $('.display_bill_with_coupen').addClass("hide");
                $('.display_bill_with_coupen').removeClass("cardBottom");
                $('.show_user').addClass("hide");
                $('.show_user').removeClass("cardBottom");
                $('.displayCustimization').html('');
                if (result.data.item.length > 0) {
                    $.each(result.data.item, function (i, element)
                    {
                        $('input[name=submenu_id]').val(element.submenu_id);
                        $('.displayCustimization').append('<table class="table custimization_table"><tr><td class="cust_items' + i + '"><hr><input type="checkbox" value=' + element.name + ' id="custchkbox' + i + '" name="custimization' + element.name + '"><label for="custchkbox' + i + '">' + element.name + '</label><hr></td></tr></table>');
                        if (element.custimazation_item != null) {
                            var c = JSON.parse(element.custimazation_item);
                            $.each(c, function (j, item) {
                                var check = '';
                                if (item.isDefault == 1) {
                                    check = 'checked';
                                }

                                $('.cust_items' + i).append('<tr class="radios' + i + '"><td><label for="radio' + j + '">' + item.name + '</label></td><td class="text-right w-100"><label for="radio' + j + '">' + result.currency + item.price + '</label>&nbsp;<input id="radio' + i + '_' + j + '" name="cust_item' + element.id + '" type="radio" ' + check + ' value="' + item.name + '_' + item.price + '"></td></tr>');
                            });
                            if (result.data.session != "") {
                                $.each(c, function (j, item) {
                                    if (result.data.session != "") {
                                        var s = JSON.parse(result.data.session);
                                        $.each(s, function (k, session) {
                                            if (session.main_menu == $('input[type=checkbox][name="custimization' + element.name + '"]').val()) {
                                                $('input[type=checkbox][name="custimization' + element.name + '"]').prop("checked", true);
                                            }
                                            if (session.data.name + '_' + session.data.price == $('input[type=radio][id="radio' + i + '_' + j + '"]').val()) {
                                                $('input[type=radio][id="radio' + i + '_' + j + '"]').prop("checked", true);
                                            }
                                        });
                                    }
                                });
                            }
                        }
                        else {
                            $('.displayCustimization').append('No custimization available');
                        }
                    });
                }
                else {
                    $('.displayCustimization').append('<h5 class="text-center">No custimization available</h5>');
                }
            }
        },
        error: function (err) {
            console.log('err ', err)
        }
    });
}

function UserBtn() {
    $.ajax(
    {
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/vendor/add_user',
        data:
        {
            name: $('#user_name').val(),
            email_id: $('#email').val(),
            phone: $('#phone').val(),
        },
        success: function (result) {
            console.log('result.success == true', result.success == true);
            if (result.success == true) {
                $('.show_user').removeClass("hide");
                $('.show_user').addClass("cardBottom");
                $('.add_user').addClass("hide");
                $('.add_user').removeClass("cardBottom");
                $('.displayUser').append('<li class="media p-3 single_record"><img alt="image" width="50" src=' + result.data.image + ' class="mr-3 rounded-circle"> <div class="media-body"><div class="media-title">' + result.data.name + '</div> <div class="text-job text-muted">' + result.data.email_id + '<br></div>' + result.data.phone + '</div> <div class="media-item"><div class="media-value"><input type="radio" value=' + result.data.id + ' id="chkbox' + result.data.id + '" name="user"></div></div></li>');
            }
        },
        error: function (err) {
            // $('.show_user').show();
            // $('.add_user').addClass("hide");
            for (let v1 of Object.keys(err.responseJSON.errors)) {
                // $('.show_alert').show();
                $('.custom_error .'+v1).text(err.responseJSON.errors[v1]);
            }
        }
    });
}

function data_search()
{
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search");
    filter = input.value.toUpperCase();
    table = document.getElementById("sort_location");
    tr = table.getElementsByClassName("single_record");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByClassName("media-title")[0];
        if (td)
        {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) == -1) {
                tr[i].style.setProperty('display', 'none', 'important');
            }
            else
            {
                tr[i].style.display = "flex";
            }
        }
    }
}
