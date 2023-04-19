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
use BT\Modules\Employees\Models\Employee;
use BT\Modules\Products\Models\Product;
use BT\Modules\TaxRates\Models\TaxRate;
use BT\Support\CurrencyFormatter;
use BT\Support\NumberFormatter;
use BT\Support\Statuses\PurchaseorderItemStatuses;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'document_id');
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


    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function taxRate2(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_2_id');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'resource_id');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'id', 'resource_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedQuantity(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->quantity));
    }

    public function formattedNumericPrice(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->price));
    }

    public function formattedPrice(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->price, $this->document->currency));
    }

    public function formattedDescription(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->description));
    }

    public function recStatusText(): Attribute
    {
            $statuses = PurchaseorderItemStatuses::statuses();
            return new Attribute(get: fn() => $statuses[$this->rec_status_id]);
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
        return $query->whereIn('document_id', function ($query) use ($from, $to) {
            $query->select('id')
                ->from('documents')
                ->where('document_date', '>=', $from)
                ->where('document_date', '<=', $to);
        });
    }
}
