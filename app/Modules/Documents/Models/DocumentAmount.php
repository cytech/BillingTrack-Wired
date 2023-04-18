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

use BT\Support\CurrencyFormatter;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentAmount extends Model
{
    use SoftDeletes;

    protected $casts = ['deleted_at' => 'datetime'];

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
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

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedSubtotal(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['subtotal'], $this->document->currency));
    }

    public function formattedTax(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['tax'], $this->document->currency));
    }

    public function formattedTotal(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['total'], $this->document->currency));
    }

    public function formattedPaid(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['paid'], $this->document->currency));
    }

    public function formattedBalance(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['balance'], $this->document->currency));
    }

    public function formattedNumericBalance(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->attributes['balance']));
    }

    public function formattedDiscount(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['discount'], $this->document->currency));
    }
    /**
     * Retrieve the formatted total prior to conversion.
     * @return string
     */
//    public function getFormattedTotalWithoutConversionAttribute()
//    {
//        return CurrencyFormatter::format($this->attributes['total'] / $this->document->exchange_rate);
//    }
}
