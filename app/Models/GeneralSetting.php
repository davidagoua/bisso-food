<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $table = 'general_setting';

    protected $fillable = ['business_name','cancel_reason','company_black_logo','favicon','country','timezone','customer_notification','customer_app_id','customer_auth_key','customer_api_key','vendor_notification','vendor_app_id','vendor_auth_key','vendor_api_key','driver_notification','driver_app_id','driver_auth_key','driver_api_key','currency','currency_symbol','start_time','end_time','business_availability','message','isItemTax','item_tax','privacy_policy', 'company_details','terms_and_condition','help','about_us','site_color','driver_vehical_type','driver_earning','verification','verification_phone','verification_email','twilio_acc_id','twilio_auth_token','twilio_phone_no','radius','driver_auto_refrese','mail_mailer','mail_host','mail_username','mail_password','mail_encryption','mail_from_address','mail_port','customer_mail','vendor_mail','driver_mail','ios_customer_version','ios_vendor_version','ios_driver_version','ios_customer_app_url','ios_vendor_app_url','ios_driver_app_url','android_customer_version','android_vendor_version','android_driver_version','android_customer_app_url','android_vendor_app_url','android_driver_app_url','map_key','license_code','client_name','license_verify','isPickup','is_debbug_mode'];

    protected $appends = ['blacklogo'];

    public function getBlackLogoAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['company_black_logo'];
    }
}