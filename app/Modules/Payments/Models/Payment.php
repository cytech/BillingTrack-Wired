<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Payments\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CustomFields\Models\PaymentCustom;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Purchaseorder;
use BT\Modules\MailQueue\Models\MailQueue;
use BT\Modules\Notes\Models\Note;
use BT\Modules\PaymentMethods\Models\PaymentMethod;
use BT\Modules\Vendors\Models\Vendor;
use Carbon\Carbon;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use BT\Support\FileNames;
use BT\Support\HTML;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use SoftDeletes;

    use SoftCascadeTrait;

    protected $softCascade = ['custom', 'mailQueue', 'notes'];

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    protected $casts = ['paid_at' => 'datetime', 'deleted_at' => 'datetime'];

//    protected $appends = ['formatted_paid_at','formatted_amount'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'client_id');
    }

    public function custom(): HasOne
    {
        return $this->hasOne(PaymentCustom::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'invoice_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function purchaseorder(): BelongsTo
    {
        return $this->belongsTo(Purchaseorder::class, 'invoice_id');
    }

    public function mailQueue(): MorphMany
    {
        return $this->morphMany(MailQueue::class, 'mailable');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedPaidAt(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::format($this->paid_at));
    }

    public function formattedAmount(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->amount, $this->invoice->currency ?? ''));
    }

    public function formattedNumericAmount(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->amount));
    }

    public function formattedNote(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->note));
    }

    public function user(): Attribute
    {
        return new Attribute(get: fn() => $this->invoice->user);
    }

    public function html(): Attribute
    {
        return new Attribute(get: fn() => HTML::invoice($this->invoice));
    }

    public function pdfFilename(): Attribute
    {
        return new Attribute(get: fn() => FileNames::invoice($this->invoice));
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeYearToDate($query)
    {
        return $query->where('paid_at', '>=', date('Y') . '-01-01')
            ->where('paid_at', '<=', date('Y') . '-12-31');
    }

    public function scopeThisQuarter($query)
    {
        return $query->where('paid_at', '>=', Carbon::now()->firstOfQuarter())
            ->where('paid_at', '<=', Carbon::now()->lastOfQuarter());
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->where('paid_at', '>=', $from)->where('paid_at', '<=', $to);
    }

    public function scopeYear($query, $year)
    {
        return $query->where('paid_at', '>=', $year . '-01-01')
            ->where('paid_at', '<=', $year . '-12-31');
    }

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where('payments.created_at', 'like', '%' . $keywords . '%')
                ->orWhereIn('invoice_id', function ($query) use ($keywords) {
                    $query->select('id')->from('invoices')->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                        ->orWhere('summary', 'like', '%' . $keywords . '%')
                        ->orWhereIn('client_id', function ($query) use ($keywords) {
                            $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                        });
                })
                ->orWhereIn('payment_method_id', function ($query) use ($keywords) {
                    $query->select('id')->from('payment_methods')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }

    public function scopeClientId($query, $clientId)
    {
        if ($clientId) {
            $query->whereHas('invoice', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            });
        }

        return $query;
    }

    public function scopeInvoiceId($query, $invoiceId)
    {
        if ($invoiceId) {
            $query->whereHas('invoice', function ($query) use ($invoiceId) {
                $query->where('id', $invoiceId);
            });
        }

        return $query;
    }

    public function scopeInvoiceNumber($query, $invoiceNumber)
    {
        if ($invoiceNumber) {
            $query->whereHas('invoice', function ($query) use ($invoiceNumber) {
                $query->where('number', $invoiceNumber);
            });
        }

        return $query;
    }

    public function scopeStatusId($query, $statusId = null)
    {
        if ($statusId == 1) {
            $query->whereHas('Invoice');
        } else {
            $query->whereHas('Purchaseorder');
        }

        return $query;
    }
}
