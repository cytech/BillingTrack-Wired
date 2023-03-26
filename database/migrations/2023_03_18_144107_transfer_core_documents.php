<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use BT\Modules\Quotes\Models\Quote;
use BT\Modules\Workorders\Models\Workorder;
use BT\Modules\Invoices\Models\Invoice;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        $coredoctypes = [DOCUMENT_TYPE_QUOTE, DOCUMENT_TYPE_WORKORDER, DOCUMENT_TYPE_INVOICE];

        foreach ($coredoctypes as $coredoctype) {
            $modtype = '\\BT\Modules\\' . $coredoctype['module_type'] . 's\\Models\\' . $coredoctype['module_type'];
            $docs = $modtype::withTrashed()->with('amount', 'items.amount')->get();
//            $docs = $coredoctype[1]::class->withTrashed()->with('amount', 'items.amount')->get();
            //$docs = \BT\Modules\Quotes\Models\Quote::withTrashed()->with('amount', 'items.amount')->get();

            foreach ($docs as $doc) {
                $document = new \BT\Modules\Documents\Models\Document();
                $document->document_type = $coredoctype['document_type'];
                $document->document_id = $doc->id;
                $document->document_date = $doc->quote_date ?? $doc->workorder_date ?? $doc->invoice_date;
                $document->workorder_id = $doc->workorder_id ?? 0;
                $document->invoice_id = $doc->invoice_id ?? 0;
                $document->user_id = $doc->user_id;
                $document->client_id = $doc->client_id;
                $document->group_id = $doc->group_id;
                $document->document_status_id = $doc->quote_status_id ?? $doc->workorder_status_id ?? $doc->invoice_status_id;
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
                $documentamount->paid = $doc->paid ?? 0;
                $documentamount->balance = $doc->balance ?? 0;
                $documentamount->deleted_at = $doc->amount->deleted_at;
                $documentamount->created_at = $doc->amount->created_at;
                $documentamount->updated_at = $doc->amount->updated_at;

                $documentamount->saveQuietly();


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
                    $documentitem->price = $docitem->price;
                    $documentitem->deleted_at = $docitem->deleted_at;
                    $documentitem->created_at = $docitem->created_at;
                    $documentitem->updated_at = $docitem->updated_at;

                    $documentitem->saveQuietly();


                    $documentitemamount = new \BT\Modules\Documents\Models\DocumentItemAmount();

                    $documentitemamount->item_id = $documentitem->id;
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
        $invoices = \BT\Modules\Documents\Models\Document::where('document_type', 3)->get();

        foreach ($invoices as $invoice){
            if ($invoice->document_status_id == 3){
                $invoice->document_status_id = 6;
            }
            if ($invoice->document_status_id == 4){
                $invoice->document_status_id = 5;
            }
            $invoice->updateQuietly();
        }

        //update quote workorder_id and invoice_id refs to new documents
        $quotes = \BT\Modules\Documents\Models\Document::where('document_type', 1)->get();

        foreach ($quotes as $quote){
            if ($quote->workorder_id > 0 || $quote->invoice_id > 0) {
                if ($quote->workorder_id > 0) {
                    $quotedoc = \BT\Modules\Documents\Models\Document::where('document_type', 2)->where('document_id', $quote->workorder_id)->first();
                    $quote->workorder_id = $quotedoc->id;
                }
                if ($quote->invoice_id > 0) {
                    $invoicedoc = \BT\Modules\Documents\Models\Document::where('document_type', 3)->where('document_id', $quote->invoice_id)->first();
                    $quote->invoice_id = $invoicedoc->id;
                }
                $quote->updateQuietly();
            }
        }

        //update workorder  invoice_id refs to new documents
        $workorders = \BT\Modules\Documents\Models\Document::where('document_type',2)->get();

        foreach ($workorders as $workorder){
                if ($workorder->invoice_id > 0) {
                    $invoicedoc = \BT\Modules\Documents\Models\Document::where('document_type', 3)->where('document_id', $workorder->invoice_id)->first();
                    $workorder->invoice_id = $invoicedoc->id;
                    $workorder->updateQuietly();
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
