<?php

namespace BT\Events\Listeners;

use BT\Events\DocumentEmailing;
use BT\Support\DateFormatter;

class DocumentEmailingListener
{
    public function handle(DocumentEmailing $event)
    {
        if ($event->module_type == 'Quote') {
            if (config('bt.resetQuoteDateEmailDraft') and $event->document->status_text == 'draft') {
                $event->document->document_date = date('Y-m-d');
                $event->document->action_date = DateFormatter::incrementDateByDays(date('Y-m-d'), config('bt.quotesExpireAfter'));
                $event->document->save();
            }
        } elseif ($event->module_type == 'Invoice') {
            if (config('bt.resetInvoiceDateEmailDraft') and $event->document->status_text == 'draft') {
                $event->document->document_date = date('Y-m-d');
                $event->document->action_date = DateFormatter::incrementDateByDays(date('Y-m-d'), $event->document->client->client_terms);
                $event->document->save();
            }
        }  elseif ($event->module_type == 'Workorder') {
            if (config('bt.resetWorkorderDateEmailDraft') and $event->document->status_text == 'draft') {
                $event->document->document_date = date('Y-m-d');
                $event->document->action_date = DateFormatter::incrementDateByDays(date('Y-m-d'), config('bt.workordersExpireAfter'));
                $event->document->save();
            }
        }  else { //purchaseorder
            if (config('bt.resetPurchaseorderDateEmailDraft') and $event->document->status_text == 'draft') {
                $event->document->document_date = date('Y-m-d');
                $event->document->action_date = DateFormatter::incrementDateByDays(date('Y-m-d'), $event->document->vendor->vendor_terms);
                $event->document->save();
            }
        }
    }
}
