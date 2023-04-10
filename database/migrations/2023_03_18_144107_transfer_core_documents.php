<?php

use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Payments\Models\Payment;
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

        $coredoctypes = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder'];

        foreach ($coredoctypes as $coredoctype) {
            $modtype = '\\BT\Support\\SixtoSeven\\Models\\' . $coredoctype;
            $docs = $modtype::withTrashed()->with('amount', 'items.amount', 'custom')->get();

            foreach ($docs as $doc) {
                $document = new \BT\Modules\Documents\Models\Document();
                $document->document_type = 'BT\\Modules\\Documents\\Models\\' . $coredoctype;
                $document->document_id = $doc->id;
                $document->document_date = $doc->quote_date ?? $doc->workorder_date ?? $doc->invoice_date ?? $doc->purchaseorder_date;
                $document->workorder_id = $doc->workorder_id ?? 0;
                $document->invoice_id = $doc->invoice_id ?? 0;
                $document->user_id = $doc->user_id;
                $document->client_id = $doc->client_id ?? $doc->vendor_id;
                $document->group_id = $doc->group_id;
                $document->document_status_id = $doc->quote_status_id ?? $doc->workorder_status_id ?? $doc->invoice_status_id ?? $doc->purchaseorder_status_id;
                $document->action_date = $doc->expires_at ?? $doc->due_at;
                $document->number = $doc->number;
                $document->footer = $doc->footer;
                $document->url_key = $doc->url_key;
                $document->currency_code = $doc->currency_code;
                $document->exchange_rate = $doc->exchange_rate;
                $document->terms = $doc->terms;
                $document->template = $doc->template;
                $document->summary = $doc->summary;
                $document->viewed = $doc->viewed;
                $document->discount = $doc->discount;
                $document->job_date = $doc->job_date ?? null;
                $document->start_time = $doc->start_time ?? null;
                $document->end_time = $doc->end_time ?? null;
                $document->will_call = $doc->will_call ?? 0;
                $document->company_profile_id = $doc->company_profile_id;
                $document->deleted_at = $doc->deleted_at;
                $document->created_at = $doc->created_at;
                $document->updated_at = $doc->updated_at;

                $document->saveQuietly();


                $documentamount = new \BT\Modules\Documents\Models\DocumentAmount();

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

                $custidfield = strtolower($coredoctype) . '_id';

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
        //move invoice status_id to document statuses
        $invoices = Invoice::get();

        foreach ($invoices as $invoice) {
            if ($invoice->document_status_id == 3) {
                $invoice->document_status_id = 6;
            }
            if ($invoice->document_status_id == 4) {
                $invoice->document_status_id = 5;
            }
            $invoice->updateQuietly();
        }
        //move purchaseorder status_id to document statuses
        $purchaseorders = Purchaseorder::get();

        foreach ($purchaseorders as $purchaseorder) {
            if ($purchaseorder->document_status_id == 3) {
                $purchaseorder->document_status_id = 7;
            }
            if ($purchaseorder->document_status_id == 4) {
                $purchaseorder->document_status_id = 8;
            }
            if ($purchaseorder->document_status_id == 5) {
                $purchaseorder->document_status_id = 6;
            } elseif ($purchaseorder->document_status_id == 6) {
                $purchaseorder->document_status_id = 5;
            }
            $purchaseorder->updateQuietly();
        }

        //update quote workorder_id and invoice_id refs to new documents
        $quotes = Quote::get();

        foreach ($quotes as $quote) {
            if ($quote->workorder_id > 0 || $quote->invoice_id > 0) {
                if ($quote->workorder_id > 0) {
                    $quotedoc = Workorder::where('document_id', $quote->workorder_id)->first();
                    $quote->workorder_id = $quotedoc->id;
                }
                if ($quote->invoice_id > 0) {
                    $invoicedoc = Invoice::where('document_id', $quote->invoice_id)->first();
                    $quote->invoice_id = $invoicedoc->id;
                }
                $quote->updateQuietly();
            }
        }

        //update workorder  invoice_id refs to new documents
        $workorders = Workorder::get();

        foreach ($workorders as $workorder) {
            if ($workorder->invoice_id > 0) {
                $invoicedoc = Invoice::where('document_id', $workorder->invoice_id)->first();
                $workorder->invoice_id = $invoicedoc->id;
                $workorder->updateQuietly();
            }
        }

        //update payment invoice_id to new documents
        $payments = Payment::get();

        foreach ($payments as $payment) {
            if ($payment->invoice_id > 0) {
                $invoicedoc = Invoice::where('document_id', $payment->invoice_id)->first();
                $payment->invoice_id = $invoicedoc->id;
                $payment->updateQuietly();
            }
        }

        //update timetrackingtasks invoice_id to new documents
        $timetrackingtasks = TimeTrackingTask::get();

        foreach ($timetrackingtasks as $timetrackingtask) {
            if ($timetrackingtask->invoice_id > 0) {
                $invoicedoc = Invoice::where('document_id', $timetrackingtask->invoice_id)->first();
                $timetrackingtask->invoice_id = $invoicedoc->id;
                $timetrackingtask->updateQuietly();
            }
        }

        //update expenses invoice_id to new documents
        $expenses = Expense::get();

        foreach ($expenses as $expense) {
            if ($expense->invoice_id > 0) {
                $invoicedoc = Invoice::where('document_id', $expense->invoice_id)->first();
                $expense->invoice_id = $invoicedoc->id;
                $expense->updateQuietly();
            }
        }

        //remove temporary column
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('document_id');
        });

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
