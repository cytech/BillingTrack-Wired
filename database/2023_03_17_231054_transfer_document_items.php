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

        $quoteitems = \BT\Modules\Quotes\Models\QuoteItem::withTrashed()->get();

        foreach ($quoteitems as $quoteitem){
            $documentamount = new \BT\Modules\Documents\Models\DocumentItem();

            $documentamount->document_type = DOCUMENT_TYPE_QUOTE;
            $documentamount->document_id = $quoteitem->quote_id;
            $documentamount->tax_rate_id = $quoteitem->tax_rate_id;
            $documentamount->tax_rate_2_id = $quoteitem->tax_rate_2_id;
            $documentamount->resource_table = $quoteitem->resource_table;
            $documentamount->resource_id = $quoteitem->resource_id;
            $documentamount->is_tracked = 0;
            $documentamount->name = $quoteitem->name;
            $documentamount->description = $quoteitem->description;
            $documentamount->quantity = $quoteitem->quantity;
            $documentamount->display_order = $quoteitem->display_order;
            $documentamount->price = $quoteitem->price;
            $documentamount->deleted_at = $quoteitem->deleted_at;
            $documentamount->created_at = $quoteitem->created_at;
            $documentamount->updated_at = $quoteitem->updated_at;

            $documentamount->save();
        }
        //
        $workorderitems = \BT\Modules\Workorders\Models\WorkorderItem::withTrashed()->get();

        foreach ($workorderitems as $workorderitem){
            $documentamount = new \BT\Modules\Documents\Models\DocumentItem();

            $documentamount->document_type = DOCUMENT_TYPE_WORKORDER;
            $documentamount->document_id = $workorderitem->workorder_id;
            $documentamount->tax_rate_id = $workorderitem->tax_rate_id;
            $documentamount->tax_rate_2_id = $workorderitem->tax_rate_2_id;
            $documentamount->resource_table = $workorderitem->resource_table;
            $documentamount->resource_id = $workorderitem->resource_id;
            $documentamount->is_tracked = 0;
            $documentamount->name = $workorderitem->name;
            $documentamount->description = $workorderitem->description;
            $documentamount->quantity = $workorderitem->quantity;
            $documentamount->display_order = $workorderitem->display_order;
            $documentamount->price = $workorderitem->price;
            $documentamount->deleted_at = $workorderitem->deleted_at;
            $documentamount->created_at = $workorderitem->created_at;
            $documentamount->updated_at = $workorderitem->updated_at;

            $documentamount->save();
        }
        //
        $invoiceitems = \BT\Modules\Invoices\Models\InvoiceItem::withTrashed()->get();

        foreach ($invoiceitems as $invoiceitem){
            $documentamount = new \BT\Modules\Documents\Models\DocumentItem();

            $documentamount->document_type = DOCUMENT_TYPE_INVOICE;
            $documentamount->document_id = $invoiceitem->invoice_id;
            $documentamount->tax_rate_id = $invoiceitem->tax_rate_id;
            $documentamount->tax_rate_2_id = $invoiceitem->tax_rate_2_id;
            $documentamount->resource_table = $invoiceitem->resource_table;
            $documentamount->resource_id = $invoiceitem->resource_id;
            $documentamount->is_tracked = $invoiceitem->is_tracked;
            $documentamount->name = $invoiceitem->name;
            $documentamount->description = $invoiceitem->description;
            $documentamount->quantity = $invoiceitem->quantity;
            $documentamount->display_order = $invoiceitem->display_order;
            $documentamount->price = $invoiceitem->price;
            $documentamount->deleted_at = $invoiceitem->deleted_at;
            $documentamount->created_at = $invoiceitem->created_at;
            $documentamount->updated_at = $invoiceitem->updated_at;

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
