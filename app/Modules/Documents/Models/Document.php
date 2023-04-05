<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use BT\Support\FileNames;
use BT\Support\HTML;
use BT\Support\NumberFormatter;
use BT\Support\Statuses\DocumentStatuses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Parental\HasChildren;

class Document extends Model
{
    use HasChildren;

    use SoftDeletes;

    use SoftCascadeTrait;

    protected $childColumn = 'document_type';

//    protected $childTypes = [
//        '1' => Quote::class,
//        '2' => Workorder::class,
//        '3' => Invoice::class,
//        '5' => Purchaseorder::class,
//    ];

    protected $softCascade = ['documentItems', 'custom', 'amount', 'activities', 'attachments', 'mailQueue', 'notes'];

    protected $guarded = ['id'];

    protected $casts = ['action_date' => 'datetime', 'document_date' => 'datetime', 'deleted_at' => 'datetime'];

    protected $appends = ['formatted_document_date', 'formatted_action_date','status_text', 'formatted_summary'];

    public function moduletype(){
        return Str::afterLast($this->document_type, '\\');
//        switch ($this->document_type) {
//            case 1:
//                return DOCUMENT_TYPE_QUOTE['module_type'];
//            case 2:
//                return DOCUMENT_TYPE_WORKORDER['module_type'];
//            case 3:
//                return DOCUMENT_TYPE_INVOICE['module_type'];
//            case 5:
//                return DOCUMENT_TYPE_PURCHASEORDER['module_type'];
//
//        }
    }
    public function getViewDirectoryNameAttribute(){
        return strtolower(class_basename($this)) . 's';
    }

    public function convertedtoinvoice(){
        if ($this->invoice_id){
//            return $this->withTrashed()->where('document_type',  DOCUMENT_TYPE_INVOICE['document_type'])->where('document_id', $this->invoice_id)->first();
            return Invoice::withTrashed()->find($this->invoice_id);
        }
    }

    public function convertedtoworkorder(){
        if ($this->workorder_id){
//            return $this->withTrashed()->where('document_type',  DOCUMENT_TYPE_WORKORDER['document_type'])->where('id', $this->workorder_id)->first();
            return Workorder::withTrashed()->find($this->workorder_id);
        }
    }

    public function getLowerCaseBaseclassAttribute(){
        return strtolower(class_basename($this->document_type));
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
        //return self::where('document_type', DOCUMENT_TYPE_INVOICE['document_type']);
    }

//    public static function invoices()
//    {
////        return $this->belongsTo('BT\Modules\Invoices\Models\Invoice');
//        return self::where('document_type', DOCUMENT_TYPE_INVOICE['document_type']);
//    }
//
//    public static function quotes()
//    {
////        return $this->belongsTo('BT\Modules\Invoices\Models\Invoice');
//        return self::where('document_type', DOCUMENT_TYPE_QUOTE['document_type']);
//    }
//
    public function invoicetrashed()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id')->onlyTrashed();
//        return $this->document_type == DOCUMENT_TYPE_INVOICE['document_type'] &&  $this->deleted_at != null;
    }
//
//    public function workorder()
//    {
////        return $this->belongsTo('BT\Modules\Invoices\Models\Invoice');
//        return $this->document_type == DOCUMENT_TYPE_WORKORDER['document_type'];
//    }
//
//    public function workordertrashed()
//    {
////        return $this->belongsTo('BT\Modules\Invoices\Models\Invoice', 'invoice_id', 'id')->onlyTrashed();
//        return $this->document_type == DOCUMENT_TYPE_WORKORDER['document_type'] &&  $this->deleted_at != null;
//    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function amount(): HasOne
    {
        return $this->hasOne(DocumentAmount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DocumentItem::class)
            ->orderBy('display_order');
    }

    // This and items() are the exact same. This is added to appease the IDE gods
    // and the fact that Laravel has a protected items property.
    public function documentItems(): HasMany
    {
        return $this->hasMany(DocumentItem::class)
            ->orderBy('display_order');
    }

    public function activities()
    {
        return $this->morphMany('BT\Modules\Activity\Models\Activity', 'audit');
    }

    public function attachments()
    {
        return $this->morphMany('BT\Modules\Attachments\Models\Attachment', 'attachable');
    }

    public function client()
    {
        return $this->belongsTo('BT\Modules\Clients\Models\Client');
    }

    public function clientAttachments()
    {
        $relationship = $this->morphMany('BT\Modules\Attachments\Models\Attachment', 'attachable');

        if ($this->status_text == 'paid')
        {
            $relationship->whereIn('client_visibility', [1, 2]);
        }
        else {
            $relationship->where('client_visibility', 1);
        }
        return $relationship;
    }


    public function companyProfile()
    {
        return $this->belongsTo('BT\Modules\CompanyProfiles\Models\CompanyProfile');
    }

    public function currency()
    {
        return $this->belongsTo('BT\Modules\Currencies\Models\Currency', 'currency_code', 'code');
    }

    public function custom()
    {
//        return $this->hasOne('BT\Modules\CustomFields\Models\DocumentCustom');
        $customclass = $this->moduletype() . 'Custom';
        return $this->hasOne('BT\Modules\CustomFields\Models\\'.$customclass, strtolower($this->moduletype()) . '_id');
    }

    public function group()
    {
        return $this->hasOne('BT\Modules\Groups\Models\Group');
    }

    public function mailQueue()
    {
        return $this->morphMany('BT\Modules\MailQueue\Models\MailQueue', 'mailable');
    }

    public function notes()
    {
        return $this->morphMany('BT\Modules\Notes\Models\Note', 'notable');
    }

    public function user()
    {
        return $this->belongsTo('BT\Modules\Users\Models\User');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getAttachmentPathAttribute()
    {
        return attachment_path($this->view_directory_name . '/' . $this->id);
    }

    public function getAttachmentPermissionOptionsAttribute()
    {
        return [
            '0' => trans('bt.not_visible'),
            '1' => trans('bt.visible'),
            '2' => trans('bt.visible_after_payment'),
        ];
    }

    public function getFormattedCreatedAtAttribute()
    {
        return DateFormatter::format($this->attributes['created_at']);
    }

    public function getFormattedDocumentDateAttribute()
    {
        return DateFormatter::format($this->attributes['document_date']);
    }


    public function getFormattedActionDateAttribute()
    {
        return DateFormatter::format($this->attributes['action_date']);
    }


    public function getFormattedTermsAttribute()
    {
        return nl2br($this->attributes['terms']);
    }

    public function getFormattedFooterAttribute()
    {
        return nl2br($this->attributes['footer']);
    }

    public function getStatusTextAttribute()
    {
        $statuses = DocumentStatuses::statuses();

        return $statuses[$this->attributes['document_status_id']];
    }

    public function getPdfFilenameAttribute()
    {
        return FileNames::document($this);
    }


  /*  public function getPublicUrlAttribute()
    {
        return route('clientCenter.public.document.show', [$this->url_key]);
    }

    public function getIsForeignCurrencyAttribute()
    {
        if ($this->attributes['currency_code'] == config('bt.baseCurrency'))
        {
            return false;
        }

        return true;
    }*/

    public function getHtmlAttribute()
    {
        return HTML::document($this);
    }

    public function getFormattedNumericDiscountAttribute()
    {
        return NumberFormatter::format($this->attributes['discount']);
    }

/*    public function getIsPayableAttribute()
    {
        return $this->status_text <> 'canceled' and $this->amount->balance > 0;
    }*/

    public function  getFormattedSummaryAttribute(){
        return mb_strimwidth((string)$this->attributes['summary'],0,50,'...');
    }

    /**
     * Gathers a summary of both invoice and item taxes to be displayed on invoice.
     *
     * @return array
     */
    public function getSummarizedTaxesAttribute()
    {
        $taxes = [];

        foreach ($this->items as $item)
        {
            if ($item->taxRate)
            {
                $key = $item->taxRate->name;

                if (!isset($taxes[$key]))
                {
                    $taxes[$key]              = new \stdClass();
                    $taxes[$key]->name        = $item->taxRate->name;
                    $taxes[$key]->percent     = $item->taxRate->formatted_percent;
                    $taxes[$key]->total       = $item->amount->tax_1;
                    $taxes[$key]->raw_percent = $item->taxRate->percent;
                }
                else
                {
                    $taxes[$key]->total += $item->amount->tax_1;
                }
            }

            if ($item->taxRate2)
            {
                $key = $item->taxRate2->name;

                if (!isset($taxes[$key]))
                {
                    $taxes[$key]              = new \stdClass();
                    $taxes[$key]->name        = $item->taxRate2->name;
                    $taxes[$key]->percent     = $item->taxRate2->formatted_percent;
                    $taxes[$key]->total       = $item->amount->tax_2;
                    $taxes[$key]->raw_percent = $item->taxRate2->percent;
                }
                else
                {
                    $taxes[$key]->total += $item->amount->tax_2;
                }
            }
        }

        foreach ($taxes as $key => $tax)
        {
            $taxes[$key]->total = CurrencyFormatter::format($tax->total, $this->currency);
        }

        return $taxes;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // not invoiced is where workorder->invoice_id = 0,
    // or where invoice()->invoice_status_id = 1 or 4 ('draft' or 'canceled')
    // or invoice is trashed
    public function scopeNotinvoiced($query)
    {
        $query->where('invoice_id', 0)
            ->orWhereHas('invoice', function ($query) {
                $query->whereIn('document_status_id', [1, 5]);
            })
            ->orWhereHas('invoicetrashed');
    }

    public function scopeClientId($query, $clientId = null)
    {
        if ($clientId)
        {
            $query->where('client_id', $clientId);
        }

        return $query;
    }


    public function scopeCompanyProfileId($query, $companyProfileId)
    {
        if ($companyProfileId)
        {
            $query->where('company_profile_id', $companyProfileId);
        }

        return $query;
    }

    public function scopeDraft($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('draft'));
    }

    public function scopeSent($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('sent'));
    }

    public function scopeApproved($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('approved'));
    }

    public function scopeSentOrApproved($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('sent'))
                     ->orWhere('document_status_id', '=', DocumentStatuses::getStatusId('approved'));
    }

    public function scopeRejected($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('rejected'));
    }

    public function scopeCanceled($query)
    {
        return $query->where('document_status_id', '=', DocumentStatuses::getStatusId('canceled'));
    }

    public function scopeNotCanceled($query)
    {
        return $query->where('document_status_id', '<>', DocumentStatuses::getStatusId('canceled'));
    }

    public function scopeStatusIn($query, $statuses)
    {
        $statusCodes = [];

        foreach ($statuses as $status)
        {
            $statusCodes[] = DocumentStatuses::getStatusId($status);
        }

        return $query->whereIn('document_status_id', $statusCodes);
    }
    public function scopeStatus($query, $status = null)
    {
        switch ($status)
        {
            case 'draft':
                $query->draft();
                break;
            case 'sent':
                $query->sent();
                break;
            case 'viewed':
                $query->viewed();
                break;
            case 'approved':
                $query->approved();
                break;
            case 'rejected':
                $query->rejected();
                break;
            case 'paid':
                $query->paid();
                break;
            case 'canceled':
                $query->canceled();
                break;
            case 'overdue':
                $query->overdue();
                break;
        }

        return $query;
    }

    public function scopeOverdue($query)
    {
        // Only invoices in Sent status, with a balance qualify to be overdue
        return $query
            ->where('document_status_id', '=', DocumentStatuses::getStatusId('sent'))
            ->where('action_date', '<', date('Y-m-d'))
            ->whereHas('amount', function ($q){
                $q->where('balance', '<>', 0);
            });
    }

    public function scopeYearToDate($query)
    {
        return $query->where('document_date', '>=', date('Y') . '-01-01')
            ->where('document_date', '<=', date('Y') . '-12-31');
    }

    public function scopeThisQuarter($query)
    {
        return $query->where('document_date', '>=', Carbon::now()->firstOfQuarter())
            ->where('document_date', '<=', Carbon::now()->lastOfQuarter());
    }

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->where('document_date', '>=', $fromDate)
            ->where('document_date', '<=', $toDate);
    }

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords)
        {
            $keywords = strtolower($keywords);

            $query->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                ->orWhere('documents.document_date', 'like', '%' . $keywords . '%')
                ->orWhere('action_date', 'like', '%' . $keywords . '%')
                ->orWhere('summary', 'like', '%' . $keywords . '%')
                ->orWhereIn('client_id', function ($query) use ($keywords)
                {
                    $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }
}
