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
use BT\Modules\Activity\Models\Activity;
use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Currencies\Models\Currency;
use BT\Modules\Groups\Models\Group;
use BT\Modules\MailQueue\Models\MailQueue;
use BT\Modules\Notes\Models\Note;
use BT\Modules\Users\Models\User;
use BT\Observers\DocumentObserver;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use BT\Support\FileNames;
use BT\Support\HTML;
use BT\Support\NumberFormatter;
use BT\Support\Statuses\DocumentStatuses;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Parental\HasChildren;

class Document extends Model
{
    use HasChildren;

    use SoftDeletes;

    use SoftCascadeTrait;

    protected static function boot()
    {
        // allow parental children to access DocumentObserver
        // you MUST call the parent boot method
        // in this case the \Illuminate\Database\Eloquent\Model
        parent::boot();

        // using static::observe(...) instead of Config::observe(...)
        // this way the child classes auto-register the observer to their own class
        static::observe(DocumentObserver::class);
    }

    protected $childColumn = 'document_type';

    // todo remove unnecessary appends everywhere...?
    //    protected $appends = ['formatted_document_date', 'formatted_action_date','status_text', 'formatted_summary'];

    protected $casts = ['action_date' => 'datetime', 'document_date' => 'datetime', 'job_date' => 'datetime', 'deleted_at' => 'datetime'];

    protected $guarded = ['id'];

    protected $softCascade = ['documentItems', 'custom', 'amount', 'activities', 'attachments', 'mailQueue', 'notes'];

    public function moduleType(): Attribute
    {
        return new Attribute(get: fn() => class_basename($this));
    }

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

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'audit');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function clientAttachments(): MorphMany
    {
        $relationship = $this->morphMany(Attachment::class, 'attachable');

        if ($this->status_text == 'paid') {
            $relationship->whereIn('client_visibility', [1, 2]);
        } else {
            $relationship->where('client_visibility', 1);
        }
        return $relationship;
    }


    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function custom(): HasOne
    {
        $customclass = $this->module_type . 'Custom';
        return $this->hasOne('BT\Modules\CustomFields\Models\\' . $customclass, strtolower($this->module_type) . '_id');
    }

    public function group(): HasOne
    {
        return $this->hasOne(Group::class);
    }

    public function mailQueue(): MorphMany
    {
        return $this->morphMany(MailQueue::class, 'mailable');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function viewDirectoryName(): Attribute
    {
        return new Attribute(get: fn() => strtolower(class_basename($this)) . 's');
    }

    public function attachmentPath(): Attribute
    {
        return new Attribute(get: fn() => attachment_path($this->view_directory_name . '/' . $this->id));
    }

    public function attachmentPermissionOptions(): Attribute
    {
        return new Attribute(get: fn() => [
            '0' => trans('bt.not_visible'),
            '1' => trans('bt.visible'),
            '2' => trans('bt.visible_after_payment'),
        ]);
    }

    public function formattedCreatedAt(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::format($this->created_at));
    }

    public function formattedDocumentDate(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::format($this->document_date));
    }

    public function formattedActionDate(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::format($this->action_date));
    }

    public function formattedTerms(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->terms));
    }

    public function formattedFooter(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->footer));
    }

    public function lowerCaseBaseclass(): Attribute
    {
        return new Attribute(get: fn() => strtolower(class_basename($this)));
    }

    public function statusText(): Attribute
    {
        $statuses = DocumentStatuses::statuses();
        return new Attribute(get: fn() => $statuses[$this->document_status_id]);
    }

    public function pdfFilename(): Attribute
    {
        return new Attribute(get: fn() => FileNames::document($this));
    }

    public function publicUrl(): Attribute
    {
        return new Attribute(get: fn() => route('clientCenter.public.' . strtolower($this->module_type) . '.show', [$this->url_key]));
    }

//    public function isForeignCurrency(): Attribute
//    {
//        if ($this->currency_code == config('bt.baseCurrency'))
//        {
//            return new Attribute(get: fn() => False);
//        }
//        return new Attribute(get: fn() => True);
//    }

    public function html(): Attribute
    {
        return new Attribute(get: fn() => HTML::document($this));
    }

    public function formattedNumericDiscount(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->discount));
    }

    public function formattedSummary(): Attribute
    {
        return new Attribute(get: fn() => mb_strimwidth((string)$this->summary, 0, 50, '...'));
    }

    /**
     * Gathers a summary of both invoice and item taxes to be displayed on invoice.
     *
     * @return array
     */
    public function summarizedTaxes(): Attribute
    {
        $taxes = [];

        foreach ($this->items as $item) {
            if ($item->taxRate) {
                $key = $item->taxRate->name;

                if (!isset($taxes[$key])) {
                    $taxes[$key] = new \stdClass();
                    $taxes[$key]->name = $item->taxRate->name;
                    $taxes[$key]->percent = $item->taxRate->formatted_percent;
                    $taxes[$key]->total = $item->amount->tax_1;
                    $taxes[$key]->raw_percent = $item->taxRate->percent;
                } else {
                    $taxes[$key]->total += $item->amount->tax_1;
                }
            }

            if ($item->taxRate2) {
                $key = $item->taxRate2->name;

                if (!isset($taxes[$key])) {
                    $taxes[$key] = new \stdClass();
                    $taxes[$key]->name = $item->taxRate2->name;
                    $taxes[$key]->percent = $item->taxRate2->formatted_percent;
                    $taxes[$key]->total = $item->amount->tax_2;
                    $taxes[$key]->raw_percent = $item->taxRate2->percent;
                } else {
                    $taxes[$key]->total += $item->amount->tax_2;
                }
            }
        }

        foreach ($taxes as $key => $tax) {
            $taxes[$key]->total = CurrencyFormatter::format($tax->total, $this->currency);
        }

        return new Attribute(get: fn() => $taxes);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    // not invoiced is where workorder->invoice_id = 0,
    // or where invoice()->invoice_status_id = 1 or 5 ('draft' or 'canceled')
    // or invoice is trashed
    public function scopeNotinvoiced($query)
    {
        $query->where('invoice_id', 0)
            ->orWhereHas('invoice', function ($query) {
                $query->whereIn('document_status_id', [1, 5]);
            })
            ->orWhereHas('invoice', function ($query) {
                $query->whereNotNull('deleted_at');
            });
    }

    public function scopeClientId($query, $clientId = null)
    {
        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        return $query;
    }


    public function scopeCompanyProfileId($query, $companyProfileId)
    {
        if ($companyProfileId) {
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

        foreach ($statuses as $status) {
            $statusCodes[] = DocumentStatuses::getStatusId($status);
        }

        return $query->whereIn('document_status_id', $statusCodes);
    }

    public function scopeStatus($query, $status = null)
    {
        switch ($status) {
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
            ->whereHas('amount', function ($q) {
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
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                ->orWhere('documents.document_date', 'like', '%' . $keywords . '%')
                ->orWhere('action_date', 'like', '%' . $keywords . '%')
                ->orWhere('summary', 'like', '%' . $keywords . '%')
                ->orWhereIn('client_id', function ($query) use ($keywords) {
                    $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }
}
