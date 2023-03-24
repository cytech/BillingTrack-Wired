<?php

namespace BT\Observers;

use BT\Modules\Currencies\Support\CurrencyConverterFactory;
use BT\Modules\CustomFields\Models\DocumentCustom;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Support\DocumentCalculate;
use BT\Support\DateFormatter;
use BT\Support\Statuses\DocumentStatuses;

class DocumentObserver
{
    public function __construct(DocumentCalculate $documentCalculate)
    {
        $this->documentCalculate = $documentCalculate;
    }

    /**
     * Handle the document "created" event.
     *
     * @param  \BT\Modules\Documents\Models\Document  $document
     * @return void
     */
    public function created(Document $document): void
    {
        // Create the empty document amount record
        $this->documentCalculate->calculate($document);

        // Increment the next id
        Group::incrementNextId($document);

        // Create the custom document record.
        //$document->custom()->save(new DocumentCustom()); todo
    }

    /**
     * Handle the document "created" event.
     *
     * @param  \BT\Modules\Documents\Models\Document  $document
     * @return void
     */
    public function creating(Document $document): void
    {
        if (!$document->client_id)
        {
            // This needs to throw an exception since this is required.
        }

        if (!$document->user_id)
        {
            $document->user_id = auth()->user()->id;
        }

        if (!$document->document_date)
        {
            $document->document_date = date('Y-m-d');
        }

        if (!$document->action_date)
        {
            $document->action_date = DateFormatter::incrementDateByDays($document->document_date->format('Y-m-d'), config('bt.documentsExpireAfter', 10));
        }

        if (!$document->company_profile_id)
        {
            $document->company_profile_id = config('bt.defaultCompanyProfile');
        }

        if (!$document->group_id)
        {
            $document->group_id = config('bt.documentGroup');
        }

        if (!$document->number)
        {
            $document->number = Group::generateNumber($document->group_id);
        }

        if (!isset($document->terms))
        {
            $document->terms = config('bt.documentTerms');
        }

        if (!isset($document->footer))
        {
            $document->footer = config('bt.documentFooter');
        }

        if (!$document->document_status_id)
        {
            $document->document_status_id = DocumentStatuses::getStatusId('draft');
        }

        if (!$document->currency_code)
        {
            $document->currency_code = $document->client->currency_code;
        }

        if (!$document->template)
        {
            $document->template = $document->companyProfile->document_template;
        }

        if ($document->currency_code == config('bt.baseCurrency'))
        {
            $document->exchange_rate = 1;
        }
        elseif (!$document->exchange_rate)
        {
            $currencyConverter    = CurrencyConverterFactory::create();
            $document->exchange_rate = $currencyConverter->convert(config('bt.currencyConversionKey'), config('bt.baseCurrency'), $document->currency_code);
        }

        $document->url_key = str_random(32);

    }

    /**
     * Handle the document "deleting" event.
     *
     * @param  \BT\Modules\Documents\Models\Document  $document
     * @return void
     */
    public function deleting(Document $document): void
    {
        foreach ($document->activities as $activity) {
            ($document->isForceDeleting()) ? $activity->onlyTrashed()->forceDelete() : $activity->delete();
        }

        foreach ($document->attachments as $attachment){
            ($document->isForceDeleting()) ? $attachment->onlyTrashed()->forceDelete() : $attachment->delete();
        }

        foreach ($document->mailQueue as $mailQueue){
            ($document->isForceDeleting()) ? $mailQueue->onlyTrashed()->forceDelete() : $mailQueue->delete();
        }

        foreach ($document->notes as $note){
            ($document->isForceDeleting()) ? $note->onlyTrashed()->forceDelete() : $note->delete();
        }

        //this gets messy with soft deletes...
//        $group = Group::where('id', $document->group_id)
//            ->where('last_number', $document->number)
//            ->first();
//
//        if ($group)
//        {
//            $group->next_id = $group->next_id - 1;
//            $group->save();
//        }
    }

    /**
     * Handle the document "restoring" event.
     *
     * @param \BT\Modules\Documents\Models\Document $document
     * @return void
     */
    public function restoring(Document $document): void
    {
        foreach ($document->activities as $activity) {
            $activity->onlyTrashed()->restore();
        }

        foreach ($document->attachments as $attachment) {
            $attachment->onlyTrashed()->restore();
        }

        foreach ($document->mailQueue as $mailQueue) {
            $mailQueue->onlyTrashed()->restore();
        }

        foreach ($document->notes as $note) {
            $note->onlyTrashed()->restore();
        }
    }

}
