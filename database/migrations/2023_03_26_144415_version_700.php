<?php

use BT\Modules\Settings\Models\Setting;
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
        Schema::table('quotes_custom', function (Blueprint $table) {
            $table->dropForeign('quotes_custom_quote_id');
            $table->foreign('quote_id', 'quotes_custom_quote_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('workorders_custom', function (Blueprint $table) {
            $table->dropForeign('workorders_custom_workorder_id');
            $table->foreign('workorder_id', 'workorders_custom_workorder_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('invoices_custom', function (Blueprint $table) {
            $table->dropForeign('invoices_custom_invoice_id');
            $table->foreign('invoice_id', 'invoices_custom_invoice_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Setting::saveByKey('version', '7.0.0');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
