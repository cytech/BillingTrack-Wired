<?php

use BT\Modules\Groups\Models\Group;
use BT\Modules\Settings\Models\Setting;
use Database\Seeders\EmployeeTypeSeeder;
use Database\Seeders\IndustrySeeder;
use Database\Seeders\InventoryTypesSeeder;
use Database\Seeders\PaymentTermsSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\SizeSeeder;
use Database\Seeders\TitleSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ReRun the THE SEEDERS FOR FRESH INSTALL FROM DATABASE SCHEMA - database/schema/mysql-schema.sql.
     */
    public function up(): void
    {
        deleteTempFiles();
        deleteViewCache();

        //run seeder for default setup
        Artisan::call('db:seed');

        //seed industries, sizes, titles and paymentterms
        Artisan::call('db:seed', [
            '--class' => IndustrySeeder::class
        ]);
        Artisan::call('db:seed', [
            '--class' => SizeSeeder::class
        ]);
        Artisan::call('db:seed', [
            '--class' => TitleSeeder::class
        ]);
        Artisan::call('db:seed', [
            '--class' => PaymentTermsSeeder::class
        ]);

        //seed inventory types
        Artisan::call('db:seed', [
            '--class' => InventoryTypesSeeder::class
        ]);

        //seed permissions and roles
        Artisan::call('db:seed', [
            '--class' => PermissionsTableSeeder::class
        ]);
        Artisan::call('db:seed', [
            '--class' => RolesTableSeeder::class
        ]);

        //seed employee_types
        Artisan::call('db:seed', [
            '--class' => EmployeeTypeSeeder::class
        ]);

        if (Setting::getByKey('version') == '4.0.0') { //seeded version setting
            //save old migration keys for fresh schema install
            Setting::saveByKey('convertWorkorderDate', 'jobdate');
            Setting::saveByKey('currencyConversionKey', '');
            Setting::saveByKey('enabledModules', '127');
            Setting::saveByKey('purchaseorderEmailBody', '<p>Please find the attached purchase order from {{ $purchaseorder->user->name }}</p>');
            Setting::saveByKey('purchaseorderEmailSubject', 'Purchase Order #{{ $purchaseorder->number }}');
            Setting::saveByKey('purchaseorderFooter', '');
            $pogroup = Group::create(['name'    => 'Purchaseorder Default', 'format' => 'PO{NUMBER}', 'next_id' => 1,
                                      'last_id' => 0, 'left_pad' => 0, 'reset_number' => 0]);
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
            $rinvgroup = Group::create(['name'    => 'Recurringinvoice Default', 'format' => 'RINV{NUMBER}', 'next_id' => 1,
                                        'last_id' => 0, 'left_pad' => 0, 'reset_number' => 0]);
            Setting::saveByKey('recurringinvoiceGroup', $rinvgroup->id);
            Setting::saveByKey('recurringinvoicePeriod', 3);
            Setting::saveByKey('recurringinvoiceStatusFilter', 'all_statuses');
            Setting::saveByKey('upcomingPaymentNoticeEmailBody', '<p>This is a notice to let you know your invoice from {{ $invoice->user->name }} for {{ $invoice->amount->formatted_total }} is due on {{ $invoice->formatted_action_date }}. Click the link below to view the invoice:</p><br><br><p><a href="{{ $invoice->public_url }}">{{ $invoice->public_url }}</a></p>');
            Setting::saveByKey('workorderApprovedEmailBody', '<p><a href="{{ $workorder->public_url }}">Workorder #{{ $workorder->number }}</a> has been APPROVED.</p>');
            Setting::saveByKey('workorderEmailBody', '<p>To view your workorder from {{ $workorder->user->name }} for {{ $workorder->amount->formatted_total }}, click the link below:</p> <p><a href="{{ $workorder->public_url }}">{{ $workorder->public_url }}</a></p>');
            Setting::saveByKey('workorderEmailSubject', 'Workorder #{{ $workorder->number }}');
            Setting::saveByKey('workorderRejectedEmailBody', '<p><a href="{{ $workorder->public_url }}">Workorder #{{ $workorder->number }}</a> has been REJECTED.</p>');
            Setting::writeEmailTemplates();

            Setting::saveByKey('version', '7.0.0');
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
