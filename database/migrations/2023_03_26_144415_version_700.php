<?php

use BT\Modules\Activity\Models\Activity;
use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\MailQueue\Models\MailQueue;
use BT\Modules\Notes\Models\Note;
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
        // delete orphaned custom records
        $custom_tables = ['quote', 'workorder', 'invoice', 'purchaseorder', 'recurringinvoice' ];
        foreach ($custom_tables as $ct) {
            DB::table($ct . 's_custom')->where($ct . '_id_v7', 0)->delete();
        }

        Schema::table('quotes_custom', function (Blueprint $table) {
            $table->dropForeign('quotes_custom_quote_id');
        });
        Schema::table('quotes_custom', function (Blueprint $table) {
            $table->integer('quote_id')->unsigned()->change();
            $table->dropPrimary('quote_id');
            $table->dropColumn('quote_id');
            $table->renameColumn('quote_id_v7', 'quote_id');
            $table->primary('quote_id')->change();

            $table->foreign('quote_id', 'quotes_custom_quote_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('workorders_custom', function (Blueprint $table) {
            $table->dropForeign('workorders_custom_workorder_id');
        });
        Schema::table('workorders_custom', function (Blueprint $table) {
            $table->integer('workorder_id')->unsigned()->change();
            $table->dropPrimary('workorder_id');
            $table->dropColumn('workorder_id');
            $table->renameColumn('workorder_id_v7', 'workorder_id');
            $table->primary('workorder_id')->change();

            $table->foreign('workorder_id', 'workorders_custom_workorder_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('invoices_custom', function (Blueprint $table) {
            $table->dropForeign('invoices_custom_invoice_id');
        });
        Schema::table('invoices_custom', function (Blueprint $table) {
            $table->integer('invoice_id')->unsigned()->change();
            $table->dropPrimary('invoice_id');
            $table->dropColumn('invoice_id');
            $table->renameColumn('invoice_id_v7', 'invoice_id');
            $table->primary('invoice_id')->change();

            $table->foreign('invoice_id', 'invoices_custom_invoice_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('purchaseorders_custom', function (Blueprint $table) {
            $table->dropForeign('purchaseorders_custom_purchaseorder_id');
        });
        Schema::table('purchaseorders_custom', function (Blueprint $table) {
            $table->integer('purchaseorder_id')->unsigned()->change();
            $table->dropPrimary('purchaseorder_id');
            $table->dropColumn('purchaseorder_id');
            $table->renameColumn('purchaseorder_id_v7', 'purchaseorder_id');
            $table->primary('purchaseorder_id')->change();

            $table->foreign('purchaseorder_id', 'purchaseorders_custom_purchaseorder_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });

        Schema::table('recurringinvoices_custom', function (Blueprint $table) {
            $table->dropForeign('recurringinvoices_custom_recurringinvoice_id');
        });
        Schema::table('recurringinvoices_custom', function (Blueprint $table) {
            $table->integer('recurringinvoice_id')->unsigned()->change();
            $table->dropPrimary('recurringinvoice_id');
            $table->dropColumn('recurringinvoice_id');
            $table->renameColumn('recurringinvoice_id_v7', 'recurringinvoice_id');
            $table->primary('recurringinvoice_id')->change();

            $table->foreign('recurringinvoice_id', 'recurringinvoices_custom_recurringinvoice_id')
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
            $table->dropForeign('expenses_category_id_index');
            $table->dropIndex('expenses_category_id_index');
        });

        Schema::table('employees', function (Blueprint $table){
            $table->unsignedInteger('type_id')->change();
            $table->index(["type_id"], 'fk_employees_employee_types1_idx');

            $table->foreign('type_id', 'fk_employees_employee_types1_idx')
                ->references('id')->on('employee_types')
                ->onDelete('no action')
                ->onUpdate('no action');
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

        //modify activity, attachment, mail_queue and note morph types
        $coredoctypes = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder', 'RecurringInvoice'];

        foreach ($coredoctypes as $coredoctype) {
            Activity::withTrashed()->where('audit_type', 'BT\\Modules\\' . $coredoctype . 's\\Models\\' . $coredoctype)->update(['audit_type' => 'BT\\Modules\\Documents\\Models\\' . ucfirst(strtolower($coredoctype))]);
            Attachment::withTrashed()->where('attachable_type', 'BT\\Modules\\' . $coredoctype . 's\\Models\\' . $coredoctype)->update(['attachable_type' => 'BT\\Modules\\Documents\\Models\\' . ucfirst(strtolower($coredoctype))]);
            MailQueue::withTrashed()->where('mailable_type', 'BT\\Modules\\' . $coredoctype . 's\\Models\\' . $coredoctype)->update(['mailable_type' => 'BT\\Modules\\Documents\\Models\\' . ucfirst(strtolower($coredoctype))]);
            Note::withTrashed()->where('notable_type', 'BT\\Modules\\' . $coredoctype . 's\\Models\\' . $coredoctype)->update(['notable_type' => 'BT\\Modules\\Documents\\Models\\' . ucfirst(strtolower($coredoctype))]);
        }


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
