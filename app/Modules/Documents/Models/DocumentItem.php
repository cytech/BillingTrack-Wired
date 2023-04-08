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
use BT\Support\NumberFormatter;
use BT\Support\Statuses\PurchaseorderItemStatuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentItem extends Model
{
    use SoftDeletes;

    use SoftCascadeTrait;

    protected $connection = 'mysql'; // necessary for livewire error:Queueing collections with multiple model connections is not supported

    protected $softCascade = ['amount'];

    protected $casts = ['deleted_at' => 'datetime'];

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function amount(): HasOne
    {
        return $this->hasOne(DocumentItemAmount::class, 'item_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function workorder(): BelongsTo
    {
        return $this->belongsTo(Workorder::class, 'document_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'document_id');
    }

    public function purchaseorder(): BelongsTo
    {
        return $this->belongsTo(Purchaseorder::class, 'document_id');
    }


    public function taxRate()
    {
        return $this->belongsTo('BT\Modules\TaxRates\Models\TaxRate');
    }

    public function taxRate2()
    {
        return $this->belongsTo('BT\Modules\TaxRates\Models\TaxRate', 'tax_rate_2_id');
    }

    public function products()
    {
        return $this->hasMany('BT\Modules\Products\Models\Product', 'resource_id')
            ->where('resource_table','=','products');
    }

    public function product()
    {
        return $this->belongsTo('BT\Modules\Products\Models\Product',
            'resource_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany('BT\Modules\Employees\Models\Employee', 'id','resource_id');
            //->where('resource_table','=','employees');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedQuantityAttribute()
    {
        return NumberFormatter::format($this->attributes['quantity']);
    }

    public function getFormattedNumericPriceAttribute()
    {
        return NumberFormatter::format($this->attributes['price']);
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyFormatter::format($this->attributes['price'], $this->document->currency);
    }

    public function getFormattedDescriptionAttribute()
    {
        return nl2br($this->attributes['description']);
    }

    public function getStatusTextAttribute()
    {
        $statuses = PurchaseorderItemStatuses::statuses();

        return $statuses[$this->attributes['rec_status_id']];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeOpen($query)
    {
        return $query->where('rec_status_id', '=', PurchaseorderItemStatuses::getStatusId('open'));
    }

    public function scopeReceived($query)
    {
        return $query->where('rec_status_id', '=', PurchaseorderItemStatuses::getStatusId('received'));
    }

    public function scopePartial($query)
    {
        return $query->where('rec_status_id', '=', PurchaseorderItemStatuses::getStatusId('partial'));
    }

    public function scopeCanceled($query)
    {
        return $query->where('rec_status_id', '=', PurchaseorderItemStatuses::getStatusId('canceled'));
    }

    public function scopeExtra($query)
    {
        return $query->where('rec_status_id', '=', PurchaseorderItemStatuses::getStatusId('extra'));
    }
    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereIn('document_id', function ($query) use ($from, $to)
        {
            $query->select('id')
                ->from('documents')
                ->where('document_date', '>=', $from)
                ->where('document_date', '<=', $to);
        });
    }
}
