<?php

namespace BT\Observers;


//use BT\Events\InvoiceModified;

use BT\Events\DocumentModified;
use BT\Modules\CustomFields\Models\PaymentCustom;
use BT\Modules\MailQueue\Support\MailQueue;
use BT\Modules\Payments\Models\Payment;
use BT\Support\Contacts;
use BT\Support\Parser;

class PaymentObserver
{
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }
    /**
     * Handle the payment "created" event.
     *
     * @param  \BT\Modules\Payments\Models\Payment  $payment
     * @return void
     */
    public function created(Payment $payment): void
    {
        event(new DocumentModified($payment->invoice));

        // Create the default custom record.
        $payment->custom()->save(new PaymentCustom());

        if (auth()->guest() or auth()->user()->user_type == 'client')
        {
            $payment->invoice->activities()->create(['activity' => 'public.paid']);
        }
    }

    public function creating(Payment $payment): void
    {

        if (!$payment->paid_at)
        {
            $payment->paid_at = date('Y-m-d');
        }
    }

    public function updated(Payment $payment): void
    {
        event(new DocumentModified($payment->invoice));
    }

    public function deleting(Payment $payment): void
    {
        foreach ($payment->mailQueue as $mailQueue)
        {
            ($payment->isForceDeleting()) ? $mailQueue->onlyTrashed()->forceDelete() : $mailQueue->delete();
        }

        foreach ($payment->notes as $note)
        {
            ($payment->isForceDeleting()) ? $note->onlyTrashed()->forceDelete() : $note->delete();
        }
    }

    public function deleted(Payment $payment): void
    {
        if ($payment->invoice)
        {
            event(new DocumentModified($payment->invoice));
        }
    }

    public function restoring(Payment $payment): void
    {
       foreach ($payment->mailQueue as $mailQueue) {
            $mailQueue->onlyTrashed()->restore();
        }

        foreach ($payment->notes as $note) {
            $note->onlyTrashed()->restore();
        }
    }

}
