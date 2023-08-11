<?php

use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Purchaseorder;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\Workorder;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\Settings\Models\Setting;
use BT\Modules\TimeTracking\Models\TimeTrackingTask;
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

        //move invoice status_id to document statuses
        $invoices = Invoice::withTrashed()->whereIn('document_status_id', [3, 4])->get();

        foreach ($invoices as $invoice) {
            if ($invoice->document_status_id == 3) {
                $invoice->document_status_id = 6;
            } else { //4
                $invoice->document_status_id = 5;
            }
            $invoice->updateQuietly();

        }
        //move purchaseorder status_id to document statuses
        $purchaseorders = Purchaseorder::withTrashed()->whereIn('document_status_id', [3, 4, 5, 6])->get();

        foreach ($purchaseorders as $purchaseorder) {
            if ($purchaseorder->document_status_id == 3) {
                $purchaseorder->document_status_id = 7;
            } elseif ($purchaseorder->document_status_id == 4) {
                $purchaseorder->document_status_id = 8;
            } elseif ($purchaseorder->document_status_id == 5) {
                $purchaseorder->document_status_id = 6;
            } else { //6
                $purchaseorder->document_status_id = 5;
            }
            $purchaseorder->updateQuietly();

        }

        //update quote workorder_id and invoice_id refs to new documents
        $quotes = Quote::withTrashed()->where('workorder_id', '>', 0)->orWhere('invoice_id', '>', 0)->get();

        foreach ($quotes as $quote) {
            if ($quote->workorder_id > 0) {
                $quotedoc = Workorder::withTrashed()->where('document_id', $quote->workorder_id)->first();
                $quotedoc ? $quote->workorder_id = $quotedoc->id : $quote->workorder_id = 0;
                $quote->updateQuietly();
            }
            if ($quote->invoice_id > 0) {
                $invoicedoc = Invoice::withTrashed()->where('document_id', $quote->invoice_id)->first();
                $invoicedoc ? $quote->invoice_id = $invoicedoc->id : $quote->invoice_id = 0;
                $quote->updateQuietly();
            }
        }

        //update workorder  invoice_id refs to new documents
        $workorders = Workorder::withTrashed()->where('invoice_id', '>', 0)->get();

        foreach ($workorders as $workorder) {
            $invoicedoc = Invoice::withTrashed()->where('document_id', $workorder->invoice_id)->first();
            $invoicedoc ? $workorder->invoice_id = $invoicedoc->id : $workorder->invoice_id = 0;
            $workorder->updateQuietly();
        }

        //update payment invoice_id to new documents
        $payments = Payment::withTrashed()->where('invoice_id', '>', 0)->get();

        foreach ($payments as $payment) {
            $invoicedoc = Invoice::withTrashed()->where('document_id', $payment->invoice_id)->first();
            $invoicedoc ? $payment->invoice_id = $invoicedoc->id : $payment->invoice_id = 0;
            $payment->updateQuietly();
        }

        //update timetrackingtasks invoice_id to new documents
        $timetrackingtasks = TimeTrackingTask::withTrashed()->where('invoice_id', '>', 0)->get();

        foreach ($timetrackingtasks as $timetrackingtask) {
            $invoicedoc = Invoice::withTrashed()->where('document_id', $timetrackingtask->invoice_id)->first();
            $invoicedoc ? $timetrackingtask->invoice_id = $invoicedoc->id : $timetrackingtask->invoice_id = 0;
            $timetrackingtask->updateQuietly();
        }

        //update expenses invoice_id to new documents
        $expenses = Expense::withTrashed()->where('invoice_id', '>', 0)->get();

        foreach ($expenses as $expense) {
            $invoicedoc = Invoice::withTrashed()->where('document_id', $expense->invoice_id)->first();
            $invoicedoc ? $expense->invoice_id = $invoicedoc->id : $expense->invoice_id = 0;
            $expense->updateQuietly();
        }

        //create recurringinvoiceGroup and config setting

        $maxrinvs = \BT\Support\SixtoSeven\Models\RecurringInvoice::withTrashed()->max('id') ?? 0;
        $rinvgroup = Group::create(['name' => 'Recurringinvoice Default', 'format' => 'RINV{NUMBER}', 'next_id' => $maxrinvs + 1,
            'last_id' => $maxrinvs, 'left_pad' => 0, 'reset_number' => 0]);

        Setting::saveByKey('recurringinvoiceGroup', $rinvgroup->id);
        Setting::saveByKey('recurringinvoiceFrequency', 1);
        Setting::saveByKey('recurringinvoicePeriod', 3);
        Setting::saveByKey('recurringinvoiceStatusFilter', 'all_statuses');

        $recurringinvoices = \BT\Modules\Documents\Models\Recurringinvoice::withTrashed()->get();
        foreach ($recurringinvoices as $recurringinvoice) {
            $recurringinvoice->number = 'RINV'.$recurringinvoice->document_id;
            $recurringinvoice->updateQuietly();
        }

        //remove temporary column
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('document_id');
        });

        Schema::enableForeignKeyConstraints();

        //modify due_at in upcomingPaymentNoticeEmailBody setting
        //this only affects default installation
        //any user modified email templates will need to be updated by user
        //to update, replace all occurrences of 'formatted_due_at' and 'formatted_expires_at'
        //with 'formatted_action_date'
        $bodyvalue = Setting::getByKey('upcomingPaymentNoticeEmailBody');
        $bodyvalue = str_replace('due_at', 'action_date', $bodyvalue);
        Setting::saveByKey('upcomingPaymentNoticeEmailBody', $bodyvalue);
        Setting::writeEmailTemplates();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
