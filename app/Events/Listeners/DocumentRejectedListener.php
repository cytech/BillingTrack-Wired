<?php

namespace BT\Events\Listeners;

use AllowDynamicProperties;
use BT\Events\DocumentRejected;
use BT\Modules\MailQueue\Support\MailQueue;
use BT\Support\Parser;

#[AllowDynamicProperties]
class DocumentRejectedListener
{
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function handle(DocumentRejected $event)
    {
        $event->document->activities()->create(['activity' => 'public.rejected']);

        if ($event->module_type == 'Quote') {
            $parser = new Parser($event->document);

            $mail = $this->mailQueue->create($event->document, [
                'to' => [$event->document->user->email],
                'cc' => [config('bt.mailDefaultCc')],
                'bcc' => [config('bt.mailDefaultBcc')],
                'subject' => trans('bt.quote_status_change_notification'),
                'body' => $parser->parse('quoteRejectedEmailBody'),
                'attach_pdf' => config('bt.attachPdf'),
            ]);

            $this->mailQueue->send($mail->id);
        } elseif ($event->module_type == 'Workorder') {
            $parser = new Parser($event->document);

            $mail = $this->mailQueue->create($event->document, [
                'to' => [$event->document->user->email],
                'cc' => [config('bt.mailDefaultCc')],
                'bcc' => [config('bt.mailDefaultBcc')],
                'subject' => trans('bt.workorder_status_change_notification'),
                'body' => $parser->parse('workorderRejectedEmailBody'),
                'attach_pdf' => config('bt.attachPdf'),
            ]);

            $this->mailQueue->send($mail->id);
        }
    }
}
