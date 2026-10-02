<?php

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::saveByKey('mailScheme', null);

        $driver = Setting::getByKey('mailDriver');

        if ($driver == 'smtp') {
            $encryption = Setting::getByKey('mailEncryption');
            match ($encryption) {
                'ssl' => Setting::saveByKey('mailScheme', 'smtps'),
                'tls' => Setting::saveByKey('mailScheme', 'smtp'),
                '0' => Setting::saveByKey('mailScheme', null),
                default => Setting::saveByKey('mailScheme', null),
            };
        }

        Setting::deleteByKey('mailEncryption');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Setting::deleteByKey('mailScheme');
    }
};
