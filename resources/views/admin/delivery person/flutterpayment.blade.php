<html>
    <body onload="document.forms['member_signup'].submit()">
        <form method="POST" name="member_signup" action="https://checkout.flutterwave.com/v3/hosted/pay">
            <input type="hidden" name="public_key" value="{{ App\Models\PaymentSetting::first()->public_key }}" />
            <input type="hidden" name="customer[email]" value="{{ $data['email'] }}" />
            <input type="hidden" name="customer[phone_number]" value="{{ $data['phone'] }}" />
            <input type="hidden" name="customer[name]" value="{{ $data['name'] }}" />
            <input type="hidden" name="tx_ref" value="bitethtx-019203" />
            <input type="hidden" name="amount" value="{{ $data['amount'] }}"  />
            <input type="hidden" name="currency" value="{{ App\Models\GeneralSetting::first()->currency }}" />
            <input type="hidden" name="meta[token]" value="20" />
            <input type="hidden" name="redirect_url" value="{{ url('admin/driver_transction/'.$data['duration'].'/'.$data['driver']) }}" />
            <button type="submit">CHECKOUT</button>
        </form>
    </body>
</html>
