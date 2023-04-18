<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\RecurringInvoices\Models;

use BT\Support\CurrencyFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringInvoiceAmount extends Model
{
    use SoftDeletes;

    protected $casts = ['deleted_at' => 'datetime'];
    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

//    protected $appends = ['formatted_total'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function recurringInvoice(): BelongsTo
    {
        return $this->belongsTo(RecurringInvoice::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedSubtotal(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['subtotal'], $this->recurringInvoice->currency));
    }

    public function formattedTax(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['tax'], $this->recurringInvoice->currency));
    }

    public function formattedTotal(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['total'], $this->recurringInvoice->currency));
    }

    public function formattedDiscount(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->attributes['discount'], $this->recurringInvoice->currency));
    }

    /**
     * Retrieve the formatted total prior to conversion.
     * @return string
     */
//    public function getFormattedTotalWithoutConversionAttribute()
//    {
//        return CurrencyFormatter::format($this->attributes['total'] / $this->recurringInvoice->exchange_rate);
//    }
}
