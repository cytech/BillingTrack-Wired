<?php

namespace BT\Providers;

use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Notes\Models\Note;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\RecurringInvoices\Models\RecurringInvoice;
use BT\Modules\RecurringInvoices\Models\RecurringInvoiceItem;
use BT\Modules\Settings\Models\Setting;
use BT\Modules\TimeTracking\Models\TimeTrackingProject;
use BT\Modules\TimeTracking\Models\TimeTrackingTask;
use BT\Modules\Users\Models\User;
use BT\Modules\Vendors\Models\Vendor;
use BT\Observers\AttachmentObserver;
use BT\Observers\ClientObserver;
use BT\Observers\CompanyProfileObserver;
use BT\Observers\DocumentItemObserver;
use BT\Observers\DocumentObserver;
use BT\Observers\ExpenseObserver;
use BT\Observers\NoteObserver;
use BT\Observers\PaymentObserver;
use BT\Observers\RecurringInvoiceItemObserver;
use BT\Observers\RecurringInvoiceObserver;
use BT\Observers\SettingObserver;
use BT\Observers\TimeTrackingProjectObserver;
use BT\Observers\TimeTrackingTaskObserver;
use BT\Observers\UserObserver;
use BT\Observers\VendorObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Client::observe(ClientObserver::class);
        Attachment::observe(AttachmentObserver::class);
        Expense::observe(ExpenseObserver::class);
        CompanyProfile::observe(CompanyProfileObserver::class);
        Payment::observe(PaymentObserver::class);
        Note::observe(NoteObserver::class);
        //        RecurringInvoice::observe(RecurringInvoiceObserver::class);
        Setting::observe(SettingObserver::class);
        TimeTrackingProject::observe(TimeTrackingProjectObserver::class);
        User::observe(UserObserver::class);
        //        RecurringInvoiceItem::observe(RecurringInvoiceItemObserver::class);
        TimeTrackingTask::observe(TimeTrackingTaskObserver::class);
        Vendor::observe(VendorObserver::class);
        Document::observe(DocumentObserver::class);
        DocumentItem::observe(DocumentItemObserver::class);
    }
}
