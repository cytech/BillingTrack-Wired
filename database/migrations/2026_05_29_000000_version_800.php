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
        Setting::deleteByKey('pdfBinaryPath');
        Setting::deleteByKey('pdfDriver');
        Setting::saveByKey('version', '8.0.0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
