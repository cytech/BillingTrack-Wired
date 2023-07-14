<?php

use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\Settings\Models\Setting;
use BT\Modules\TimeTracking\Models\TimeTrackingTask;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\Workorder;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Purchaseorder;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // drop client_id foreign on payments (for client vendor share..)

        Schema::table('payments', function (Blueprint $table){
            $table->dropForeign('fk_payments_clients1_idx');
        });

        if (!Schema::hasTable('recurringinvoices_custom')) {
            Schema::rename('recurring_invoices_custom', 'recurringinvoices_custom');

            Schema::table('recurringinvoices_custom', function (Blueprint $table) {
                $table->dropForeign('recurring_invoices_custom_recurring_invoice_id');
                $table->renameColumn('recurring_invoice_id', 'recurringinvoice_id');
                $table->foreign('recurringinvoice_id', 'recurringinvoices_custom_recurringinvoice_id')
                    ->references('id')->on('documents')
                    ->onDelete('cascade')
                    ->onUpdate('restrict');
            });
        }


        $coredoctypes = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder', 'RecurringInvoice'];

        //modify customfield tables
        foreach ($coredoctypes as $coredoctype) {
            Schema::whenTableDoesntHaveColumn(strtolower($coredoctype) . 's_custom', strtolower($coredoctype) . '_id_v7',
                function (Blueprint $table) use ($coredoctype) {
                    $table->unsignedInteger(strtolower($coredoctype) . '_id_v7')->after(strtolower($coredoctype) . '_id');
                });
        }

        //clean orphans
        //$docs = $modtype::withTrashed()->doesntHave('invoice')->count();

        foreach ($coredoctypes as $coredoctype) {
            $modtype = '\\BT\Support\\SixtoSeven\\Models\\' . $coredoctype;

            $docs = $modtype::withTrashed()->with(
                [
                    'amount'       => function ($query) {
                        return $query->withTrashed();
                    },
                    'items.amount' => function ($query) {
                        return $query->withTrashed();
                    },
                    'custom'       => function ($query) {
                        return $query->withTrashed();
                    }
                ]
            )->get();

            foreach ($docs as $doc) {
                $document = new \BT\Modules\Documents\Models\Document();
                $document->document_type = 'BT\\Modules\\Documents\\Models\\' . ucfirst(strtolower($coredoctype));
                $document->document_id = $doc->id;
                $document->document_date = $doc->quote_date ?? $doc->workorder_date ?? $doc->invoice_date ?? $doc->purchaseorder_date ?? '0000-00-00';
                $document->workorder_id = $doc->workorder_id ?? null;
                $document->invoice_id = $doc->invoice_id ?? null;
                $document->user_id = $doc->user_id;
                $document->client_id = $doc->client_id ?? $doc->vendor_id;
                $document->company_profile_id = $doc->company_profile_id;
                $document->group_id = $doc->group_id ?? null;
                $document->document_status_id = $doc->quote_status_id ?? $doc->workorder_status_id ?? $doc->invoice_status_id ?? $doc->purchaseorder_status_id ?? 9;
                $document->action_date = $doc->expires_at ?? $doc->due_at ?? '0000-00-00';
                $document->number = $doc->number ?? 0;
                $document->footer = $doc->footer;
                $document->url_key = $doc->url_key ?? '';
                $document->currency_code = $doc->currency_code;
                $document->exchange_rate = $doc->exchange_rate;
                $document->terms = $doc->terms;
                $document->template = $doc->template;
                $document->summary = $doc->summary;
                $document->viewed = $doc->viewed ?? 0;
                $document->discount = $doc->discount;
                $document->job_date = $doc->job_date ?? null;
                $document->start_time = $doc->start_time ?? null;
                $document->end_time = $doc->end_time ?? null;
                $document->will_call = $doc->will_call ?? 0;
                $document->recurring_frequency = $doc->recurring_frequency ?? null;
                $document->recurring_period = $doc->recurring_period ?? null;
                $document->next_date = $doc->next_date ?? null;
                $document->stop_date = $doc->stop_date ?? null;
                $document->deleted_at = $doc->deleted_at;
                $document->created_at = $doc->created_at;
                $document->updated_at = $doc->updated_at;

                $document->saveQuietly();


                $documentamount = new \BT\Modules\Documents\Models\DocumentAmount();
//                $message = $modtype . $doc->id;
//                Log::error($message);
                $documentamount->document_id = $document->id;
                $documentamount->subtotal = $doc->amount->subtotal;
                $documentamount->discount = $doc->amount->discount;
                $documentamount->tax = $doc->amount->tax;
                $documentamount->total = $doc->amount->total;
                $documentamount->paid = $doc->amount->paid ?? 0;
                $documentamount->balance = $doc->amount->balance ?? 0;
                $documentamount->deleted_at = $doc->amount->deleted_at;
                $documentamount->created_at = $doc->amount->created_at;
                $documentamount->updated_at = $doc->amount->updated_at;

                $documentamount->saveQuietly();

                $custidfield = strtolower($coredoctype) . '_id_v7';

                if ($doc->custom) {
                    $doc->custom->$custidfield = $document->id;
                    $doc->custom->saveQuietly();
                }

                foreach ($doc->items as $docitem) {
                    $documentitem = new \BT\Modules\Documents\Models\DocumentItem();

                    $documentitem->document_id = $document->id;
                    $documentitem->tax_rate_id = $docitem->tax_rate_id;
                    $documentitem->tax_rate_2_id = $docitem->tax_rate_2_id;
                    $documentitem->resource_table = $docitem->resource_table;
                    $documentitem->resource_id = $docitem->resource_id;
                    $documentitem->is_tracked = $doc->is_tracked ?? 0;
                    $documentitem->name = $docitem->name;
                    $documentitem->description = $docitem->description;
                    $documentitem->quantity = $docitem->quantity;
                    $documentitem->display_order = $docitem->display_order;
                    $documentitem->price = $docitem->price ?? $docitem->cost;
                    $documentitem->rec_qty = $docitem->rec_qty ?? 0;
                    $documentitem->rec_status_id = $docitem->rec_status_id ?? 0;
                    $documentitem->deleted_at = $docitem->deleted_at;
                    $documentitem->created_at = $docitem->created_at;
                    $documentitem->updated_at = $docitem->updated_at;

                    $documentitem->saveQuietly();


                    $documentitemamount = new \BT\Modules\Documents\Models\DocumentItemAmount();

                    $documentitemamount->item_id = $documentitem->id;
                    $documentitemamount->subtotal = $docitem->amount->subtotal;
                    $documentitemamount->tax_1 = $docitem->amount->tax_1;
                    $documentitemamount->tax_2 = $docitem->amount->tax_2;
                    $documentitemamount->tax = $docitem->amount->tax;
                    $documentitemamount->total = $docitem->amount->total;
                    $documentitemamount->deleted_at = $docitem->amount->deleted_at;
                    $documentitemamount->created_at = $docitem->amount->created_at;
                    $documentitemamount->updated_at = $docitem->amount->updated_at;

                    $documentitemamount->saveQuietly();

                }
            }
        }

        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public
    function down(): void
    {
        //
    }
};
