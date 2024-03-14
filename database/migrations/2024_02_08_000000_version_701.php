<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class Version701 extends Migration
{
    /**
     * Run the migrations.
     *
     * @table payments_custom
     *
     * @return void
     */
    public function up()
    {
        Setting::saveByKey('merchant_StripeV3_enabled','0');
        Setting::saveByKey('merchant_StripeV3_paymentButtonText','Pay with StripeV3');
        Setting::saveByKey('merchant_StripeV3_publishableKey','');
        Setting::saveByKey('merchant_StripeV3_secretKey','');
        Setting::saveByKey('merchant_Square_enabled','0');
        Setting::saveByKey('merchant_Square_paymentButtonText','Pay with Square');
        Setting::saveByKey('merchant_Square_applicationId','');
        Setting::saveByKey('merchant_Square_accessToken','');
        Setting::saveByKey('merchant_Square_locationId','');

        Setting::saveByKey('version', '7.0.1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::saveByKey('version', '7.0.0');
    }
}
