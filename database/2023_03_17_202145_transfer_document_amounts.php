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

        $quoteamounts = \BT\Modules\Quotes\Models\QuoteAmount::withTrashed()->get();

        foreach ($quoteamounts as $quoteamount){
            $documentamount = new \BT\Modules\Documents\Models\DocumentAmount();

            $documentamount->document_type = DOCUMENT_TYPE_QUOTE;
            $documentamount->document_id = $quoteamount->quote_id;
            $documentamount->subtotal = $quoteamount->subtotal;
            $documentamount->discount = $quoteamount->discount;
            $documentamount->tax = $quoteamount->tax;
            $documentamount->total = $quoteamount->total;
            $documentamount->paid = 0;
            $documentamount->balance = 0;
            $documentamount->deleted_at = $quoteamount->deleted_at;
            $documentamount->created_at = $quoteamount->created_at;
            $documentamount->updated_at = $quoteamount->updated_at;

            $documentamount->save();
        }
        //
        $workorderamounts = \BT\Modules\Workorders\Models\WorkorderAmount::withTrashed()->get();

        foreach ($workorderamounts as $workorderamount){
            $documentamount = new \BT\Modules\Documents\Models\DocumentAmount();

            $documentamount->document_type = DOCUMENT_TYPE_WORKORDER;
            $documentamount->document_id = $workorderamount->workorder_id;
            $documentamount->subtotal = $workorderamount->subtotal;
            $documentamount->discount = $workorderamount->discount;
            $documentamount->tax = $workorderamount->tax;
            $documentamount->total = $workorderamount->total;
            $documentamount->paid = 0;
            $documentamount->balance = 0;
            $documentamount->deleted_at = $workorderamount->deleted_at;
            $documentamount->created_at = $workorderamount->created_at;
            $documentamount->updated_at = $workorderamount->updated_at;

            $documentamount->save();
        }
        //
        $invoiceamounts = \BT\Modules\Invoices\Models\InvoiceAmount::withTrashed()->get();

        foreach ($invoiceamounts as $invoiceamount){
            $documentamount = new \BT\Modules\Documents\Models\DocumentAmount();

            $documentamount->document_type = DOCUMENT_TYPE_INVOICE;
            $documentamount->document_id = $invoiceamount->invoice_id;
            $documentamount->subtotal = $invoiceamount->subtotal;
            $documentamount->discount = $invoiceamount->discount;
            $documentamount->tax = $invoiceamount->tax;
            $documentamount->total = $invoiceamount->total;
            $documentamount->paid = $invoiceamount->paid;
            $documentamount->balance = $invoiceamount->balance;
            $documentamount->deleted_at = $invoiceamount->deleted_at;
            $documentamount->created_at = $invoiceamount->created_at;
            $documentamount->updated_at = $invoiceamount->updated_at;

            $documentamount->save();
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
