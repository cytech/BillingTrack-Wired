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
        Setting::saveByKey('version', '8.0.1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Setting::saveByKey('version', '8.0.0');
    }
};
