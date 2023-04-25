<?php

use BT\Modules\Settings\Models\Setting;
use BT\Support\Directory;
use BT\Support\SixtoSeven\ConvertCustomTemplates;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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

        Schema::table('purchaseorders_custom', function (Blueprint $table) {
            $table->dropForeign('purchaseorders_custom_purchaseorder_id');
            $table->foreign('purchaseorder_id', 'purchaseorders_custom_purchaseorder_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('recurringinvoices_custom', function (Blueprint $table) {
            $table->dropForeign('recurring_invoices_custom_recurring_invoice_id');
        });

        Schema::table('recurringinvoices_custom', function (Blueprint $table) {
            $table->foreign('recurringinvoice_id', 'recurringinvoices_custom_recurringinvoice_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign('fk_expenses_invoices1_idx');
            $table->foreign('invoice_id', 'fk_expenses_invoices1_idx')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('time_tracking_tasks', function (Blueprint $table) {
            $table->dropForeign('time_tracking_tasks_invoice_id_index');
            $table->foreign('invoice_id', 'time_tracking_tasks_invoice_id_index')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_invoice_id_index');
            $table->foreign('invoice_id', 'payments_invoice_id_index')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign('fk_expenses_invoices1_idx');
        });

        Schema::disableForeignKeyConstraints();
        //delete obsolete tables
        $droptables = ['invoices', 'invoice_amounts', 'invoice_items', 'invoice_item_amounts',
            'quotes', 'quote_amounts', 'quote_items', 'quote_item_amounts',
            'workorders', 'workorder_amounts', 'workorder_items', 'workorder_item_amounts',
            'purchaseorders', 'purchaseorder_amounts', 'purchaseorder_items', 'purchaseorder_item_amounts',
            'invoice_transactions', 'recurring_invoices', 'recurring_invoice_amounts', 'recurring_invoice_items',
            'recurring_invoice_item_amounts'];

        foreach ($droptables as $droptable) {
            Schema::dropIfExists($droptable);
        }
        Schema::enableForeignKeyConstraints();

        // copy and convert user custom templates
        // copies existing custom templates (except for custom.blade.php) to same name with 'V7' prefix
        // then replaces new V7 file variable ($quote, $workorder, $invoice, $purchaseorder) with $document

        ConvertCustomTemplates::copy();
        ConvertCustomTemplates::update();

        Setting::saveByKey('workorderEmailSubject','Workorder #{{ $workorder->number }}');
        Setting::saveByKey('workorderEmailBody','<p>To view your workorder from {{ $workorder->user->name }} for {{ $workorder->amount->formatted_total }}, click the link below:</p> <p><a href="{{ $workorder->public_url }}">{{ $workorder->public_url }}</a></p>');
        Setting::saveByKey('workorderApprovedEmailBody','<p><a href="{{ $workorder->public_url }}">Workorder #{{ $workorder->number }}</a> has been APPROVED.</p>');
        Setting::saveByKey('workorderRejectedEmailBody','<p><a href="{{ $workorder->public_url }}">Workorder #{{ $workorder->number }}</a> has been REJECTED.</p>');
        Setting::writeEmailTemplates();

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
