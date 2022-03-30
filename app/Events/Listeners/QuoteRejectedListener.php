<?php

namespace BT\Events\Listeners;

use BT\Events\QuoteRejected;
use BT\Modules\MailQueue\Support\MailQueue;
use BT\Support\Parser;

class QuoteRejectedListener
{
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function handle(QuoteRejected $event)
    {
        $event->quote->activities()->create(['activity' => 'public.rejected']);

        $parser = new Parser($event->quote);

        $mail = $this->mailQueue->create($event->quote, [
            'to'         => [$event->quote->user->email],
            'cc'         => [config('bt.mailDefaultCc')],
            'bcc'        => [config('bt.mailDefaultBcc')],
            'subject'    => trans('bt.quote_status_change_notification'),
            'body'       => $parser->parse('quoteRejectedEmailBody'),
            'attach_pdf' => config('bt.attachPdf'),
        ]);

        $this->mailQueue->send($mail->id);
    }
}
