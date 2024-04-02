<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('flag_text',50)->nullable()->after('paymentterm_id');
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('flag_text',50)->nullable()->after('paymentterm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('flag_text');
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('flag_text');
        });
    }
};
