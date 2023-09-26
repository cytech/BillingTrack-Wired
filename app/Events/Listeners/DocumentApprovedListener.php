<?php

namespace BT\Events\Listeners;

use AllowDynamicProperties;
use BT\Events\DocumentApproved;
use BT\Modules\MailQueue\Support\MailQueue;
use BT\Support\ConvertToModule;
use BT\Support\DateFormatter;
use BT\Support\Parser;

#[AllowDynamicProperties]
class DocumentApprovedListener
{
    public function __construct(MailQueue $mailQueue, ConvertToModule $documentToInvoice)
    {
        $this->mailQueue = $mailQueue;
        $this->documentToInvoice = $documentToInvoice;
    }

    public function handle(DocumentApproved $event)
    {
        // Create the activity record
        $event->document->activities()->create(['activity' => 'public.approved']);

        if ($event->module_type == 'Quote') {
            // If applicable, convert the document to an invoice when document is approved
            if (config('bt.convertQuoteWhenApproved')) {
                $this->documentToInvoice->convert(
                    $event->document,
                    date('Y-m-d'),
                    DateFormatter::incrementDateByDays(date('Y-m-d'), $event->document->client->client_terms),
                    config('bt.invoiceGroup'),
                    'Invoice'
                );
            }

            $parser = new Parser($event->document);

            $mail = $this->mailQueue->create($event->document, [
                'to' => [$event->document->user->email],
                'cc' => [config('bt.mailDefaultCc')],
                'bcc' => [config('bt.mailDefaultBcc')],
                'subject' => trans('bt.quote_status_change_notification'),
                'body' => $parser->parse('quoteApprovedEmailBody'),
                'attach_pdf' => config('bt.attachPdf'),
            ]);

            $this->mailQueue->send($mail->id);
        } elseif ($event->module_type == 'Workorder') {
            if (config('bt.convertWorkorderWhenApproved')) {
                $this->documentToInvoice->convert(
                    $event->document,
                    date('Y-m-d'),
                    DateFormatter::incrementDateByDays(date('Y-m-d'), $event->document->client->client_terms),
                    config('bt.invoiceGroup'),
                    'Invoice'
                );
            }

            $parser = new Parser($event->document);

            $mail = $this->mailQueue->create($event->document, [
                'to' => [$event->document->user->email],
                'cc' => [config('bt.mailDefaultCc')],
                'bcc' => [config('bt.mailDefaultBcc')],
                'subject' => trans('bt.workorder_status_change_notification'),
                'body' => $parser->parse('workorderApprovedEmailBody'),
                'attach_pdf' => config('bt.attachPdf'),
            ]);

            $this->mailQueue->send($mail->id);
        }
    }
}
