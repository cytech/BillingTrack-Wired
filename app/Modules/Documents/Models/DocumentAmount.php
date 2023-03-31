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

    protected $appends = ['formatted_total'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedSubtotalAttribute()
    {
        return CurrencyFormatter::format($this->attributes['subtotal'], $this->document->currency);
    }

    public function getFormattedTaxAttribute()
    {
        return CurrencyFormatter::format($this->attributes['tax'], $this->document->currency);
    }

    public function getFormattedTotalAttribute()
    {
        return CurrencyFormatter::format($this->attributes['total'], $this->document->currency);
    }
    public function getFormattedPaidAttribute()
    {
        return CurrencyFormatter::format($this->attributes['paid'], $this->document->currency);
    }

    public function getFormattedBalanceAttribute()
    {
        return CurrencyFormatter::format($this->attributes['balance'], $this->document->currency);
    }

    public function getFormattedNumericBalanceAttribute()
    {
        return NumberFormatter::format($this->attributes['balance']);
    }

    public function getFormattedDiscountAttribute()
    {
        return CurrencyFormatter::format($this->attributes['discount'], $this->document->currency);
    }

    /**
     * Retrieve the formatted total prior to conversion.
     * @return string
     */
    public function getFormattedTotalWithoutConversionAttribute()
    {
        return CurrencyFormatter::format($this->attributes['total'] / $this->document->exchange_rate);
    }
}
