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

        $quoteitemamounts = \BT\Modules\Quotes\Models\QuoteItemAmount::withTrashed()->get();

        foreach ($quoteitemamounts as $quoteitemamount){
            $documentitemamount = new \BT\Modules\Documents\Models\DocumentItemAmount();

            $documentitemamount->document_type = DOCUMENT_TYPE_QUOTE;
            $documentitemamount->item_id = $quoteitemamount->item_id;
            $documentitemamount->tax_1 = $quoteitemamount->tax_1;
            $documentitemamount->tax_2 = $quoteitemamount->tax_2;
            $documentitemamount->tax = $quoteitemamount->tax;
            $documentitemamount->total = $quoteitemamount->total;
            $documentitemamount->deleted_at = $quoteitemamount->deleted_at;
            $documentitemamount->created_at = $quoteitemamount->created_at;
            $documentitemamount->updated_at = $quoteitemamount->updated_at;

            $documentitemamount->save();
        }
        //
        $workorderitemamounts = \BT\Modules\Workorders\Models\WorkorderItemAmount::withTrashed()->get();

        foreach ($workorderitemamounts as $workorderitemamount){
            $documentitemamount = new \BT\Modules\Documents\Models\DocumentItemAmount();

            $documentitemamount->document_type = DOCUMENT_TYPE_WORKORDER;
            $documentitemamount->item_id = $workorderitemamount->item_id;
            $documentitemamount->tax_1 = $workorderitemamount->tax_1;
            $documentitemamount->tax_2 = $workorderitemamount->tax_2;
            $documentitemamount->tax = $workorderitemamount->tax;
            $documentitemamount->total = $workorderitemamount->total;
            $documentitemamount->deleted_at = $workorderitemamount->deleted_at;
            $documentitemamount->created_at = $workorderitemamount->created_at;
            $documentitemamount->updated_at = $workorderitemamount->updated_at;

            $documentitemamount->save();
        }
        //
        $invoiceitemamounts = \BT\Modules\Invoices\Models\InvoiceItemAmount::withTrashed()->get();

        foreach ($invoiceitemamounts as $invoiceitemamount){
            $documentitemamount = new \BT\Modules\Documents\Models\DocumentItemAmount();

            $documentitemamount->document_type = DOCUMENT_TYPE_INVOICE;
            $documentitemamount->item_id = $invoiceitemamount->item_id;
            $documentitemamount->tax_1 = $invoiceitemamount->tax_1;
            $documentitemamount->tax_2 = $invoiceitemamount->tax_2;
            $documentitemamount->tax = $invoiceitemamount->tax;
            $documentitemamount->total = $invoiceitemamount->total;
            $documentitemamount->deleted_at = $invoiceitemamount->deleted_at;
            $documentitemamount->created_at = $invoiceitemamount->created_at;
            $documentitemamount->updated_at = $invoiceitemamount->updated_at;

            $documentitemamount->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
