<?php

namespace Database\Seeders;

use BT\Modules\Documents\Models\Recurringinvoice;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsV7TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::saveByKey('convertWorkorderDate', 'jobdate');
        Setting::saveByKey('currencyConversionKey', '');
        Setting::saveByKey('enabledModules', '127');
        Setting::saveByKey('purchaseorderEmailBody', '<p>Please find the attached purchase order from {{ $purchaseorder->user->name }}</p>');
        Setting::saveByKey('purchaseorderEmailSubject', 'Purchase Order #{{ $purchaseorder->number }}');
        Setting::saveByKey('purchaseorderFooter', '');
        $pogroup = Group::create(['name' => 'Purchaseorder Default',
            'next_id' => 1,
            'left_pad' => 0,
            'format' => 'PO{NUMBER}',
            'reset_number' => 0,
            'last_id' => 0,
            'last_year' => 0,
            'last_month' => 0,
            'last_week' => 0,
            'last_number' => 0, ]);
        Setting::saveByKey('purchaseorderGroup', $pogroup->id);
        Setting::saveByKey('purchaseordersDueAfter', '30');
        Setting::saveByKey('purchaseorderStatusFilter', 'all_statuses');
        Setting::saveByKey('purchaseorderTemplate', 'default.blade.php');
        Setting::saveByKey('purchaseorderTerms', '');
        Setting::saveByKey('resetPurchaseorderDateEmailDraft', '0');
        Setting::saveByKey('resultsPerPage', 10);
        Setting::saveByKey('schedulerFcThemeSystem', 'bootstrap5');
        Setting::saveByKey('schedulerFcTodaybgColor', '#FFF9DE');
        Setting::saveByKey('skin', '{"headBackground":"purple","headClass":"light","sidebarMode":"open"}');
        Setting::saveByKey('updateInvProductsDefault', '1');
        Setting::saveByKey('updateProductsDefault', '1');
        Setting::saveByKey('recurringinvoiceFrequency', 1);
        $maxrinvs = RecurringInvoice::withTrashed()->max('id') ?? 0;
        $rinvgroup = Group::create(['name' => 'Recurringinvoice Default', 'format' => 'RINV{NUMBER}', 'next_id' => $maxrinvs + 1,
            'last_id' => $maxrinvs, 'left_pad' => 0, 'reset_number' => 0, 'last_year' => 0, 'last_month' => 0, 'last_week' => 0, 'last_number' => 0]);
        Setting::saveByKey('recurringinvoiceGroup', $rinvgroup->id);
        Setting::saveByKey('recurringinvoicePeriod', 3);
        Setting::saveByKey('recurringinvoiceStatusFilter', 'all_statuses');
        Setting::saveByKey('upcomingPaymentNoticeEmailBody', '<p>This is a notice to let you know your invoice from {{ $invoice->user->name }} for {{ $invoice->amount->formatted_total }} is due on {{ $invoice->formatted_action_date }}. Click the link below to view the invoice:</p><br><br><p><a href="{{ $invoice->public_url }}">{{ $invoice->public_url }}</a></p>');
        Setting::saveByKey('workorderApprovedEmailBody', '<p><a href="{{ $workorder->public_url }}">Workorder #{{ $workorder->number }}</a> has been APPROVED.</p>');
        Setting::saveByKey('workorderEmailBody', '<p>To view your workorder from {{ $workorder->user->name }} for {{ $workorder->amount->formatted_total }}, click the link below:</p> <p><a href="{{ $workorder->public_url }}">{{ $workorder->public_url }}</a></p>');
        Setting::saveByKey('workorderEmailSubject', 'Workorder #{{ $workorder->number }}');
        Setting::saveByKey('workorderRejectedEmailBody', '<p><a href="{{ $workorder->public_url }}">Workorder #{{ $workorder->number }}</a> has been REJECTED.</p>');
        Setting::writeEmailTemplates();
    }
}
