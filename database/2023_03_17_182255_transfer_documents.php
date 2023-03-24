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
        Schema::disableForeignKeyConstraints();

        $quotes = \BT\Modules\Quotes\Models\Quote::withTrashed()->get();

        foreach ($quotes as $quote){
            $document = new \BT\Modules\Documents\Models\Document();
            $document->document_type = DOCUMENT_TYPE_QUOTE;
            $document->document_id = $quote->id;
            $document->document_date = $quote->quote_date;
            $document->workorder_id = $quote->workorder_id;
            $document->invoice_id = $quote->invoice_id;
            $document->user_id = $quote->user_id;
            $document->client_id = $quote->client_id;
            $document->group_id = $quote->group_id;
            $document->document_status_id = $quote->quote_status_id;
            $document->action_date = $quote->expires_at;
            $document->number = $quote->number;
            $document->footer = $quote->footer;
            $document->url_key = $quote->url_key;
            $document->currency_code = $quote->currency_code;
            $document->exchange_rate = $quote->exchange_rate;
            $document->terms = $quote->terms;
            $document->template = $quote->template;
            $document->summary = $quote->summary;
            $document->viewed = $quote->viewed;
            $document->discount = $quote->discount;
            $document->job_date = null;
            $document->start_time = null;
            $document->end_time = null;
            $document->will_call = 0;
            $document->company_profile_id = $quote->company_profile_id;
            $document->deleted_at = $quote->deleted_at;
            $document->created_at = $quote->created_at;
            $document->updated_at = $quote->updated_at;

            $document->save();
        }
        /////
        $workorders = \BT\Modules\Workorders\Models\Workorder::withTrashed()->get();

        foreach ($workorders as $workorder){
            $document = new \BT\Modules\Documents\Models\Document();
            $document->document_type = DOCUMENT_TYPE_WORKORDER;
            $document->document_id = $workorder->id;
            $document->document_date = $workorder->workorder_date;
            $document->workorder_id = 0;
            $document->invoice_id = $workorder->invoice_id;
            $document->user_id = $workorder->user_id;
            $document->client_id = $workorder->client_id;
            $document->group_id = $workorder->group_id;
            $document->document_status_id = $workorder->workorder_status_id;
            $document->action_date = $workorder->expires_at;
            $document->number = $workorder->number;
            $document->footer = $workorder->footer;
            $document->url_key = $workorder->url_key;
            $document->currency_code = $workorder->currency_code;
            $document->exchange_rate = $workorder->exchange_rate;
            $document->terms = $workorder->terms;
            $document->template = $workorder->template;
            $document->summary = $workorder->summary;
            $document->viewed = $workorder->viewed;
            $document->discount = $workorder->discount;
            $document->job_date = $workorder->job_date;
            $document->start_time = $workorder->start_time;
            $document->end_time = $workorder->end_time;
            $document->will_call = $workorder->will_call;
            $document->company_profile_id = $workorder->company_profile_id;
            $document->deleted_at = $workorder->deleted_at;
            $document->created_at = $workorder->created_at;
            $document->updated_at = $workorder->updated_at;

            $document->save();
        }
        ////
        $invoices = \BT\Modules\Invoices\Models\Invoice::withTrashed()->get();

        foreach ($invoices as $invoice){
            $document = new \BT\Modules\Documents\Models\Document();
            $document->document_type = DOCUMENT_TYPE_INVOICE;
            $document->document_id = $invoice->id;
            $document->document_date = $invoice->invoice_date;
            $document->workorder_id = 0;
            $document->invoice_id = 0;
            $document->user_id = $invoice->user_id;
            $document->client_id = $invoice->client_id;
            $document->group_id = $invoice->group_id;
            $document->document_status_id = $invoice->invoice_status_id;
            $document->action_date = $invoice->due_at;
            $document->number = $invoice->number;
            $document->footer = $invoice->footer;
            $document->url_key = $invoice->url_key;
            $document->currency_code = $invoice->currency_code;
            $document->exchange_rate = $invoice->exchange_rate;
            $document->terms = $invoice->terms;
            $document->template = $invoice->template;
            $document->summary = $invoice->summary;
            $document->viewed = $invoice->viewed;
            $document->discount = $invoice->discount;
            $document->job_date = null;
            $document->start_time = null;
            $document->end_time = null;
            $document->will_call = 0;
            $document->company_profile_id = $invoice->company_profile_id;
            $document->deleted_at = $invoice->deleted_at;
            $document->created_at = $invoice->created_at;
            $document->updated_at = $invoice->updated_at;

            $document->save();
        }
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
