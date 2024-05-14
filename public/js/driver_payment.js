var base_url = $('#mainurl').val();
var amount = $('input[name=amount]').val();
var duration = $('input[name=duration]').val();
var currency = $('input[name=hidden_currency]').val();
var driver = $('input[name=driver_person]').val();

var orderData;

$(document).ready(function ()
{
    orderData = new FormData();
    orderData.append('driver_payment_type','COD');
    orderData.append('driver',driver)
    orderData.append('duration',duration);
    $('input[id=paypal]').change(function ()
    {
        orderData.append('driver_payment_type','PAYPAL');
        $('.paypal_card').show();
        $('.cod_card').hide();
        $('.stripe_card').hide();
        $('.flutter_card').hide();
        $('.razor_card').hide();
        paypalPayment();
    });

    $('input[id=razor]').change(function ()
    {
        orderData.append('driver_payment_type','RAZOR');
        $('.paypal_card').hide();
        $('.cod_card').hide();
        $('.stripe_card').hide();
        $('.flutter_card').hide();
        $('.razor_card').show();
        RazorPayPayment();
    });

    // *********** Stripe Payment ***********
    $('input[id=stripe]').change(function ()
    {
        orderData.append('driver_payment_type','STRIPE');
        $('.paypal_card').hide();
        $('.cod_card').hide();
        $('.stripe_card').show();
        $('.flutter_card').hide();
        $('.razor_card').hide();
        var month = $('.expiry-date').val().split('/')[0];
        var year = $('.expiry-date').val().split('/')[1];
        $('.card-expiry-month').val(month);
        $('.card-expiry-year').val(year);
        StripPayment();
    });

    $('input[id=flutterwave]').change(function ()
    {
        orderData.append('driver_payment_type','FLUTTERWAVE');
        $('.paypal_card').hide();
        $('.cod_card').hide();
        $('.stripe_card').hide();
        $('.flutter_card').show();
        $('.razor_card').hide();
    });

    $('input[id=cod]').change(function ()
    {
        orderData.append('driver_payment_type','COD');
        $('.paypal_card').hide();
        $('.cod_card').show();
        $('.stripe_card').hide();
        $('.flutter_card').hide();
        $('.razor_card').hide();
    });
});

function stripeResponseHandler(status, response)
{
    if (response.error) {
        $('.stripe_alert').show();
        $('.stripe_alert').text(response.error.message);
    }
    else
    {
        $('.loading').show();
        var token = response['id'];
        $form.find('input[type=text]').empty();
        $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
        var paymentData = new FormData($('#stripe-payment-form')[0]);
        paymentData.append('payment',amount);
        paymentData.append('duration',duration);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: base_url + '/admin/stripePayment',
            data: paymentData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result)
            {
                if (result.success == true)
                {
                    orderData.append('driver_payment_token',result.data);
                    driver_settlement();
                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Payment not complete",
                    }
                )}
            },
            error: function (err)
            {
                console.log(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err.responseJSON.message,
                })
            }
        });
    }
}

function paypalPayment()
{
    if(currency != 'INR')
    {
        $('.paypal_card_body').html('');
        paypal_sdk.Buttons({
            createOrder: function (data, actions)
            {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount
                        }
                    }]
                });
            },
            onApprove: function (data, actions)
            {
                $('.loading').show();
                return actions.order.capture().then(function (details)
                {
                    orderData.append('driver_payment_token', details.id);
                    orderData.append('payment_type', 'PAYPAL');
                    driver_settlement();
                });
            }
        }).render('.paypal_card_body');
    }
    else
    {
        $('.paypal_card_body').html('INR currency not supported in Paypal');
    }
}

function RazorPayPayment()
{
    var options =
    {
        key: $('#RAZORPAY_KEY').val(),
        amount: amount * 100,
        description: '',
        currency: currency,
        image: 'https://i.imgur.com/n5tjHFD.png',
        handler: demoSuccessHandler
    }
    window.r = new Razorpay(options);
    document.getElementById('paybtn').onclick = function ()
    {
        $('.loading').show();
        r.open();
    }
}

function driver_settlement()
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: base_url + '/admin/driver_settle',
        data: orderData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (result)
        {
            if (result.success == true)
            {
                window.location.replace(base_url + '/admin/delivery_person_finance_details/'+result.data);
            }
            else
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "Payment not complete",
                }
            )}
        },
        error: function (err)
        {
            console.log(err);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: err.responseJSON.message,
            })
        }
    });
}

function StripPayment()
{
    // $(function () {
        $form = $(".require-validation");
        $('form.require-validation').bind('submit', function (e)
        {
            $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
            'input[type=text]', 'input[type=file]',
            'textarea'].join(', '),
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
            $errorMessage.addClass('hide');

            $('.has-error').removeClass('has-error');
            $inputs.each(function (i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            var month = $('.expiry-date').val().split('/')[0];
            var year = $('.expiry-date').val().split('/')[1];
            $('.card-expiry-month').val(month);
            $('.card-expiry-year').val(year);

            if (!$form.data('cc-on-file'))
            {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                var ccNum = $('.card-number').val(),
                cvcNum = $('.card-cvc').val(),
                expMonth = $('.card-expiry-month').val(),
                expYear = $('.card-expiry-year').val();

                // if (!Stripe.card.validateCardNumber(ccNum)) {
                //     $('.stripe-error').css('display', 'inline')
                //     $('.stripe-error')
                //     .removeClass('hide')
                //     .text('The credit card number appears to be invalid.');

                // }

                // if (!Stripe.card.validateCVC(cvcNum)) {
                //     $('.stripe-error').css('display', 'inline')
                //     $('.stripe-error')
                //     .removeClass('hide')
                //     .text('The CVC number appears to be invalid.');
                // }

                // if (!Stripe.card.validateExpiry(expMonth, expYear)) {
                //     $('.stripe-error').css('display', 'inline')
                //     $('.stripe-error')
                //     .removeClass('hide')
                //     .text('The expiration date appears to be invalid.');
                // }

                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }
        });
    // });
}

function padStart(str) {
    return ('0' + str).slice(-2)
}

function demoSuccessHandler(transaction)
{
    $("#paymentDetail").removeAttr('style');
    $('#paymentID').text(transaction.razorpay_payment_id);
    var paymentDate = new Date();
    $('#paymentDate').text(
        padStart(paymentDate.getDate()) + '.' + padStart(paymentDate.getMonth() + 1) + '.' + paymentDate.getFullYear() + ' ' + padStart(paymentDate.getHours()) + ':' + padStart(paymentDate.getMinutes())
    );
    orderData.append('driver_payment_token', transaction.razorpay_payment_id);
    driver_settlement();
}
