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

class RecurringInvoiceItemAmount extends Model
{
    use SoftDeletes;

    protected $casts = ['deleted_at' => 'datetime'];
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function item(): BelongsTo
    {
        return $this->belongsTo(RecurringInvoiceItem::class);
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
}
